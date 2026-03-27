<?php

namespace App\Http\Controllers;

use App\Models\FeedComment;
use App\Models\FeedPost;
use App\Models\FeedReaction;
use App\Models\FeedShare;
use App\Models\NGO;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $this->ensureSeedPosts();

        $posts = FeedPost::query()
            ->with([
                'ngo:id,name,logo',
                'user:id,name',
                'comments.user:id,name',
                'reactions',
                'shares',
            ])
            ->where('is_published', true)
            ->latest()
            ->take(30)
            ->get()
            ->map(function (FeedPost $post) use ($user) {
                $reactions = $post->reactions->groupBy('type')->map->count();
                $userReaction = $post->reactions->firstWhere('user_id', $user->id);

                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'body' => $post->body,
                    'image_url' => $post->image_url,
                    'created_at' => $post->created_at?->toIso8601String(),
                    'ngo' => $post->ngo ? [
                        'id' => $post->ngo->id,
                        'name' => $post->ngo->name,
                        'logo' => $post->ngo->logo,
                    ] : null,
                    'author' => [
                        'id' => $post->user->id,
                        'name' => $post->user->name,
                    ],
                    'reactions' => [
                        'totals' => [
                            'like' => (int) ($reactions['like'] ?? 0),
                            'love' => (int) ($reactions['love'] ?? 0),
                            'support' => (int) ($reactions['support'] ?? 0),
                        ],
                        'my_reaction' => $userReaction?->type,
                    ],
                    'comments' => $post->comments->sortByDesc('id')->take(8)->values()->map(fn ($comment) => [
                        'id' => $comment->id,
                        'comment' => $comment->comment,
                        'user_name' => $comment->user?->name ?? 'Member',
                        'created_at' => $comment->created_at?->toIso8601String(),
                    ]),
                    'shares_count' => (int) $post->shares->count(),
                ];
            });

        $ngos = NGO::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('is_active', true)
            ->orderBy('name')
            ->take(200)
            ->get()
            ->map(fn (NGO $ngo) => [
                'id' => $ngo->id,
                'name' => $ngo->name,
                'logo' => $ngo->logo,
                'description' => $ngo->description,
                'latitude' => (float) $ngo->latitude,
                'longitude' => (float) $ngo->longitude,
                'focus_areas' => $ngo->focus_areas ?? [],
                'city' => $ngo->city_id,
                'state' => $ngo->state_id,
            ]);

        return Inertia::render('Feeds/Index', [
            'posts' => $posts,
            'ngos' => $ngos,
        ]);
    }

    public function react(Request $request, FeedPost $post)
    {
        $validated = $request->validate([
            'type' => 'required|in:like,love,support',
        ]);

        $user = $request->user();

        DB::transaction(function () use ($user, $post, $validated) {
            FeedReaction::query()->updateOrCreate(
                [
                    'feed_post_id' => $post->id,
                    'user_id' => $user->id,
                ],
                [
                    'type' => $validated['type'],
                ]
            );

            $user->increment('social_cause_points', 2);

            UserNotification::query()->create([
                'user_id' => $user->id,
                'type' => 'feed_reaction',
                'title' => 'Reaction recorded',
                'body' => 'You earned +2 social cause points for reacting to a feed post.',
                'target_url' => '/feeds',
            ]);

            if ($post->user_id !== $user->id) {
                UserNotification::query()->create([
                    'user_id' => $post->user_id,
                    'type' => 'feed_engagement',
                    'title' => 'Your post got a new reaction',
                    'body' => "{$user->name} reacted to your post: {$post->title}",
                    'target_url' => '/feeds',
                ]);
            }
        });

        return back();
    }

    public function comment(Request $request, FeedPost $post)
    {
        $validated = $request->validate([
            'comment' => 'required|string|min:2|max:500',
        ]);

        DB::transaction(function () use ($request, $post, $validated) {
            FeedComment::query()->create([
                'feed_post_id' => $post->id,
                'user_id' => $request->user()->id,
                'comment' => $validated['comment'],
            ]);

            $request->user()->increment('social_cause_points', 4);

            UserNotification::query()->create([
                'user_id' => $request->user()->id,
                'type' => 'feed_comment',
                'title' => 'Comment posted',
                'body' => 'You earned +4 social cause points for adding a comment.',
                'target_url' => '/feeds',
            ]);

            if ($post->user_id !== $request->user()->id) {
                UserNotification::query()->create([
                    'user_id' => $post->user_id,
                    'type' => 'feed_engagement',
                    'title' => 'New comment on your post',
                    'body' => "{$request->user()->name} commented: " . str($validated['comment'])->limit(70),
                    'target_url' => '/feeds',
                ]);
            }
        });

        return back();
    }

    public function share(Request $request, FeedPost $post)
    {
        $validated = $request->validate([
            'channel' => 'nullable|in:link,whatsapp,facebook,instagram',
        ]);

        DB::transaction(function () use ($request, $post, $validated) {
            FeedShare::query()->create([
                'feed_post_id' => $post->id,
                'user_id' => $request->user()->id,
                'channel' => $validated['channel'] ?? 'link',
            ]);

            $request->user()->increment('social_cause_points', 5);

            UserNotification::query()->create([
                'user_id' => $request->user()->id,
                'type' => 'feed_share',
                'title' => 'Post shared',
                'body' => 'You earned +5 social cause points for sharing impact content.',
                'target_url' => '/feeds',
            ]);

            if ($post->user_id !== $request->user()->id) {
                UserNotification::query()->create([
                    'user_id' => $post->user_id,
                    'type' => 'feed_engagement',
                    'title' => 'Your post was shared',
                    'body' => "{$request->user()->name} shared your post: {$post->title}",
                    'target_url' => '/feeds',
                ]);
            }
        });

        return back();
    }

    private function ensureSeedPosts(): void
    {
        if (FeedPost::query()->exists()) {
            return;
        }

        $ngo = NGO::query()->orderBy('id')->first();
        $authorId = $ngo?->users()->value('user_id');

        if (!$authorId) {
            $authorId = \App\Models\User::query()->value('id');
        }

        if (!$authorId) {
            return;
        }

        $seedPosts = [
            [
                'title' => 'School Kits Distribution Completed',
                'body' => 'Today we distributed 420 school kits in Raichur district. Thank you donors for making this milestone possible.',
                'image_url' => '/assets/images/fevourd-k/programs-image.jpg',
            ],
            [
                'title' => 'Community Health Camp Update',
                'body' => 'Our health team completed 310 preventive screenings. New campaign phase begins next week for rural outreach.',
                'image_url' => '/assets/images/fevourd-k/events-image.jpg',
            ],
            [
                'title' => 'Volunteer Story of the Week',
                'body' => 'A local youth group helped restore a learning center in 48 hours. We are sharing this to inspire more community-led action.',
                'image_url' => '/assets/images/fevourd-k/about-image.jpg',
            ],
        ];

        foreach ($seedPosts as $seedPost) {
            FeedPost::query()->create([
                'user_id' => $authorId,
                'ngo_id' => $ngo?->id,
                'title' => $seedPost['title'],
                'body' => $seedPost['body'],
                'image_url' => $seedPost['image_url'],
                'is_published' => true,
            ]);
        }
    }
}
