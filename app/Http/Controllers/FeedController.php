<?php

namespace App\Http\Controllers;

use App\Models\FeedComment;
use App\Models\FeedPost;
use App\Models\FeedReaction;
use App\Models\FeedShare;
use App\Models\NGO;
use App\Models\NGOSocialChannel;
use App\Models\UserNotification;
use App\Services\FeedPostSeoService;
use App\Services\NgoSocialPostDispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $user?->loadMissing('ngo');

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
            ->take(60)
            ->get()
            ->map(fn (FeedPost $post) => $this->serializeFeedPost($post, $user, 8));

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

        $feedMeta = [
            'can_post_as_ngo' => false,
            'ngo_name' => null,
            'social_connected_platforms' => [],
            'social_connect_url' => route('ngo.social-connect'),
        ];

        if ($user && $user->ngo_id && $user->hasAnyRole(['ngo_admin', 'ngo_staff'])) {
            $feedMeta['can_post_as_ngo'] = true;
            $feedMeta['ngo_name'] = $user->ngo?->name;
            $feedMeta['social_connected_platforms'] = NGOSocialChannel::query()
                ->where('ngo_id', $user->ngo_id)
                ->whereNotNull('access_token')
                ->where('auto_post_enabled', true)
                ->pluck('platform')
                ->values()
                ->all();
        }

        return Inertia::render('Feeds/Index', [
            'posts' => $posts,
            'ngos' => $ngos,
            'feedMeta' => $feedMeta,
        ]);
    }

    public function show(Request $request, FeedPost $post)
    {
        abort_unless($post->is_published, 404);

        $post->load([
            'ngo:id,name,logo',
            'user:id,name',
            'comments.user:id,name',
            'reactions',
            'shares',
        ]);

        $user = $request->user();
        $publicUrl = url('/feeds/'.$post->id);
        $seo = $post->meta['seo'] ?? FeedPostSeoService::forPost($post, $publicUrl);

        return Inertia::render('Feeds/Show', [
            'post' => $this->serializeFeedPost($post, $user, 80),
            'seo' => $seo,
        ]);
    }

    public function recordView(Request $request, FeedPost $post): JsonResponse
    {
        abort_unless($post->is_published, 404);

        $sessionKey = 'feed_post_viewed_'.$post->id;
        if ($request->session()->has($sessionKey)) {
            return response()->json([
                'recorded' => false,
                'views_count' => (int) $post->views_count,
            ]);
        }

        $request->session()->put($sessionKey, true);
        $post->increment('views_count');

        return response()->json([
            'recorded' => true,
            'views_count' => (int) $post->fresh()->views_count,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        abort_unless(
            $user && $user->ngo_id && $user->hasAnyRole(['ngo_admin', 'ngo_staff']),
            403,
            'Only NGO workspace members can publish to the live feed.'
        );

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'body' => 'required|string|max:8000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'media_files' => 'nullable|array|max:12',
            'media_files.*' => 'file|max:51200',
            'from_ngo_workspace' => 'nullable|boolean',
        ]);

        $files = $request->file('media_files', []);
        if ($files !== null && ! is_array($files)) {
            $files = [$files];
        }
        $files = array_values(array_filter($files ?: []));
        if ($request->hasFile('image')) {
            $files[] = $request->file('image');
        }

        if (count($files) > 12) {
            throw ValidationException::withMessages([
                'media_files' => ['You can attach up to 12 photos or videos per post.'],
            ]);
        }

        $mediaItems = [];
        foreach ($files as $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }
            $mime = (string) $file->getMimeType();
            if (str_starts_with($mime, 'image/')) {
                if (! preg_match('#^image/(jpeg|jpg|png|webp)$#', $mime)) {
                    throw ValidationException::withMessages(['media_files' => ['Use JPG, PNG, or WebP images.']]);
                }
                if ($file->getSize() > 5 * 1024 * 1024) {
                    throw ValidationException::withMessages(['media_files' => ['Each image must be 5 MB or smaller.']]);
                }
                $mediaItems[] = [
                    'type' => 'image',
                    'path' => '/storage/'.$file->store('feed_media', 'public'),
                ];
            } elseif (str_starts_with($mime, 'video/')) {
                $allowed = ['video/mp4', 'video/webm', 'video/quicktime'];
                if (! in_array($mime, $allowed, true)) {
                    throw ValidationException::withMessages(['media_files' => ['Videos must be MP4, WebM, or MOV.']]);
                }
                if ($file->getSize() > 50 * 1024 * 1024) {
                    throw ValidationException::withMessages(['media_files' => ['Each video must be 50 MB or smaller.']]);
                }
                $mediaItems[] = [
                    'type' => 'video',
                    'path' => '/storage/'.$file->store('feed_media', 'public'),
                ];
            } else {
                throw ValidationException::withMessages(['media_files' => ['Unsupported file type.']]);
            }
        }

        $firstImage = null;
        foreach ($mediaItems as $m) {
            if ($m['type'] === 'image') {
                $firstImage = $m['path'];
                break;
            }
        }

        $post = FeedPost::query()->create([
            'user_id' => $user->id,
            'ngo_id' => $user->ngo_id,
            'title' => $validated['title'],
            'body' => $validated['body'],
            'image_url' => $firstImage,
            'media' => $mediaItems === [] ? null : $mediaItems,
            'is_published' => true,
            'views_count' => 0,
        ]);

        $publicUrl = url('/feeds/'.$post->id);
        $post->update([
            'meta' => array_merge($post->meta ?? [], [
                'seo' => FeedPostSeoService::forPost($post->fresh(), $publicUrl),
            ]),
        ]);

        NgoSocialPostDispatcher::dispatchForFeedPost($post->fresh());

        $message = 'Your update is live on the community feed. Search-friendly tags were generated automatically.';
        if ($request->boolean('from_ngo_workspace')) {
            return redirect()->route('ngo.post-update')->with('success', $message.' If you turned on auto-share, we also queued Facebook / Instagram / LinkedIn.');
        }

        return redirect()->route('feeds.index')->with('success', $message.' Cross-post jobs were queued for any connected social channels.');
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
                    'body' => "{$request->user()->name} commented: ".str($validated['comment'])->limit(70),
                    'target_url' => '/feeds',
                ]);
            }
        });

        return back();
    }

    public function share(Request $request, FeedPost $post)
    {
        $validated = $request->validate([
            'channel' => 'nullable|in:link,whatsapp,facebook,instagram,linkedin',
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

    private function serializeFeedPost(FeedPost $post, ?\App\Models\User $user, int $commentLimit = 8): array
    {
        $reactions = $post->reactions->groupBy('type')->map->count();
        $userReaction = $user ? $post->reactions->firstWhere('user_id', $user->id) : null;

        return [
            'id' => $post->id,
            'title' => $post->title,
            'body' => $post->body,
            'image_url' => $post->image_url,
            'media' => $post->resolvedMediaItems(),
            'views_count' => (int) ($post->views_count ?? 0),
            'created_at' => $post->created_at?->toIso8601String(),
            'public_url' => url('/feeds/'.$post->id),
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
            'comments' => $post->comments->sortByDesc('id')->take($commentLimit)->values()->map(fn ($comment) => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'user_name' => $comment->user?->name ?? 'Member',
                'created_at' => $comment->created_at?->toIso8601String(),
            ]),
            'shares_count' => (int) $post->shares->count(),
        ];
    }

    private function ensureSeedPosts(): void
    {
        if (FeedPost::query()->exists()) {
            return;
        }

        $ngo = NGO::query()->orderBy('id')->first();
        $authorId = $ngo?->users()->value('user_id');

        if (! $authorId) {
            $authorId = \App\Models\User::query()->value('id');
        }

        if (! $authorId) {
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
