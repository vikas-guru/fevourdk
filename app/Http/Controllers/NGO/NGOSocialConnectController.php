<?php

namespace App\Http\Controllers\NGO;

use App\Http\Controllers\Controller;
use App\Models\NGOSocialChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;

class NGOSocialConnectController extends Controller
{
    private const PLATFORMS = ['facebook', 'instagram', 'linkedin', 'google_business'];

    public function index()
    {
        $ngo = $this->resolveNgo();
        if (! $ngo) {
            return redirect()->route('welcome')
                ->with('error', 'NGO not found or access denied.');
        }

        $rows = NGOSocialChannel::query()
            ->where('ngo_id', $ngo->id)
            ->get()
            ->keyBy('platform');

        $channels = [];
        foreach (self::PLATFORMS as $platform) {
            $row = $rows->get($platform);
            $channels[$platform] = $this->serializePublic($row, $platform);
        }

        return Inertia::render('NGO/SocialConnect', [
            'ngo' => $ngo,
            'channels' => $channels,
            'help' => [
                'meta' => 'https://developers.facebook.com/docs/pages-api/posts',
                'instagram' => 'https://developers.facebook.com/docs/instagram-api/guides/content-publishing',
                'linkedin' => 'https://learn.microsoft.com/en-us/linkedin/marketing/integrations/community-management/shares/ugc-post-api',
            ],
        ]);
    }

    public function update(Request $request, string $platform)
    {
        abort_unless(in_array($platform, self::PLATFORMS, true), 404);

        $ngo = $this->resolveNgo();
        if (! $ngo) {
            return redirect()->route('welcome')
                ->with('error', 'NGO not found or access denied.');
        }

        $validated = $request->validate([
            'account_handle' => 'nullable|string|max:255',
            'access_token' => 'nullable|string|max:8000',
            'auto_post_enabled' => 'nullable|boolean',
            'page_id' => 'nullable|string|max:100',
            'instagram_account_id' => 'nullable|string|max:100',
            'linkedin_organization_id' => 'nullable|string|max:100',
        ]);

        $existing = NGOSocialChannel::query()
            ->where('ngo_id', $ngo->id)
            ->where('platform', $platform)
            ->first();

        $meta = array_merge($existing?->meta ?? [], array_filter([
            'page_id' => $validated['page_id'] ?? null,
            'instagram_account_id' => $validated['instagram_account_id'] ?? null,
            'linkedin_organization_id' => $validated['linkedin_organization_id'] ?? null,
        ], fn ($v) => $v !== null && $v !== ''));

        $data = [
            'account_handle' => $validated['account_handle'] ?? null,
            'auto_post_enabled' => (bool) ($validated['auto_post_enabled'] ?? false),
            'meta' => $meta,
        ];

        if (! empty($validated['access_token'])) {
            $data['access_token'] = Crypt::encryptString(trim($validated['access_token']));
        }

        NGOSocialChannel::query()->updateOrCreate(
            [
                'ngo_id' => $ngo->id,
                'platform' => $platform,
            ],
            $data
        );

        return back()->with('success', ucfirst($platform).' settings saved. When auto-share is on, new updates from Post an update will be copied there too.');
    }

    public function destroy(string $platform)
    {
        abort_unless(in_array($platform, self::PLATFORMS, true), 404);

        $ngo = $this->resolveNgo();
        if (! $ngo) {
            return redirect()->route('welcome')
                ->with('error', 'NGO not found or access denied.');
        }

        NGOSocialChannel::query()
            ->where('ngo_id', $ngo->id)
            ->where('platform', $platform)
            ->delete();

        return back()->with('success', 'Disconnected '.ucfirst($platform).'.');
    }

    private function resolveNgo()
    {
        $user = Auth::user();
        if (! $user || ! $user->ngo_id) {
            return null;
        }
        if (! $user->hasAnyRole(['ngo_admin', 'ngo_staff'])) {
            return null;
        }

        return $user->ngo;
    }

    private function serializePublic(?NGOSocialChannel $row, string $platform): array
    {
        $labels = [
            'facebook' => 'Facebook Page',
            'instagram' => 'Instagram Business',
            'linkedin' => 'LinkedIn (organization)',
            'google_business' => 'Google Business Profile',
        ];

        if (! $row) {
            return [
                'platform' => $platform,
                'label' => $labels[$platform] ?? $platform,
                'connected' => false,
                'auto_post_enabled' => false,
                'account_handle' => '',
                'page_id' => '',
                'instagram_account_id' => '',
                'linkedin_organization_id' => '',
            ];
        }

        $meta = $row->meta ?? [];

        return [
            'platform' => $platform,
            'label' => $labels[$platform] ?? $platform,
            'connected' => (bool) $row->access_token,
            'auto_post_enabled' => (bool) $row->auto_post_enabled,
            'account_handle' => (string) ($row->account_handle ?? ''),
            'page_id' => (string) ($meta['page_id'] ?? ''),
            'instagram_account_id' => (string) ($meta['instagram_account_id'] ?? ''),
            'linkedin_organization_id' => (string) ($meta['linkedin_organization_id'] ?? ''),
        ];
    }
}
