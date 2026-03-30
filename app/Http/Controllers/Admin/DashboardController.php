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

        $totalPageViews = (int) DB::table('analytics_page_views')
            ->where('viewed_at', '>=', now()->subDays(30))
            ->count();

        $daysWithViews = (int) DB::table('analytics_page_views')
            ->where('viewed_at', '>=', now()->subDays(30))
            ->selectRaw('COUNT(DISTINCT DATE(viewed_at)) as aggregate')
            ->value('aggregate');

        $avgDailyPageViews = $daysWithViews > 0 ? (int) round($totalPageViews / $daysWithViews) : 0;

        $mapUsers = User::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', 0)
            ->where('longitude', '!=', 0)
            ->with('roles')
            ->orderByDesc('updated_at')
            ->limit(5000)
            ->get([
                'id', 'name', 'email', 'user_type', 'is_active', 'latitude', 'longitude',
                'district_name', 'state_name', 'email_verified_at', 'last_login_at',
            ])
            ->map(static function (User $u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'user_type' => $u->user_type,
                    'is_active' => (bool) $u->is_active,
                    'latitude' => (float) $u->latitude,
                    'longitude' => (float) $u->longitude,
                    'district_name' => $u->district_name,
                    'state_name' => $u->state_name,
                    'email_verified' => $u->email_verified_at !== null,
                    'roles' => $u->roles->pluck('name')->values()->all(),
                    'last_login_at' => $u->last_login_at?->toIso8601String(),
                ];
            });

        $mapNgos = NGO::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', 0)
            ->where('longitude', '!=', 0)
            ->orderByDesc('updated_at')
            ->limit(2000)
            ->get(['id', 'name', 'email', 'verification_status', 'latitude', 'longitude', 'is_active'])
            ->map(static function (NGO $n) {
                return [
                    'id' => $n->id,
                    'name' => $n->name,
                    'email' => $n->email,
                    'verification_status' => $n->verification_status,
                    'is_active' => (bool) $n->is_active,
                    'latitude' => (float) $n->latitude,
                    'longitude' => (float) $n->longitude,
                ];
            });

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
            'totalPageViews' => $totalPageViews,
            'avgDailyPageViews' => $avgDailyPageViews,
            'mapUsers' => $mapUsers,
            'mapNgos' => $mapNgos,
        ]);
    }
}
