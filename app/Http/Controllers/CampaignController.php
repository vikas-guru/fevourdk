<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CampaignController extends Controller
{
    public function index()
    {
        // For public campaigns listing, we'll use sample data for now
        // In production, this would fetch from database
        return Inertia::render('Campaigns/Index', [
            'campaigns' => [], // This will be populated by the Vue component
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
