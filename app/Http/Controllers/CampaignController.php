<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::query()
            ->with('ngo:id,name,logo')
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Campaign $campaign) {
                return [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'slug' => $campaign->slug,
                    'description' => $campaign->description,
                    'featured_image' => $campaign->featured_image,
                    'category' => data_get($campaign->focus_areas, '0', 'General'),
                    'goal_amount' => (float) $campaign->target_amount,
                    'raised_amount' => (float) $campaign->raised_amount,
                    'progress_percentage' => round($campaign->progress_percentage, 2),
                    'donors_count' => (int) ($campaign->donor_count ?? 0),
                    'days_left' => $campaign->days_left,
                    'ngo_name' => $campaign->ngo?->name,
                    'created_at' => $campaign->created_at?->toDateString(),
                ];
            });

        return Inertia::render('Campaigns/Index', [
            'campaigns' => $campaigns,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Campaigns/Create');
    }

    public function store(Request $request)
    {
        // Implementation for campaign creation
        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign created successfully.');
    }

    public function show(Campaign $campaign)
    {
        return Inertia::render('Admin/Campaigns/Show', [
            'campaign' => $campaign,
        ]);
    }

    public function edit(Campaign $campaign)
    {
        return Inertia::render('Admin/Campaigns/Edit', [
            'campaign' => $campaign,
        ]);
    }

    public function update(Request $request, Campaign $campaign)
    {
        // Implementation for campaign update
        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign updated successfully.');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign deleted successfully.');
    }
}
