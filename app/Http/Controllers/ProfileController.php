<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\FeedComment;
use App\Models\FeedReaction;
use App\Models\FeedShare;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user()->load('roles');
        $topMembers = User::query()
            ->select('id', 'name', 'email', 'phone', 'gender', 'city_name', 'district_name', 'state_name', 'social_cause_points')
            ->orderByDesc('social_cause_points')
            ->take(10)
            ->get();

        if (!$topMembers->contains('id', $user->id)) {
            $topMembers->push($user);
        }

        $leaderboard = $topMembers->map(function (User $member) {
            $effectivePoints = $this->calculateEffectiveSocialCausePoints($member);
            return [
                'id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
                'phone' => $member->phone,
                'gender' => $member->gender,
                'city_name' => $member->city_name,
                'district_name' => $member->district_name,
                'state_name' => $member->state_name,
                'social_cause_points' => (int) $effectivePoints,
            ];
        })->unique('id')->sortByDesc('social_cause_points')->values();
        
        return Inertia::render('Profile/Edit', [
            'user' => $user,
            'leaderboard' => $leaderboard,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    private function calculateEffectiveSocialCausePoints(User $member): int
    {
        $rolesCount = $member->roles()->count();
        $profileFields = [
            $member->name,
            $member->email,
            $member->phone,
            $member->address,
            $member->state_name,
            $member->district_name,
            $member->city_name,
            $member->avatar,
        ];
        $filledFields = collect($profileFields)->filter(fn ($value) => !empty($value))->count();
        $profileCompletionPercent = (int) round(($filledFields / max(count($profileFields), 1)) * 100);

        $donor = Donor::query()->where('user_id', $member->id)->first();
        $donationCount = (int) ($donor?->donation_count ?? 0);
        $totalDonated = (float) ($donor?->total_donated ?? 0);
        $reactionCount = FeedReaction::query()->where('user_id', $member->id)->count();
        $commentCount = FeedComment::query()->where('user_id', $member->id)->count();
        $shareCount = FeedShare::query()->where('user_id', $member->id)->count();

        return (int) $member->social_cause_points
            + 100
            + ($rolesCount * 40)
            + ((int) floor($profileCompletionPercent * 1.8))
            + ($donationCount * 30)
            + (int) min(floor($totalDonated / 100), 700)
            + ($reactionCount * 2)
            + ($commentCount * 4)
            + ($shareCount * 5);
    }
}
