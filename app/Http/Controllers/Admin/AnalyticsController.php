<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AnalyticsController extends Controller
{
    public function index(): Response
    {
        $pageVisits = DB::table('analytics_page_views')
            ->where('viewed_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(viewed_at) as date, COUNT(*) as visits')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        $totalPageViews = (int) $pageVisits->sum('visits');
        $avgDailyPageViews = $pageVisits->count() > 0 ? (int) round($totalPageViews / $pageVisits->count()) : 0;

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
            ->limit(15)
            ->get();

        $deviceDistribution = DB::table('analytics_page_views')
            ->selectRaw('COALESCE(device_type, "unknown") as device_type, COUNT(*) as count')
            ->where('viewed_at', '>=', now()->subDays(30))
            ->groupBy('device_type')
            ->orderByDesc('count')
            ->get();

        $uniqueSessions30d = (int) DB::table('analytics_page_views')
            ->where('viewed_at', '>=', now()->subDays(30))
            ->whereNotNull('session_id')
            ->where('session_id', '!=', '')
            ->selectRaw('COUNT(DISTINCT session_id) as aggregate')
            ->value('aggregate');

        $pageViewsByAuth = [
            'logged_in' => (int) DB::table('analytics_page_views')
                ->where('viewed_at', '>=', now()->subDays(30))
                ->whereNotNull('user_id')
                ->count(),
            'guest' => (int) DB::table('analytics_page_views')
                ->where('viewed_at', '>=', now()->subDays(30))
                ->whereNull('user_id')
                ->count(),
        ];

        $topCountries = DB::table('analytics_page_views')
            ->select('country_code', DB::raw('COUNT(*) as count'))
            ->where('viewed_at', '>=', now()->subDays(30))
            ->whereNotNull('country_code')
            ->where('country_code', '!=', '')
            ->groupBy('country_code')
            ->orderByDesc('count')
            ->limit(12)
            ->get();

        $topBrowsers = DB::table('analytics_page_views')
            ->select('browser_name', DB::raw('COUNT(*) as count'))
            ->where('viewed_at', '>=', now()->subDays(30))
            ->whereNotNull('browser_name')
            ->where('browser_name', '!=', '')
            ->groupBy('browser_name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $topOs = DB::table('analytics_page_views')
            ->select('os_name', DB::raw('COUNT(*) as count'))
            ->where('viewed_at', '>=', now()->subDays(30))
            ->whereNotNull('os_name')
            ->where('os_name', '!=', '')
            ->groupBy('os_name')
            ->orderByDesc('count')
            ->limit(8)
            ->get();

        $userRoleDistribution = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->selectRaw('roles.name as role, COUNT(*) as count')
            ->groupBy('roles.name')
            ->get();

        $topReferrers = DB::table('analytics_page_views')
            ->select('referrer', DB::raw('COUNT(*) as count'))
            ->where('viewed_at', '>=', now()->subDays(30))
            ->groupBy('referrer')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return Inertia::render('Admin/Analytics/Index', [
            'pageVisits' => $pageVisits,
            'totalPageViews' => $totalPageViews,
            'avgDailyPageViews' => $avgDailyPageViews,
            'userRegistrationTrends' => $userRegistrationTrends,
            'topPaths' => $topPaths,
            'deviceDistribution' => $deviceDistribution,
            'uniqueSessions30d' => $uniqueSessions30d,
            'pageViewsByAuth' => $pageViewsByAuth,
            'topCountries' => $topCountries,
            'topBrowsers' => $topBrowsers,
            'topOs' => $topOs,
            'userRoleDistribution' => $userRoleDistribution,
            'topReferrers' => $topReferrers,
        ]);
    }
}
