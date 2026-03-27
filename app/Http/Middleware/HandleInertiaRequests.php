<?php

namespace App\Http\Middleware;

use App\Models\Donor;
use App\Models\FeedComment;
use App\Models\FeedReaction;
use App\Models\FeedShare;
use App\Models\User;
use App\Models\UserNotification;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Inertia\Response;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        $otpService = app(OtpService::class);
        $user = $request->user();

        $roles = $user ? $user->roles->pluck('name')->values() : collect();
        $permissions = $user ? $user->getAllPermissions()->pluck('name')->values() : collect();

        $profileAndLoyalty = null;
        if ($user) {
            $profileFields = [
                $user->name,
                $user->email,
                $user->phone,
                $user->address,
                $user->state_name,
                $user->district_name,
                $user->city_name,
                $user->avatar,
            ];
            $filledFields = collect($profileFields)->filter(fn ($value) => !empty($value))->count();
            $profileCompletionPercent = (int) round(($filledFields / max(count($profileFields), 1)) * 100);

            $donor = Donor::query()->where('user_id', $user->id)->first();
            $donationCount = (int) ($donor?->donation_count ?? 0);
            $totalDonated = (float) ($donor?->total_donated ?? 0);
            $reactionCount = FeedReaction::query()->where('user_id', $user->id)->count();
            $commentCount = FeedComment::query()->where('user_id', $user->id)->count();
            $shareCount = FeedShare::query()->where('user_id', $user->id)->count();

            $points = (int) $user->social_cause_points
                + 100
                + ((int) $roles->count() * 40)
                + ((int) floor($profileCompletionPercent * 1.8))
                + ($donationCount * 30)
                + (int) min(floor($totalDonated / 100), 700)
                + ($reactionCount * 2)
                + ($commentCount * 4)
                + ($shareCount * 5);

            $nextMilestone = ($points < 500) ? 500 : (($points < 1000) ? 1000 : (($points < 2000) ? 2000 : 3500));
            $level = ($points < 500) ? 'Starter' : (($points < 1000) ? 'Impact Builder' : (($points < 2000) ? 'Community Leader' : 'Changemaker'));
            $leaderboardRank = User::query()
                ->whereRaw('(COALESCE(social_cause_points, 0)) > ?', [$user->social_cause_points ?? 0])
                ->count() + 1;

            $profileAndLoyalty = [
                'completion_percent' => $profileCompletionPercent,
                'social_cause_points' => $points,
                'level' => $level,
                'next_milestone' => $nextMilestone,
                'next_milestone_gap' => max($nextMilestone - $points, 0),
                'donation_count' => $donationCount,
                'total_donated' => round($totalDonated, 2),
                'reactions_count' => $reactionCount,
                'comments_count' => $commentCount,
                'shares_count' => $shareCount,
                'leaderboard_rank' => $leaderboardRank,
            ];
        }

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user,
                'roles' => $roles,
                'permissions' => $permissions,
                'profile' => $profileAndLoyalty,
                'notifications' => $user
                    ? UserNotification::query()
                        ->where('user_id', $user->id)
                        ->latest()
                        ->take(12)
                        ->get()
                        ->map(fn (UserNotification $notification) => [
                            'id' => $notification->id,
                            'type' => $notification->type,
                            'title' => $notification->title,
                            'body' => $notification->body,
                            'target_url' => $notification->target_url,
                            'read_at' => optional($notification->read_at)?->toIso8601String(),
                            'created_at' => optional($notification->created_at)?->toIso8601String(),
                        ])
                    : [],
                'unread_notifications_count' => $user
                    ? UserNotification::query()->where('user_id', $user->id)->whereNull('read_at')->count()
                    : 0,
            ],
            'otp' => [
                'pilot_mode' => $otpService->isPilotMode(),
                'code_length' => $otpService->otpCodeLength(),
            ],
            'ziggy' => [
                'location' => $request->url(),
            ],
        ]);
    }
}
