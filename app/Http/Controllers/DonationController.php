<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\NGOLedgerEntry;
use App\Models\Setting;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class DonationController extends Controller
{
    public function donate()
    {
        $featuredCampaigns = Campaign::with(['ngo'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return Inertia::render('Donate', [
            'featuredCampaigns' => $featuredCampaigns,
            'paymentMethods' => self::publicPaymentMethodFlags(),
        ]);
    }

    /**
     * @return array<string, bool>
     */
    private static function publicPaymentMethodFlags(): array
    {
        return [
            'razorpay' => (bool) Setting::getValue('payment_razorpay_enabled', true),
            'upi' => (bool) Setting::getValue('payment_upi_enabled', true),
            'phonepe' => (bool) Setting::getValue('payment_phonepe_enabled', false),
            'stripe' => (bool) Setting::getValue('payment_stripe_enabled', false),
        ];
    }

    public function index()
    {
        $donations = Donation::with(['user', 'campaign'])
            ->select('id', 'amount', 'created_at', 'user_id', 'campaign_id')
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/Donations/Index', [
            'donations' => $donations,
        ]);
    }

    public function process(Request $request)
    {
        $allowed = array_keys(array_filter(self::publicPaymentMethodFlags()));

        $validated = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => ['required', 'string', Rule::in($allowed)],
            'transaction_id' => 'nullable|string',
        ]);

        $campaign = Campaign::query()->findOrFail($validated['campaign_id']);

        $donation = Donation::create([
            'ngo_id' => $campaign->ngo_id,
            'user_id' => auth()->id(),
            'campaign_id' => $validated['campaign_id'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'],
            'status' => 'completed',
        ]);

        $campaign->increment('raised_amount', $validated['amount']);

        // Auto-post to NGO ledger so finance stays auditable.
        if ($campaign->ngo_id) {
            $lastBalance = (float) (NGOLedgerEntry::query()
                ->where('ngo_id', $campaign->ngo_id)
                ->latest('id')
                ->value('balance_after') ?? 0);

            NGOLedgerEntry::query()->create([
                'ngo_id' => $campaign->ngo_id,
                'entry_date' => now()->toDateString(),
                'type' => 'credit',
                'category' => 'donation',
                'reference_type' => Donation::class,
                'reference_id' => $donation->id,
                'description' => "Donation received for campaign: {$campaign->title}",
                'amount' => $validated['amount'],
                'balance_after' => $lastBalance + (float) $validated['amount'],
            ]);
        }

        UserNotification::query()->create([
            'user_id' => auth()->id(),
            'type' => 'donation',
            'title' => 'Donation successful',
            'body' => "Your donation of ₹{$validated['amount']} was recorded successfully.",
            'target_url' => $campaign?->slug ? "/campaigns/{$campaign->slug}" : '/donate',
            'meta' => [
                'campaign_id' => $campaign?->id,
                'amount' => $validated['amount'],
            ],
        ]);

        return redirect()->route('donations.success')
            ->with('success', 'Donation processed successfully!');
    }

    public function success()
    {
        return inertia('DonationSuccess');
    }

    public function failure()
    {
        return inertia('DonationFailure');
    }
}
