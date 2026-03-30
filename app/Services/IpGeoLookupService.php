<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class IpGeoLookupService
{
    /**
     * @return array{country_code: ?string, region_name: ?string, city: ?string, approx_lat: ?float, approx_lng: ?float}|null
     */
    public function lookup(?string $ip): ?array
    {
        if (! config('services.ip_geo.enabled', true) || $ip === null || $ip === '') {
            return null;
        }

        if (! filter_var($ip, FILTER_VALIDATE_IP)) {
            return null;
        }

        if (! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return [
                'country_code' => null,
                'region_name' => null,
                'city' => null,
                'approx_lat' => null,
                'approx_lng' => null,
            ];
        }

        return Cache::remember("ip_geo:".$ip, (int) config('services.ip_geo.cache_ttl', 3600), function () use ($ip) {
            try {
                $response = Http::timeout((int) config('services.ip_geo.timeout', 2))
                    ->get("http://ip-api.com/json/{$ip}", [
                        'fields' => 'status,countryCode,regionName,city,lat,lon,query',
                    ]);

                if (! $response->successful()) {
                    return null;
                }

                $data = $response->json();
                if (($data['status'] ?? '') !== 'success') {
                    return null;
                }

                return [
                    'country_code' => isset($data['countryCode']) ? strtoupper((string) $data['countryCode']) : null,
                    'region_name' => $data['regionName'] ?? null,
                    'city' => $data['city'] ?? null,
                    'approx_lat' => isset($data['lat']) ? (float) $data['lat'] : null,
                    'approx_lng' => isset($data['lon']) ? (float) $data['lon'] : null,
                ];
            } catch (\Throwable $e) {
                Log::warning('ip_geo.lookup_failed', ['ip' => $ip, 'message' => $e->getMessage()]);

                return null;
            }
        });
    }
}
