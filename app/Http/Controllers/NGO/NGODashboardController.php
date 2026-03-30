<?php

namespace App\Http\Controllers\NGO;

use App\Http\Controllers\Controller;
use App\Models\NGOLedgerEntry;
use App\Support\NgoWebsiteAnalytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NGODashboardController extends Controller
{
    /**
     * Display NGO dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $ngo = $user->ngo;

        if (! $ngo) {
            return redirect()->route('welcome')
                ->with('error', 'NGO not found or access denied.');
        }

        // Get NGO statistics
        $stats = [
            'campaigns' => $ngo->campaigns()->where('status', 'active')->count(),
            'totalRaised' => $ngo->donations()->where('status', 'completed')->sum('amount'),
            'donors' => $ngo->donations()->where('status', 'completed')->distinct('donor_id')->count('donor_id'),
            'thisMonth' => $ngo->donations()
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
        ];

        // Get recent donations
        $recentDonations = $ngo->donations()
            ->with(['campaign', 'donor'])
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($donation) {
                return [
                    'id' => $donation->id,
                    'amount' => $donation->amount,
                    'donor_name' => $donation->donor ? $donation->donor->name : 'Anonymous',
                    'campaign_title' => $donation->campaign ? $donation->campaign->title : 'General Donation',
                    'created_at' => $donation->created_at->toISOString(),
                ];
            });

        return Inertia::render('NGO/Dashboard', [
            'ngo' => $ngo,
            'stats' => $stats,
            'recentDonations' => $recentDonations,
            'ledgerSummary' => [
                'current_balance' => (float) ($ngo->ledgerEntries()->latest('id')->value('balance_after') ?? 0),
                'entries_count' => $ngo->ledgerEntries()->count(),
            ],
            'welcomeAfterRegistration' => (bool) $request->session()->pull('ngo_registration_welcome', false),
        ]);
    }

    /**
     * Show NGO profile
     */
    public function profile()
    {
        $user = Auth::user();
        $ngo = $user->ngo;

        if (! $ngo) {
            return redirect()->route('welcome')
                ->with('error', 'NGO not found or access denied.');
        }

        $ngo->load(['city', 'documents', 'bankAccounts', 'paymentGateways']);

        return Inertia::render('NGO/Profile', [
            'ngo' => $ngo,
            'websiteAnalytics' => NgoWebsiteAnalytics::summary($ngo),
        ]);
    }

    public function analytics()
    {
        $ngo = $this->resolveNgoOrRedirect();
        if (! $ngo) {
            return redirect()->route('welcome')
                ->with('error', 'NGO not found or access denied.');
        }

        return Inertia::render('NGO/Analytics', [
            'ngo' => $ngo,
            'analytics' => NgoWebsiteAnalytics::dashboard($ngo),
        ]);
    }

    /**
     * Show campaigns
     */
    public function campaigns()
    {
        $user = Auth::user();
        $ngo = $user->ngo;

        if (! $ngo) {
            return redirect()->route('welcome')
                ->with('error', 'NGO not found or access denied.');
        }

        $campaigns = $ngo->campaigns()
            ->withCount(['donations' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->withSum(['donations' => function ($query) {
                $query->where('status', 'completed');
            }], 'amount')
            ->latest()
            ->paginate(10);

        return Inertia::render('NGO/Campaigns/Index', [
            'ngo' => $ngo,
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * Show donations
     */
    public function donations()
    {
        $user = Auth::user();
        $ngo = $user->ngo;

        if (! $ngo) {
            return redirect()->route('welcome')
                ->with('error', 'NGO not found or access denied.');
        }

        $donations = $ngo->donations()
            ->with(['campaign', 'donor.user'])
            ->latest()
            ->paginate(20);

        return Inertia::render('NGO/Donations/Index', [
            'ngo' => $ngo,
            'donations' => $donations,
        ]);
    }

    /**
     * Show documents
     */
    public function documents()
    {
        $user = Auth::user();
        $ngo = $user->ngo;

        if (! $ngo) {
            return redirect()->route('welcome')
                ->with('error', 'NGO not found or access denied.');
        }

        $documents = $ngo->documents()
            ->latest()
            ->get();

        return Inertia::render('NGO/Documents/Index', [
            'ngo' => $ngo,
            'documents' => $documents,
        ]);
    }

    /**
     * Show banking settings
     */
    public function digitalization()
    {
        $ngo = $this->resolveNgoOrRedirect();
        if (! $ngo) {
            return redirect()->route('welcome')
                ->with('error', 'NGO not found or access denied.');
        }

        return Inertia::render('NGO/Digitalization', [
            'ngo' => $ngo,
            'previewUrl' => '/ngo/website-preview',
        ]);
    }

    public function updateDigitalization(Request $request)
    {
        $ngo = $this->resolveNgoOrRedirect();
        if (! $ngo) {
            return redirect()->route('welcome')
                ->with('error', 'NGO not found or access denied.');
        }

        $validated = $request->validate([
            'website_url' => 'nullable|url|max:255',
            'custom_domain' => 'nullable|string|max:255',
            'theme_color' => ['nullable', 'regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/'],
            'tawk_property_id' => 'nullable|string|max:255',
            'tawk_widget_id' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'google_business_location_id' => 'nullable|string|max:255',
            'google_business_auto_post' => 'nullable|boolean',
            'digitalization_settings' => 'nullable|array',
            'microsite_json' => 'nullable|string|max:50000',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if (! empty($validated['microsite_json'])) {
            $decoded = json_decode($validated['microsite_json'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $dig = is_array($ngo->digitalization_settings) ? $ngo->digitalization_settings : [];
                $dig['microsite'] = array_replace_recursive($dig['microsite'] ?? [], $decoded);
                $validated['digitalization_settings'] = $dig;
            }
            unset($validated['microsite_json']);
        } else {
            unset($validated['microsite_json']);
        }

        $validated['google_business_auto_post'] = (bool) ($validated['google_business_auto_post'] ?? false);
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('ngo_logos', 'public');
        }
        if (! empty($validated['custom_domain'])) {
            $validated['custom_domain_status'] = 'pending';
        }

        $ngo->update($validated);

        return back()->with('success', 'Digitalization settings updated.');
    }

    public function postUpdate()
    {
        $ngo = $this->resolveNgoOrRedirect();
        if (! $ngo) {
            return redirect()->route('welcome')
                ->with('error', 'NGO not found or access denied.');
        }

        return Inertia::render('NGO/PostUpdate', [
            'ngo' => $ngo,
        ]);
    }

    public function ledger()
    {
        $ngo = $this->resolveNgoOrRedirect();
        if (! $ngo) {
            return redirect()->route('welcome')
                ->with('error', 'NGO not found or access denied.');
        }

        $entries = $ngo->ledgerEntries()
            ->latest('entry_date')
            ->latest('id')
            ->take(200)
            ->get()
            ->map(function (NGOLedgerEntry $entry) {
                return [
                    'id' => $entry->id,
                    'entry_date' => $entry->entry_date?->toDateString(),
                    'type' => $entry->type,
                    'category' => $entry->category,
                    'description' => $entry->description,
                    'amount' => (float) $entry->amount,
                    'balance_after' => (float) $entry->balance_after,
                    'reference_type' => $entry->reference_type,
                    'reference_id' => $entry->reference_id,
                ];
            });

        return Inertia::render('NGO/Ledger', [
            'ngo' => $ngo,
            'entries' => $entries,
            'currentBalance' => (float) ($ngo->ledgerEntries()->latest('id')->value('balance_after') ?? 0),
        ]);
    }

    public function storeLedgerEntry(Request $request)
    {
        $ngo = $this->resolveNgoOrRedirect();
        if (! $ngo) {
            return redirect()->route('welcome')
                ->with('error', 'NGO not found or access denied.');
        }

        $validated = $request->validate([
            'entry_date' => 'required|date',
            'type' => 'required|in:credit,debit',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $lastBalance = (float) ($ngo->ledgerEntries()->latest('id')->value('balance_after') ?? 0);
        $signedAmount = $validated['type'] === 'credit'
            ? (float) $validated['amount']
            : -1 * (float) $validated['amount'];
        $newBalance = $lastBalance + $signedAmount;

        $ngo->ledgerEntries()->create([
            'entry_date' => $validated['entry_date'],
            'type' => $validated['type'],
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'amount' => $validated['amount'],
            'balance_after' => $newBalance,
        ]);

        return back()->with('success', 'Ledger entry added.');
    }

    private function resolveNgoOrRedirect()
    {
        $user = Auth::user();

        return $user?->ngo;
    }
}
