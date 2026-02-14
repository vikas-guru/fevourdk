<?php

namespace App\Http\Controllers\NGO;

use App\Http\Controllers\Controller;
use App\Models\NGO;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class NGODashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:ngo_admin,ngo_staff');
    }

    /**
     * Display NGO dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $ngo = $user->ngo;

        if (!$ngo) {
            return redirect()->route('dashboard')
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
                ->sum('amount')
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
                    'created_at' => $donation->created_at->toISOString()
                ];
            });

        return Inertia::render('NGO/Dashboard', [
            'ngo' => $ngo,
            'stats' => $stats,
            'recentDonations' => $recentDonations
        ]);
    }

    /**
     * Show NGO profile
     */
    public function profile()
    {
        $user = Auth::user();
        $ngo = $user->ngo;

        if (!$ngo) {
            return redirect()->route('dashboard')
                ->with('error', 'NGO not found or access denied.');
        }

        return Inertia::render('NGO/Profile', [
            'ngo' => $ngo->load(['city', 'documents', 'bankAccounts', 'paymentGateways'])
        ]);
    }

    /**
     * Show campaigns
     */
    public function campaigns()
    {
        $user = Auth::user();
        $ngo = $user->ngo;

        if (!$ngo) {
            return redirect()->route('dashboard')
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
            'campaigns' => $campaigns
        ]);
    }

    /**
     * Show donations
     */
    public function donations()
    {
        $user = Auth::user();
        $ngo = $user->ngo;

        if (!$ngo) {
            return redirect()->route('dashboard')
                ->with('error', 'NGO not found or access denied.');
        }

        $donations = $ngo->donations()
            ->with(['campaign', 'donor'])
            ->latest()
            ->paginate(20);

        return Inertia::render('NGO/Donations/Index', [
            'donations' => $donations
        ]);
    }

    /**
     * Show documents
     */
    public function documents()
    {
        $user = Auth::user();
        $ngo = $user->ngo;

        if (!$ngo) {
            return redirect()->route('dashboard')
                ->with('error', 'NGO not found or access denied.');
        }

        $documents = $ngo->documents()
            ->latest()
            ->get();

        return Inertia::render('NGO/Documents/Index', [
            'documents' => $documents
        ]);
    }

    /**
     * Show banking settings
     */
    public function banking()
    {
        $user = Auth::user();
        $ngo = $user->ngo;

        if (!$ngo) {
            return redirect()->route('dashboard')
                ->with('error', 'NGO not found or access denied.');
        }

        return Inertia::render('NGO/Banking/Index', [
            'ngo' => $ngo->load(['bankAccounts', 'paymentGateways'])
        ]);
    }
}
