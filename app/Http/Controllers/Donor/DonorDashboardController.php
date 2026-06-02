<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DonorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Donations link via donors.id (the donor profile), not users.id.
        $donor = $user->donor;
        $base = $donor
            ? Donation::where('donor_id', $donor->id)
            : Donation::whereRaw('1 = 0');

        // NGOs this donor follows/supports — the social-network side of the dashboard.
        $followedNgos = $user->followedNgos()
            ->wherePivot('is_following', true)
            ->get(['ngos.id', 'ngos.name', 'ngos.slug', 'ngos.logo', 'ngos.theme_color'])
            ->map(fn ($n) => [
                'id' => $n->id,
                'name' => $n->name,
                'slug' => $n->slug,
                'logo' => $n->logo,
                'theme_color' => $n->theme_color,
                'is_supporting' => (bool) $n->pivot->is_supporting,
            ]);

        return Inertia::render('Donor/Dashboard', [
            'user' => $user->load('roles'),
            'donations' => (clone $base)->with('campaign', 'ngo')->latest()->take(10)->get(),
            'followedNgos' => $followedNgos,
            'stats' => [
                'total_donated' => (clone $base)->where('status', 'completed')->sum('amount'),
                'campaigns_supported' => (clone $base)->distinct('campaign_id')->count('campaign_id'),
                'ngos_supported' => (clone $base)->distinct('ngo_id')->count('ngo_id'),
                'following_count' => $followedNgos->count(),
            ],
        ]);
    }
}
