<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\NGO;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_ngos' => NGO::count(),
            'total_campaigns' => Campaign::count(),
            'total_donations' => Donation::where('status', 'completed')->count(),
            'total_donation_amount' => (float) Donation::where('status', 'completed')->sum('amount'),
            'total_programs' => Program::count(),
            'active_programs' => Program::where('is_active', true)->count(),
            'featured_programs' => Program::where('is_featured', true)->count(),
            'pending_ngos' => NGO::where('verification_status', 'pending')->count(),
            'verified_ngos' => NGO::where('verification_status', 'verified')->count(),
            'pending_individuals' => User::query()
                ->where('user_type', 'individual')
                ->where(function ($q) {
                    $q->whereNull('email_verified_at')->orWhere('is_active', false);
                })
                ->count(),
        ];

        $donationStats = Donation::selectRaw('
                COUNT(*) as total_donations,
                COALESCE(SUM(amount), 0) as total_amount,
                COALESCE(AVG(amount), 0) as avg_amount
            ')->first();

        $recentUsers = User::latest()->take(5)->get(['id', 'name', 'email', 'created_at']);
        $recentNGOs = NGO::latest()->take(8)->get(['id', 'name', 'email', 'verification_status', 'is_active', 'created_at']);
        $recentCampaigns = Campaign::with('ngo')->latest()->take(5)->get(['id', 'title', 'ngo_id', 'target_amount', 'raised_amount', 'created_at']);
        $recentIndividuals = User::query()
            ->where('user_type', 'individual')
            ->latest()
            ->take(8)
            ->get(['id', 'name', 'email', 'phone', 'is_active', 'email_verified_at', 'created_at']);

        $monthlyDonations = Donation::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as count,
                COALESCE(SUM(amount), 0) as total
            ')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $ngoStatusDistribution = NGO::selectRaw('verification_status as status, COUNT(*) as count')
            ->groupBy('verification_status')
            ->get();

        $topCampaigns = Campaign::with(['ngo'])
            ->withCount('donations')
            ->orderBy('donations_count', 'desc')
            ->take(5)
            ->get(['id', 'title', 'ngo_id', 'target_amount', 'raised_amount']);

        $userRoleDistribution = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->selectRaw('roles.name as role, COUNT(*) as count')
            ->groupBy('roles.name')
            ->get();

        $pageVisits = DB::table('analytics_page_views')
            ->where('viewed_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(viewed_at) as date, COUNT(*) as visits')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        $totalPageViews = $pageVisits->sum('visits');
        $avgDailyPageViews = $pageVisits->count() > 0 ? round($totalPageViews / $pageVisits->count()) : 0;

        $userRegistrationTrends = User::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as count
            ')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topPaths = DB::table('analytics_page_views')
            ->selectRaw('path, COUNT(*) as visits')
            ->where('viewed_at', '>=', now()->subDays(30))
            ->groupBy('path')
            ->orderByDesc('visits')
            ->limit(10)
            ->get();

        $deviceDistribution = DB::table('analytics_page_views')
            ->selectRaw('COALESCE(device_type, "unknown") as device_type, COUNT(*) as count')
            ->where('viewed_at', '>=', now()->subDays(30))
            ->groupBy('device_type')
            ->orderByDesc('count')
            ->get();

        return Inertia::render('Admin/DashboardSimple', [
            'stats' => $stats,
            'donationStats' => $donationStats,
            'recentUsers' => $recentUsers,
            'recentNGOs' => $recentNGOs,
            'recentIndividuals' => $recentIndividuals,
            'recentCampaigns' => $recentCampaigns,
            'monthlyDonations' => $monthlyDonations,
            'ngoStatusDistribution' => $ngoStatusDistribution,
            'topCampaigns' => $topCampaigns,
            'userRoleDistribution' => $userRoleDistribution,
            'pageVisits' => $pageVisits,
            'totalPageViews' => $totalPageViews,
            'avgDailyPageViews' => $avgDailyPageViews,
            'userRegistrationTrends' => $userRegistrationTrends,
            'topPaths' => $topPaths,
            'deviceDistribution' => $deviceDistribution,
        ]);
    }
}
