<?php

namespace App\Http\Controllers\NGO;

use App\Http\Controllers\Controller;
use App\Models\NgoExpenseClaim;
use App\Models\NGO;
use App\Models\NGOLedgerEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class FinanceClaimsController extends Controller
{
    private function ngo()
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

    private function canApproveClaims(): bool
    {
        $u = Auth::user();

        return $u->hasRole('ngo_admin') || $u->hasRole('ngo_finance');
    }

    public function index(): Response
    {
        $ngo = $this->ngo();

        $query = NgoExpenseClaim::query()
            ->where('ngo_id', $ngo->id)
            ->with(['user:id,name,email', 'reviewedBy:id,name']);

        if (! $this->isNgoAdmin() && ! $this->canApproveClaims()) {
            $query->where('user_id', Auth::id());
        }

        $claims = $query->orderByDesc('created_at')->limit(200)->get();

        return Inertia::render('NGO/Finance/Claims', [
            'ngo' => $ngo,
            'claims' => $claims,
            'isNgoAdmin' => $this->isNgoAdmin(),
            'canApproveClaims' => $this->canApproveClaims(),
            'categories' => ['travel', 'meals', 'supplies', 'events', 'communications', 'other'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $ngo = $this->ngo();

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string|max:80',
            'description' => 'nullable|string|max:500',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $path = null;
        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('ngo_expense_receipts', 'public');
        }

        NgoExpenseClaim::query()->create([
            'ngo_id' => $ngo->id,
            'user_id' => Auth::id(),
            'amount' => $validated['amount'],
            'currency' => 'INR',
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'receipt_path' => $path,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Expense claim submitted.');
    }

    public function decide(Request $request, NgoExpenseClaim $claim): RedirectResponse
    {
        if (! $this->canApproveClaims()) {
            abort(403);
        }
        $ngo = $this->ngo();
        if ((int) $claim->ngo_id !== (int) $ngo->id) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'review_notes' => 'nullable|string|max:2000',
            'ledger_note' => 'nullable|string|max:500',
        ]);

        $claim->update([
            'status' => $validated['status'],
            'reviewed_by_user_id' => Auth::id(),
            'reviewed_at' => now(),
            'review_notes' => $validated['review_notes'] ?? null,
            'ledger_note' => $validated['ledger_note'] ?? null,
        ]);

        if ($validated['status'] === 'approved') {
            $this->postApprovedClaimToLedger($ngo, $claim, $validated['ledger_note'] ?? null);
        }

        return back()->with('success', $validated['status'] === 'approved'
            ? 'Claim approved and posted to the cashbook.'
            : 'Claim updated.');
    }

    private function postApprovedClaimToLedger(NGO $ngo, NgoExpenseClaim $claim, ?string $ledgerNote): void
    {
        $exists = NGOLedgerEntry::query()
            ->where('ngo_id', $ngo->id)
            ->where('reference_type', NgoExpenseClaim::class)
            ->where('reference_id', $claim->id)
            ->exists();

        if ($exists) {
            return;
        }

        $last = (float) ($ngo->ledgerEntries()->latest('id')->value('balance_after') ?? 0);
        $amount = (float) $claim->amount;
        $claim->loadMissing('user:id,name');

        $ngo->ledgerEntries()->create([
            'entry_date' => now()->toDateString(),
            'type' => 'debit',
            'category' => 'expense_claim',
            'reference_type' => NgoExpenseClaim::class,
            'reference_id' => $claim->id,
            'description' => trim(
                'Expense claim: '.($claim->user?->name ?? 'Staff').
                ($ledgerNote ? " — {$ledgerNote}" : '').
                ($claim->description ? " — {$claim->description}" : '')
            ),
            'amount' => $amount,
            'balance_after' => $last - $amount,
        ]);
    }
}
