<?php

namespace App\Http\Controllers\NGO;

use App\Http\Controllers\Controller;
use App\Models\NGO;
use App\Models\NGOBankAccount;
use App\Models\NGOLedgerEntry;
use App\Models\NgoExpenseClaim;
use App\Models\NgoOutboundPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class NgoFinanceController extends Controller
{
    private function ngo(): NGO
    {
        $ngo = Auth::user()?->ngo;
        if (! $ngo) {
            abort(403);
        }

        return $ngo;
    }

    private function isNgoAdmin(): bool
    {
        return Auth::user()->hasRole('ngo_admin');
    }

    private function canManageTreasury(): bool
    {
        $u = Auth::user();

        return $u->hasRole('ngo_admin') || $u->hasRole('ngo_finance');
    }

    private function nextLedgerBalance(NGO $ngo): float
    {
        return (float) ($ngo->ledgerEntries()->latest('id')->value('balance_after') ?? 0);
    }

    private function createLedgerDebit(NGO $ngo, string $category, float $amount, ?string $description, ?string $refType, ?int $refId): NGOLedgerEntry
    {
        $last = $this->nextLedgerBalance($ngo);
        $new = $last - $amount;

        return $ngo->ledgerEntries()->create([
            'entry_date' => now()->toDateString(),
            'type' => 'debit',
            'category' => $category,
            'reference_type' => $refType,
            'reference_id' => $refId,
            'description' => $description,
            'amount' => $amount,
            'balance_after' => $new,
        ]);
    }

    public function hub(): Response
    {
        $ngo = $this->ngo();
        $since = Carbon::now()->subDays(30)->startOfDay();

        $inflow = (float) $ngo->ledgerEntries()
            ->where('type', 'credit')
            ->where('entry_date', '>=', $since->toDateString())
            ->sum('amount');

        $outflow = (float) $ngo->ledgerEntries()
            ->where('type', 'debit')
            ->where('entry_date', '>=', $since->toDateString())
            ->sum('amount');

        $pendingClaims = NgoExpenseClaim::query()
            ->where('ngo_id', $ngo->id)
            ->where('status', 'pending')
            ->count();

        $scheduledPay = NgoOutboundPayment::query()
            ->where('ngo_id', $ngo->id)
            ->where('status', 'scheduled')
            ->sum('amount');

        $balance = $this->nextLedgerBalance($ngo);

        return Inertia::render('NGO/Finance/Hub', [
            'ngo' => $ngo,
            'stats' => [
                'ledger_balance' => $balance,
                'inflow_30d' => $inflow,
                'outflow_30d' => $outflow,
                'pending_claims' => $pendingClaims,
                'scheduled_payments_total' => (float) $scheduledPay,
            ],
            'isNgoAdmin' => $this->isNgoAdmin(),
            'canManageTreasury' => $this->canManageTreasury(),
        ]);
    }

    public function activity(): Response
    {
        $ngo = $this->ngo();

        $ledger = $ngo->ledgerEntries()
            ->latest('entry_date')
            ->latest('id')
            ->take(400)
            ->get()
            ->map(fn (NGOLedgerEntry $e) => [
                'id' => 'L'.$e->id,
                'kind' => 'ledger',
                'date' => $e->entry_date?->toDateString(),
                'direction' => $e->type,
                'amount' => (float) $e->amount,
                'category' => $e->category,
                'description' => $e->description,
                'balance_after' => (float) $e->balance_after,
                'reference_type' => $e->reference_type,
                'reference_id' => $e->reference_id,
            ]);

        $scheduled = NgoOutboundPayment::query()
            ->where('ngo_id', $ngo->id)
            ->where('status', 'scheduled')
            ->with(['payeeUser:id,name,email'])
            ->latest('id')
            ->get()
            ->map(fn (NgoOutboundPayment $p) => [
                'id' => 'P'.$p->id,
                'kind' => 'scheduled_payment',
                'date' => $p->created_at?->toDateString(),
                'direction' => 'debit',
                'amount' => (float) $p->amount,
                'category' => $p->category,
                'description' => $p->notes ?? ($p->payee_name ?? $p->payeeUser?->name ?? 'Outbound'),
                'balance_after' => null,
                'reference_type' => null,
                'reference_id' => $p->id,
            ]);

        $rows = $ledger->concat($scheduled)->sortByDesc(function ($r) {
            return ($r['date'] ?? '').'-'.$r['id'];
        })->values();

        return Inertia::render('NGO/Finance/Activity', [
            'ngo' => $ngo,
            'rows' => $rows,
            'isNgoAdmin' => $this->isNgoAdmin(),
            'canManageTreasury' => $this->canManageTreasury(),
        ]);
    }

    public function payments(): Response
    {
        $ngo = $this->ngo();

        $payments = NgoOutboundPayment::query()
            ->where('ngo_id', $ngo->id)
            ->with(['payeeUser:id,name,email', 'recordedBy:id,name'])
            ->latest('id')
            ->limit(200)
            ->get();

        $staff = User::query()
            ->where('ngo_id', $ngo->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return Inertia::render('NGO/Finance/Payments', [
            'ngo' => $ngo,
            'payments' => $payments,
            'staffUsers' => $staff,
            'categories' => ['salary', 'reimbursement', 'vendor', 'stipend', 'advance', 'other'],
            'isNgoAdmin' => $this->isNgoAdmin(),
            'canManageTreasury' => $this->canManageTreasury(),
        ]);
    }

    public function storePayment(Request $request): RedirectResponse
    {
        if (! $this->canManageTreasury()) {
            abort(403);
        }
        $ngo = $this->ngo();

        $validated = $request->validate([
            'payee_user_id' => 'nullable|exists:users,id',
            'payee_name' => 'nullable|string|max:200',
            'category' => 'required|string|max:80',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:bank_transfer,upi,cash,cheque,other',
            'status' => 'required|in:scheduled,paid',
            'utr_reference' => 'nullable|string|max:120',
            'notes' => 'nullable|string|max:2000',
        ]);

        if (! empty($validated['payee_user_id'])) {
            $u = User::query()->find($validated['payee_user_id']);
            if (! $u || (int) $u->ngo_id !== (int) $ngo->id) {
                return back()->withErrors(['payee_user_id' => 'Employee must belong to your NGO.'])->withInput();
            }
        }

        $payeeName = $validated['payee_name'] ?? null;
        if (! $payeeName && ! empty($validated['payee_user_id'])) {
            $payeeName = User::query()->whereKey($validated['payee_user_id'])->value('name');
        }

        DB::transaction(function () use ($ngo, $validated, $payeeName) {
            $payment = NgoOutboundPayment::query()->create([
                'ngo_id' => $ngo->id,
                'payee_user_id' => $validated['payee_user_id'] ?? null,
                'payee_name' => $payeeName,
                'category' => $validated['category'],
                'amount' => $validated['amount'],
                'currency' => 'INR',
                'payment_method' => $validated['payment_method'],
                'status' => $validated['status'],
                'paid_at' => $validated['status'] === 'paid' ? now() : null,
                'utr_reference' => $validated['utr_reference'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'recorded_by_user_id' => Auth::id(),
                'ledger_entry_id' => null,
            ]);

            if ($validated['status'] === 'paid') {
                $entry = $this->createLedgerDebit(
                    $ngo,
                    'outbound_'.$validated['category'],
                    (float) $validated['amount'],
                    $payeeName ? "Payment: {$payeeName}" : 'Outbound payment',
                    NgoOutboundPayment::class,
                    $payment->id
                );
                $payment->update(['ledger_entry_id' => $entry->id]);
            }
        });

        return back()->with('success', $validated['status'] === 'paid'
            ? 'Payment recorded and posted to the cashbook.'
            : 'Payment scheduled. Mark as paid when money leaves the bank.');
    }

    public function markPaymentPaid(Request $request, NgoOutboundPayment $payment): RedirectResponse
    {
        if (! $this->canManageTreasury()) {
            abort(403);
        }
        $ngo = $this->ngo();
        if ((int) $payment->ngo_id !== (int) $ngo->id) {
            abort(404);
        }
        if ($payment->status !== 'scheduled') {
            return back()->with('error', 'Only scheduled payments can be marked paid.');
        }

        $validated = $request->validate([
            'utr_reference' => 'nullable|string|max:120',
        ]);

        DB::transaction(function () use ($ngo, $payment, $validated) {
            $label = $payment->payee_name ?? $payment->payeeUser?->name ?? 'Payee';
            $entry = $this->createLedgerDebit(
                $ngo,
                'outbound_'.$payment->category,
                (float) $payment->amount,
                "Payment: {$label}",
                NgoOutboundPayment::class,
                $payment->id
            );

            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'utr_reference' => $validated['utr_reference'] ?? $payment->utr_reference,
                'ledger_entry_id' => $entry->id,
            ]);
        });

        return back()->with('success', 'Marked paid and posted to the cashbook.');
    }

    public function banking(): Response
    {
        $ngo = $this->ngo()->load(['bankAccounts', 'paymentGateways']);
        $user = Auth::user();

        $isAdmin = $user->hasRole('ngo_admin');
        $showFull = (bool) $ngo->finance_show_full_bank_numbers
            && ($isAdmin || $user->hasRole('ngo_finance'));

        return Inertia::render('NGO/Banking/Index', [
            'ngo' => $ngo,
            'showFullBankNumbers' => $showFull,
            'canConfigureBank' => $isAdmin,
        ]);
    }

    public function updatePreferences(Request $request): RedirectResponse
    {
        if (! $this->isNgoAdmin()) {
            abort(403);
        }
        $ngo = $this->ngo();

        $validated = $request->validate([
            'finance_show_full_bank_numbers' => 'required|boolean',
        ]);

        $ngo->update([
            'finance_show_full_bank_numbers' => $validated['finance_show_full_bank_numbers'],
        ]);

        return back()->with('success', 'Finance display settings updated.');
    }

    public function storeBankAccount(Request $request): RedirectResponse
    {
        if (! $this->isNgoAdmin()) {
            abort(403);
        }
        $ngo = $this->ngo();

        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:200',
            'ifsc_code' => 'required|string|max:20',
            'branch_address' => 'nullable|string|max:500',
        ]);

        NGOBankAccount::query()->create([
            'ngo_id' => $ngo->id,
            'bank_name' => $validated['bank_name'],
            'account_number' => preg_replace('/\s+/', '', $validated['account_number']),
            'account_holder_name' => $validated['account_holder_name'],
            'ifsc_code' => strtoupper(preg_replace('/\s+/', '', $validated['ifsc_code'])),
            'branch_address' => $validated['branch_address'] ?? null,
            'verification_status' => 'pending',
        ]);

        return back()->with('success', 'Bank account added.');
    }

    public function updateBankAccount(Request $request, NGOBankAccount $bankAccount): RedirectResponse
    {
        if (! $this->isNgoAdmin()) {
            abort(403);
        }
        $ngo = $this->ngo();
        if ((int) $bankAccount->ngo_id !== (int) $ngo->id) {
            abort(404);
        }

        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:200',
            'ifsc_code' => 'required|string|max:20',
            'branch_address' => 'nullable|string|max:500',
        ]);

        $bankAccount->update([
            'bank_name' => $validated['bank_name'],
            'account_number' => preg_replace('/\s+/', '', $validated['account_number']),
            'account_holder_name' => $validated['account_holder_name'],
            'ifsc_code' => strtoupper(preg_replace('/\s+/', '', $validated['ifsc_code'])),
            'branch_address' => $validated['branch_address'] ?? null,
        ]);

        return back()->with('success', 'Bank account updated.');
    }

    public function destroyBankAccount(NGOBankAccount $bankAccount): RedirectResponse
    {
        if (! $this->isNgoAdmin()) {
            abort(403);
        }
        $ngo = $this->ngo();
        if ((int) $bankAccount->ngo_id !== (int) $ngo->id) {
            abort(404);
        }
        $bankAccount->delete();

        return back()->with('success', 'Bank account removed.');
    }
}
