<?php

namespace App\Support;

use App\Models\NgoTrustedLoginIp;
use App\Models\NGO;
final class NgoLoginGeoEvaluator
{
    /**
     * @param  array<string, mixed>|null  $geo
     * @return array{allowed: bool, reason: ?string, user_message: ?string}
     */
    public static function evaluate(NGO $ngo, ?array $geo, string $ip): array
    {
        $policy = $ngo->login_geo_policy ?? 'log_only';

        if ($policy === 'none') {
            return ['allowed' => true, 'reason' => null, 'user_message' => null];
        }

        if ($policy === 'log_only') {
            return ['allowed' => true, 'reason' => 'log_only', 'user_message' => null];
        }

        $isPublic = filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) !== false;

        if (! $isPublic) {
            return ['allowed' => true, 'reason' => 'private_ip', 'user_message' => null];
        }

        $failClosed = (bool) $ngo->login_geo_fail_closed;

        if ($policy === 'country_in') {
            if (! $geo || empty($geo['country_code'])) {
                return $failClosed
                    ? ['allowed' => false, 'reason' => 'geo_unknown', 'user_message' => 'We could not verify your network location. Contact your NGO admin.']
                    : ['allowed' => true, 'reason' => 'geo_unknown_fail_open', 'user_message' => null];
            }

            $expected = strtoupper((string) $ngo->login_geo_country_code);
            if ($geo['country_code'] === $expected) {
                return ['allowed' => true, 'reason' => 'country_ok', 'user_message' => null];
            }

            return [
                'allowed' => false,
                'reason' => 'country_mismatch',
                'user_message' => 'Sign-in is restricted to connections from '.$expected.'.',
            ];
        }

        if ($policy === 'trusted_ip_only') {
            if (self::ipIsTrusted($ngo->id, $ip)) {
                return ['allowed' => true, 'reason' => 'trusted_ip', 'user_message' => null];
            }

            return [
                'allowed' => false,
                'reason' => 'not_trusted_ip',
                'user_message' => 'Sign-in is only allowed from approved office IP addresses.',
            ];
        }

        if ($policy === 'trusted_or_office_circle') {
            if (self::ipIsTrusted($ngo->id, $ip)) {
                return ['allowed' => true, 'reason' => 'trusted_ip', 'user_message' => null];
            }

            $lat = $ngo->office_geo_lat;
            $lng = $ngo->office_geo_lng;
            $radiusKm = $ngo->office_geo_radius_km;

            if ($lat === null || $lng === null || $radiusKm === null) {
                return $failClosed
                    ? ['allowed' => false, 'reason' => 'office_not_configured', 'user_message' => 'Office location is not configured. Ask your NGO admin.']
                    : ['allowed' => true, 'reason' => 'office_not_configured_fail_open', 'user_message' => null];
            }

            if (! $geo || $geo['approx_lat'] === null || $geo['approx_lng'] === null) {
                return $failClosed
                    ? ['allowed' => false, 'reason' => 'geo_unknown', 'user_message' => 'We could not approximate your location from this network.']
                    : ['allowed' => true, 'reason' => 'geo_unknown_fail_open', 'user_message' => null];
            }

            $km = Geo::haversineMeters(
                (float) $lat,
                (float) $lng,
                (float) $geo['approx_lat'],
                (float) $geo['approx_lng']
            ) / 1000;

            if ($km <= (float) $radiusKm) {
                return ['allowed' => true, 'reason' => 'within_office_radius', 'user_message' => null];
            }

            return [
                'allowed' => false,
                'reason' => 'outside_office_radius',
                'user_message' => 'Sign-in appears outside the allowed office area (IP-based). Use office network or get an IP allow-list entry.',
            ];
        }

        return ['allowed' => true, 'reason' => 'unknown_policy', 'user_message' => null];
    }

    private static function ipIsTrusted(int $ngoId, string $ip): bool
    {
        return NgoTrustedLoginIp::query()
            ->where('ngo_id', $ngoId)
            ->where('is_active', true)
            ->where('ip_address', $ip)
            ->exists();
    }
}
