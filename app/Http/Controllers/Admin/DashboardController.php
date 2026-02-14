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
        // Get overall statistics
        $stats = [
            'total_users' => User::count(),
            'total_ngos' => NGO::count(),
            'total_campaigns' => Campaign::count(),
            'total_donations' => Donation::count(),
            'total_programs' => Program::count(),
            'active_programs' => Program::where('is_active', true)->count(),
            'featured_programs' => Program::where('is_featured', true)->count(),
            'pending_ngos' => NGO::where('verification_status', 'pending')->count(),
            'verified_ngos' => NGO::where('verification_status', 'verified')->count(),
        ];

        // Get donation statistics
        $donationStats = Donation::selectRaw('
                COUNT(*) as total_donations,
                COALESCE(SUM(amount), 0) as total_amount,
                COALESCE(AVG(amount), 0) as avg_amount
            ')->first();

        // Get recent activities
        $recentUsers = User::latest()->take(5)->get(['id', 'name', 'email', 'created_at']);
        $recentNGOs = NGO::latest()->take(5)->get(['id', 'name', 'verification_status', 'created_at']);
        $recentCampaigns = Campaign::with('ngo')->latest()->take(5)->get(['id', 'title', 'ngo_id', 'target_amount', 'raised_amount', 'created_at']);
        $recentPrograms = Program::latest()->take(5)->get(['id', 'title', 'category', 'is_featured', 'is_active', 'created_at']);

        // Get monthly donation trends (last 6 months)
        $monthlyDonations = Donation::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as count,
                COALESCE(SUM(amount), 0) as total
            ')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get NGO status distribution
        $ngoStatusDistribution = NGO::selectRaw('verification_status as status, COUNT(*) as count')
            ->groupBy('verification_status')
            ->get();

        // Get top campaigns by donations
        $topCampaigns = Campaign::with(['ngo'])
            ->withCount('donations')
            ->orderBy('donations_count', 'desc')
            ->take(5)
            ->get(['id', 'title', 'ngo_id', 'target_amount', 'raised_amount']);

        // Get user role distribution
        $userRoleDistribution = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->selectRaw('roles.name as role, COUNT(*) as count')
            ->groupBy('roles.name')
            ->get();

        // Get page visit analytics (last 30 days)
        $pageVisits = DB::table('sessions')
            ->where('last_activity', '>=', now()->subDays(30))
            ->selectRaw('DATE(FROM_UNIXTIME(last_activity)) as date, COUNT(*) as visits')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        $totalPageViews = $pageVisits->sum('visits');
        $avgDailyPageViews = $pageVisits->count() > 0 ? round($totalPageViews / $pageVisits->count()) : 0;

        // Get user registration trends (last 6 months)
        $userRegistrationTrends = User::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as count
            ')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'donationStats' => $donationStats,
            'recentUsers' => $recentUsers,
            'recentNGOs' => $recentNGOs,
            'recentCampaigns' => $recentCampaigns,
            'recentPrograms' => $recentPrograms,
            'monthlyDonations' => $monthlyDonations,
            'ngoStatusDistribution' => $ngoStatusDistribution,
            'topCampaigns' => $topCampaigns,
            'userRoleDistribution' => $userRoleDistribution,
            'pageVisits' => $pageVisits,
            'totalPageViews' => $totalPageViews,
            'avgDailyPageViews' => $avgDailyPageViews,
            'userRegistrationTrends' => $userRegistrationTrends,
        ]);
    }
}
