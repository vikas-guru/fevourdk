<?php

namespace App\Http\Controllers;

use App\Models\NGO;
use App\Support\Seo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NGOController extends Controller
{
    public function index(Request $request)
    {
        if ($request->is('admin/*')) {
            $ngos = NGO::query()
                ->with(['state', 'district', 'city'])
                ->withCount('documents')
                ->select([
                    'id',
                    'name',
                    'slug',
                    'email',
                    'phone',
                    'verification_status',
                    'is_active',
                    'state_id',
                    'district_id',
                    'city_id',
                    'created_at',
                ])
                ->latest()
                ->paginate(15);

            return Inertia::render('Admin/NGOs/Index', [
                'ngos' => $ngos,
            ]);
        }

        $ngos = NGO::with(['state', 'district', 'city'])
            ->where('verification_status', 'verified')
            ->where('is_active', true)
            ->select('id', 'name', 'slug', 'description', 'logo', 'focus_areas', 'state_id', 'district_id', 'city_id', 'verification_status', 'created_at')
            ->withCount([
                'supporters as followers_count' => fn ($q) => $q->where('is_following', true),
                'supporters as supporters_count' => fn ($q) => $q->where('is_supporting', true),
            ])
            ->latest()
            ->paginate(12);

        // Social graph: which of these NGOs the current user already follows/supports.
        $followState = [];
        if ($user = $request->user()) {
            $followState = \App\Models\NgoSupporter::where('user_id', $user->id)
                ->get(['ngo_id', 'is_following', 'is_supporting'])
                ->mapWithKeys(fn ($r) => [$r->ngo_id => [
                    'following' => (bool) $r->is_following,
                    'supporting' => (bool) $r->is_supporting,
                ]])->toArray();
        }

        return Inertia::render('NGOs/Index', [
            'ngos' => $ngos,
            'followState' => $followState,
            'authed' => (bool) $request->user(),
            'seo' => Seo::page(
                'Verified NGOs',
                'Browse verified voluntary organisations across Karnataka — mission, focus areas, and contact on FEVOURD-K.',
                '/ngos',
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/NGOs/Create');
    }

    public function store(Request $request)
    {
        // Implementation for NGO creation
        return redirect()->route('admin.ngos.index')
            ->with('success', 'NGO created successfully.');
    }

    public function show(Request $request, NGO $ngo)
    {
        $ngo->load(['state', 'district', 'city']);

        // Admin review screen — only for the /admin/* route.
        if ($request->is('admin/*')) {
            return Inertia::render('Admin/NGOs/Show', [
                'ngo' => $ngo->load('documents'),
            ]);
        }

        // Public-facing NGO profile.
        $ngo->loadCount([
            'supporters as followers_count' => fn ($q) => $q->where('is_following', true),
            'supporters as supporters_count' => fn ($q) => $q->where('is_supporting', true),
        ]);

        $follow = ['following' => false, 'supporting' => false];
        if ($user = $request->user()) {
            $row = \App\Models\NgoSupporter::where('user_id', $user->id)
                ->where('ngo_id', $ngo->id)
                ->first(['is_following', 'is_supporting']);
            if ($row) {
                $follow = ['following' => (bool) $row->is_following, 'supporting' => (bool) $row->is_supporting];
            }
        }

        // Campaigns — the heart of the impact section.
        $campaigns = \App\Models\Campaign::where('ngo_id', $ngo->id)
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderByDesc('raised_amount')
            ->limit(4)
            ->get(['id', 'title', 'slug', 'description', 'featured_image', 'target_amount', 'raised_amount', 'donor_count', 'end_date', 'focus_areas']);

        // Headline impact aggregates (campaign totals read as the org's lifetime reach).
        $impact = [
            'total_raised' => (float) $campaigns->sum('raised_amount'),
            'total_target' => (float) $campaigns->sum('target_amount'),
            'total_donors' => (int) $campaigns->sum('donor_count'),
            'active_campaigns' => $campaigns->count(),
        ];

        $campaignsPayload = $campaigns->map(fn ($c) => [
            'id' => $c->id,
            'title' => $c->title,
            'slug' => $c->slug,
            'featured_image' => $c->featured_image,
            'target_amount' => (float) $c->target_amount,
            'raised_amount' => (float) $c->raised_amount,
            'donor_count' => (int) $c->donor_count,
            'progress' => round($c->progress_percentage),
            'days_left' => (int) $c->days_left,
            'focus_areas' => $c->focus_areas ?? [],
        ]);

        // Recent completed donations — a live "supporters" ticker (privacy-safe).
        $recentDonations = \App\Models\Donation::where('ngo_id', $ngo->id)
            ->where('status', 'completed')
            ->with('donor.user:id,name')
            ->latest('donated_at')
            ->limit(5)
            ->get()
            ->map(function ($d) {
                $name = optional(optional($d->donor)->user)->name;
                // Show only the first name for privacy; never reveal anonymous donors.
                $first = $name ? trim(explode(' ', $name)[0]) : null;
                return [
                    'amount' => (float) $d->amount,
                    'name' => $d->is_anonymous ? 'Anonymous' : ($first ?: 'A supporter'),
                    'message' => $d->message,
                ];
            });

        // Latest published updates from the NGO's feed.
        $updates = \App\Models\FeedPost::where('ngo_id', $ngo->id)
            ->where('is_published', true)
            ->latest('created_at')
            ->limit(3)
            ->get(['id', 'title', 'body', 'image_url', 'views_count', 'created_at'])
            ->map(fn ($p) => [
                'title' => $p->title,
                'body' => \Illuminate\Support\Str::limit(strip_tags((string) $p->body), 110),
                'image' => $p->image_url,
                'views' => (int) $p->views_count,
                'ago' => optional($p->created_at)->diffForHumans(),
            ]);

        return Inertia::render('NGOs/Show', [
            'campaigns' => $campaignsPayload,
            'impact' => $impact,
            'recentDonations' => $recentDonations,
            'updates' => $updates,
            'ngo' => [
                'id' => $ngo->id,
                'name' => $ngo->name,
                'slug' => $ngo->slug,
                'description' => $ngo->description,
                'logo' => $ngo->logo,
                'focus_areas' => $ngo->focus_areas ?? [],
                'email' => $ngo->email,
                'phone' => $ngo->phone,
                'website' => $ngo->website ?: $ngo->website_url,
                'address' => $ngo->address,
                'registration_number' => $ngo->registration_number,
                'founder_name' => $ngo->founder_name,
                'cofounder_name' => $ngo->cofounder_name,
                'facebook_url' => $ngo->facebook_url,
                'instagram_url' => $ngo->instagram_url,
                'theme_color' => $ngo->theme_color,
                'verification_status' => $ngo->verification_status,
                'latitude' => $ngo->latitude,
                'longitude' => $ngo->longitude,
                'created_at' => $ngo->created_at,
                'city' => $ngo->city?->only(['id', 'name']),
                'district' => $ngo->district?->only(['id', 'name']),
                'state' => $ngo->state?->only(['id', 'name', 'code']),
                'short_location' => $ngo->short_location,
                'full_location' => $ngo->full_location,
                'followers_count' => $ngo->followers_count,
                'supporters_count' => $ngo->supporters_count,
            ],
            'follow' => $follow,
            'authed' => (bool) $request->user(),
            'seo' => Seo::page(
                $ngo->name,
                \Illuminate\Support\Str::limit(strip_tags((string) $ngo->description), 150) ?: 'Verified voluntary organisation on FEVOURD-K.',
                '/ngos/'.$ngo->slug,
            ),
        ]);
    }

    public function edit(NGO $ngo)
    {
        return Inertia::render('Admin/NGOs/Edit', [
            'ngo' => $ngo,
        ]);
    }

    public function update(Request $request, NGO $ngo)
    {
        // Implementation for NGO update
        return redirect()->route('admin.ngos.index')
            ->with('success', 'NGO updated successfully.');
    }

    public function destroy(NGO $ngo)
    {
        $ngo->delete();

        return redirect()->route('admin.ngos.index')
            ->with('success', 'NGO deleted successfully.');
    }

    public function pending()
    {
        $pendingNgos = NGO::where('verification_status', 'pending')
            ->select('id', 'name', 'email', 'created_at')
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/NGOs/Pending', [
            'ngos' => $pendingNgos,
        ]);
    }

    public function verify(NGO $ngo)
    {
        $ngo->update([
            'verification_status' => 'verified',
            'is_active' => true,
            'verified_at' => now(),
        ]);

        return redirect()->route('admin.ngos.index')
            ->with('success', 'NGO verified successfully.');
    }

    public function reject(Request $request, NGO $ngo)
    {
        $ngo->update([
            'verification_status' => 'suspended',
            'is_active' => false,
        ]);

        return redirect()->route('admin.ngos.index')
            ->with('success', 'NGO marked as rejected/suspended.');
    }
}
