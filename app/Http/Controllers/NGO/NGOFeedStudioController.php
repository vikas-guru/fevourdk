<?php

namespace App\Http\Controllers\NGO;

use App\Http\Controllers\Controller;
use App\Models\FeedPost;
use App\Services\FeedPostSeoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class NGOFeedStudioController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ngo = $user?->ngo;

        if (! $user->hasAnyRole(['ngo_admin', 'ngo_staff'])) {
            return redirect()->route('dashboard')
                ->with('error', 'NGO not found or access denied.');
        }

        if (! $ngo) {
            return redirect()->route('welcome')
                ->with('error', 'NGO not found or access denied.');
        }

        $posts = FeedPost::query()
            ->where('ngo_id', $ngo->id)
            ->withCount([
                'reactions as cnt_like' => fn ($q) => $q->where('type', 'like'),
                'reactions as cnt_love' => fn ($q) => $q->where('type', 'love'),
                'reactions as cnt_support' => fn ($q) => $q->where('type', 'support'),
            ])
            ->withCount(['comments', 'shares'])
            ->latest()
            ->take(100)
            ->get()
            ->map(function (FeedPost $post) {
                $publicUrl = url('/feeds/'.$post->id);
                $seo = $post->meta['seo'] ?? FeedPostSeoService::forPost($post, $publicUrl);

                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'body_preview' => Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) $post->body))), 140),
                    'created_at' => $post->created_at?->toIso8601String(),
                    'views_count' => (int) ($post->views_count ?? 0),
                    'likes' => (int) $post->cnt_like,
                    'loves' => (int) $post->cnt_love,
                    'supports' => (int) $post->cnt_support,
                    'reactions_total' => (int) ($post->cnt_like + $post->cnt_love + $post->cnt_support),
                    'comments_count' => (int) $post->comments_count,
                    'shares_count' => (int) $post->shares_count,
                    'media_count' => count($post->resolvedMediaItems()),
                    'public_url' => url('/feeds/'.$post->id),
                    'seo_description' => $seo['meta_description'] ?? null,
                    'seo_keywords' => $seo['keywords'] ?? null,
                ];
            });

        $totals = [
            'posts' => $posts->count(),
            'views' => (int) $posts->sum('views_count'),
            'reactions' => (int) $posts->sum('reactions_total'),
            'comments' => (int) $posts->sum('comments_count'),
            'shares' => (int) $posts->sum('shares_count'),
        ];

        return Inertia::render('NGO/FeedStudio', [
            'ngo' => $ngo,
            'posts' => $posts,
            'totals' => $totals,
        ]);
    }
}
