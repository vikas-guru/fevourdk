<?php

namespace App\Support;

use App\Models\NGO;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class NgoWebsiteAnalytics
{
    public static function summary(NGO $ngo): array
    {
        $slug = $ngo->slug;
        if (! is_string($slug) || $slug === '') {
            return self::emptySummary();
        }

        $base = self::scopedBase($slug);
        $ok = fn ($q) => $q->whereNull('status_code')->orWhere('status_code', '<', 400);

        $total = (clone $base)->where($ok)->count();
        $sessions = (int) (clone $base)->where($ok)->whereNotNull('session_id')
            ->selectRaw('COUNT(DISTINCT session_id) as aggregate')
            ->value('aggregate');
        $week = (clone $base)->where($ok)->where('viewed_at', '>=', now()->subDays(7))->count();

        return [
            'total_views' => $total,
            'unique_sessions' => $sessions,
            'views_last_7_days' => $week,
        ];
    }

    public static function dashboard(NGO $ngo): array
    {
        $slug = $ngo->slug;
        if (! is_string($slug) || $slug === '') {
            return self::emptyDashboard();
        }

        $centroids = config('analytics_geo', []);
        $base = self::scopedBase($slug);
        $ok = fn ($q) => $q->whereNull('status_code')->orWhere('status_code', '<', 400);

        $b = (clone $base)->where($ok);

        $totalViews = (clone $b)->count();
        $uniqueSessions = (int) (clone $b)->whereNotNull('session_id')
            ->selectRaw('COUNT(DISTINCT session_id) as aggregate')
            ->value('aggregate');
        $uniqueIps = (int) (clone $b)->whereNotNull('ip_address')
            ->selectRaw('COUNT(DISTINCT ip_address) as aggregate')
            ->value('aggregate');

        $supporters = (int) $ngo->donations()
            ->where('status', 'completed')
            ->whereNotNull('donor_id')
            ->distinct()
            ->count('donor_id');

        $views7 = (clone $b)->where('viewed_at', '>=', now()->subDays(7))->count();
        $views30 = (clone $b)->where('viewed_at', '>=', now()->subDays(30))->count();

        $series30 = self::dailySeries(clone $b, 30);
        $byCountry = self::aggregateBy(clone $b, ['country_code'], 40);
        $byRegion = self::aggregateBy(clone $b, ['country_code', 'region'], 20);
        $byCity = self::aggregateBy(clone $b, ['country_code', 'region', 'city'], 15);
        $byDevice = self::aggregateBy(clone $b, ['device_type'], 8);
        $byBrowser = self::aggregateBy(clone $b, ['browser_name'], 8);
        $topPaths = self::topPaths(clone $b, 12);
        $recent = self::recentSamples(clone $b, 25);

        $geoPoints = [];
        foreach ($byCountry as $row) {
            $code = strtoupper((string) ($row['country_code'] ?? ''));
            if ($code === '' || $code === 'UNKNOWN') {
                continue;
            }
            if (! isset($centroids[$code])) {
                continue;
            }
            [$lat, $lng] = $centroids[$code];
            $geoPoints[] = [
                'code' => $code,
                'label' => $code,
                'views' => (int) $row['views'],
                'sessions' => (int) $row['sessions'],
                'lat' => $lat,
                'lng' => $lng,
            ];
        }

        $viewCounts = array_values(array_column($geoPoints, 'views'));
        $maxCountryViews = max(array_merge([1], $viewCounts));

        return [
            'slug' => $slug,
            'summary' => [
                'total_views' => $totalViews,
                'unique_sessions' => $uniqueSessions,
                'unique_visitors_ip' => $uniqueIps,
                'views_last_7_days' => $views7,
                'views_last_30_days' => $views30,
            ],
            'supporters' => [
                'completed_donors' => $supporters,
                'label' => 'Supporters (completed donors)',
            ],
            'subscribers' => [
                'count' => null,
                'note' => 'Email or WhatsApp subscriber lists are not stored in FEVOURD-K yet. Use your newsletter tool and track signups there, or ask us to add a microsite capture form.',
            ],
            'series30' => $series30,
            'by_country' => $byCountry,
            'by_region' => $byRegion,
            'by_city' => $byCity,
            'by_device' => $byDevice,
            'by_browser' => $byBrowser,
            'top_paths' => $topPaths,
            'recent' => $recent,
            'geo_points' => $geoPoints,
            'max_country_views' => $maxCountryViews,
            'tracked_paths_hint' => [
                'listing' => 'ngos/'.$slug,
                'microsite' => $slug.' and '.$slug.'/*',
            ],
        ];
    }

    private static function emptySummary(): array
    {
        return [
            'total_views' => 0,
            'unique_sessions' => 0,
            'views_last_7_days' => 0,
        ];
    }

    private static function emptyDashboard(): array
    {
        return [
            'slug' => null,
            'summary' => [
                'total_views' => 0,
                'unique_sessions' => 0,
                'unique_visitors_ip' => 0,
                'views_last_7_days' => 0,
                'views_last_30_days' => 0,
            ],
            'supporters' => ['completed_donors' => 0, 'label' => 'Supporters (completed donors)'],
            'subscribers' => [
                'count' => null,
                'note' => 'Email or WhatsApp subscriber lists are not stored in FEVOURD-K yet.',
            ],
            'series30' => [],
            'by_country' => [],
            'by_region' => [],
            'by_city' => [],
            'by_device' => [],
            'by_browser' => [],
            'top_paths' => [],
            'recent' => [],
            'geo_points' => [],
            'max_country_views' => 1,
            'tracked_paths_hint' => null,
        ];
    }

    private static function scopedBase(string $slug): Builder
    {
        $pathListing = 'ngos/'.$slug;
        $pathMicro = $slug;

        return DB::table('analytics_page_views')
            ->where(function ($query) use ($pathListing, $pathMicro) {
                $query->where('path', $pathListing)
                    ->orWhere('path', $pathMicro)
                    ->orWhere('path', 'like', $pathMicro.'/%');
            });
    }

    /**
     * @return list<array{date: string, views: int}>
     */
    private static function dailySeries(Builder $b, int $days): array
    {
        $start = now()->subDays($days - 1)->startOfDay();
        $rows = (clone $b)
            ->where('viewed_at', '>=', $start)
            ->selectRaw('DATE(viewed_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->orderBy('d')
            ->get()
            ->keyBy('d');

        $out = [];
        for ($i = 0; $i < $days; $i++) {
            $d = $start->copy()->addDays($i)->toDateString();
            $out[] = [
                'date' => $d,
                'views' => (int) ($rows[$d]->c ?? 0),
            ];
        }

        return $out;
    }

    /**
     * @param  list<string>  $groupCols
     * @return list<array<string, mixed>>
     */
    private static function aggregateBy(Builder $b, array $groupCols, int $limit): array
    {
        $select = [...$groupCols, DB::raw('COUNT(*) as views'), DB::raw('COUNT(DISTINCT session_id) as sessions')];

        $q = (clone $b)->select($select)->groupBy($groupCols)->orderByDesc('views')->limit($limit);

        return array_map(function ($row) {
            $a = (array) $row;
            foreach ($a as $k => $v) {
                if ($v === null && $k !== 'views' && $k !== 'sessions') {
                    $a[$k] = 'Unknown';
                }
            }

            return $a;
        }, $q->get()->all());
    }

    /**
     * @return list<array{path: string, views: int}>
     */
    private static function topPaths(Builder $b, int $limit): array
    {
        return array_map(
            fn ($row) => ['path' => (string) $row->path, 'views' => (int) $row->c],
            (clone $b)
                ->selectRaw('path, COUNT(*) as c')
                ->groupBy('path')
                ->orderByDesc('c')
                ->limit($limit)
                ->get()
                ->all()
        );
    }

    /**
     * @return list<array{path: string, region: string, city: string, country_code: string, device_type: string, viewed_at: string}>
     */
    private static function recentSamples(Builder $b, int $limit): array
    {
        return (clone $b)
            ->orderByDesc('viewed_at')
            ->limit($limit)
            ->get()
            ->map(function ($row) {
                return [
                    'path' => (string) $row->path,
                    'region' => (string) ($row->region ?? ''),
                    'city' => (string) ($row->city ?? ''),
                    'country_code' => (string) ($row->country_code ?? ''),
                    'device_type' => (string) ($row->device_type ?? ''),
                    'viewed_at' => Carbon::parse($row->viewed_at)->toIso8601String(),
                ];
            })
            ->all();
    }
}
