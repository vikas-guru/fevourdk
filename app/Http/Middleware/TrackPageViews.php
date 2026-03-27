<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackPageViews
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldTrack($request, $response)) {
            return $response;
        }

        $agentMeta = $this->extractUserAgentMeta($request->userAgent() ?? '');
        $routeName = optional($request->route())->getName();

        DB::table('analytics_page_views')->insert([
            'user_id' => optional($request->user())->id,
            'session_id' => $request->session()->getId(),
            'route_name' => is_string($routeName) ? $routeName : null,
            'path' => substr($request->path(), 0, 500),
            'full_url' => substr($request->fullUrl(), 0, 1000),
            'referrer' => $request->headers->get('referer'),
            'ip_address' => $request->ip(),
            'country_code' => $request->header('CF-IPCountry')
                ?? $request->header('X-Country-Code')
                ?? null,
            'region' => $request->header('CF-Region')
                ?? $request->header('X-Region')
                ?? null,
            'city' => $request->header('CF-IPCity')
                ?? $request->header('X-City')
                ?? null,
            'accept_language' => $request->header('Accept-Language'),
            'device_type' => $agentMeta['device_type'],
            'browser_name' => $agentMeta['browser_name'],
            'os_name' => $agentMeta['os_name'],
            'status_code' => $response->getStatusCode(),
            'user_agent' => $request->userAgent(),
            'viewed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $response;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET')) {
            return false;
        }

        if ($request->expectsJson() || $request->ajax()) {
            return false;
        }

        if ($request->is('build/*', 'storage/*', 'favicon.ico', 'icons/*', 'manifest.json', 'up')) {
            return false;
        }

        if ($response->getStatusCode() >= 500) {
            return false;
        }

        return true;
    }

    private function extractUserAgentMeta(string $ua): array
    {
        $browserName = 'Unknown';
        $osName = 'Unknown';
        $deviceType = 'desktop';

        if (preg_match('/Edg\/([0-9\.]+)/', $ua)) {
            $browserName = 'Edge';
        } elseif (preg_match('/Chrome\/([0-9\.]+)/', $ua)) {
            $browserName = 'Chrome';
        } elseif (preg_match('/Firefox\/([0-9\.]+)/', $ua)) {
            $browserName = 'Firefox';
        } elseif (preg_match('/Version\/([0-9\.]+).*Safari/', $ua)) {
            $browserName = 'Safari';
        }

        if (str_contains($ua, 'Android')) {
            $osName = 'Android';
            $deviceType = 'mobile';
        } elseif (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) {
            $osName = 'iOS';
            $deviceType = str_contains($ua, 'iPad') ? 'tablet' : 'mobile';
        } elseif (str_contains($ua, 'Windows')) {
            $osName = 'Windows';
        } elseif (str_contains($ua, 'Mac OS X')) {
            $osName = 'macOS';
        } elseif (str_contains($ua, 'Linux')) {
            $osName = 'Linux';
        }

        if (str_contains($ua, 'Mobile')) {
            $deviceType = 'mobile';
        } elseif (str_contains($ua, 'Tablet') || str_contains($ua, 'iPad')) {
            $deviceType = 'tablet';
        }

        return [
            'browser_name' => $browserName,
            'os_name' => $osName,
            'device_type' => $deviceType,
        ];
    }
}

