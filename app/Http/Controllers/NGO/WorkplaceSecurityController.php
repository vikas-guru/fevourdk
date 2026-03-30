<?php

namespace App\Http\Controllers\NGO;

use App\Http\Controllers\Controller;
use App\Models\NgoTrustedLoginIp;
use App\Models\UserLoginGeoEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class WorkplaceSecurityController extends Controller
{
    private function ngo()
    {
        $ngo = Auth::user()?->ngo;
        if (! $ngo || ! Auth::user()->hasRole('ngo_admin')) {
            abort(403);
        }

        return $ngo;
    }

    public function index(): Response
    {
        $ngo = $this->ngo()->load('trustedLoginIps');

        $recent = UserLoginGeoEvent::query()
            ->where('ngo_id', $ngo->id)
            ->with('user:id,name,email')
            ->orderByDesc('created_at')
            ->limit(80)
            ->get()
            ->map(fn ($e) => [
                'id' => $e->id,
                'user' => $e->user,
                'ip_address' => $e->ip_address,
                'country_code' => $e->country_code,
                'region_name' => $e->region_name,
                'city' => $e->city,
                'was_blocked' => $e->was_blocked,
                'block_reason' => $e->block_reason,
                'created_at' => $e->created_at?->toIso8601String(),
            ]);

        return Inertia::render('NGO/Workplace/Security', [
            'ngo' => $ngo,
            'trustedIps' => $ngo->trustedLoginIps->where('is_active', true)->values(),
            'recentLogins' => $recent,
            'policyOptions' => [
                ['value' => 'none', 'label' => 'No checks'],
                ['value' => 'log_only', 'label' => 'Log only (default)'],
                ['value' => 'country_in', 'label' => 'Block if country mismatch'],
                ['value' => 'trusted_ip_only', 'label' => 'Trusted office IPs only'],
                ['value' => 'trusted_or_office_circle', 'label' => 'Trusted IP or IP-geo near office'],
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $ngo = $this->ngo();

        $validated = $request->validate([
            'login_geo_policy' => 'required|in:none,log_only,country_in,trusted_ip_only,trusted_or_office_circle',
            'login_geo_country_code' => 'required|string|size:2',
            'office_geo_lat' => 'nullable|numeric|between:-90,90',
            'office_geo_lng' => 'nullable|numeric|between:-180,180',
            'office_geo_radius_km' => 'nullable|numeric|min:1|max:500',
            'login_geo_fail_closed' => 'boolean',
        ]);

        $validated['login_geo_country_code'] = strtoupper($validated['login_geo_country_code']);
        $validated['login_geo_fail_closed'] = (bool) ($validated['login_geo_fail_closed'] ?? false);

        $ngo->update($validated);

        return back()->with('success', 'Workplace security settings saved.');
    }

    public function storeTrustedIp(Request $request): RedirectResponse
    {
        $ngo = $this->ngo();

        $validated = $request->validate([
            'ip_address' => 'required|ip',
            'label' => 'nullable|string|max:120',
        ]);

        NgoTrustedLoginIp::query()->create([
            'ngo_id' => $ngo->id,
            'ip_address' => $validated['ip_address'],
            'label' => $validated['label'] ?? null,
            'is_active' => true,
        ]);

        return back()->with('success', 'Trusted IP added.');
    }

    public function destroyTrustedIp(NgoTrustedLoginIp $trustedIp): RedirectResponse
    {
        $ngo = $this->ngo();
        if ((int) $trustedIp->ngo_id !== (int) $ngo->id) {
            abort(404);
        }
        $trustedIp->delete();

        return back()->with('success', 'Trusted IP removed.');
    }
}
