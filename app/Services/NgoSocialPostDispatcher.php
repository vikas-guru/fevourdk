<?php

namespace App\Services;

use App\Jobs\ProcessNgoSocialPostJob;
use App\Models\FeedPost;
use App\Models\NGO;
use App\Models\NGOSocialPostJob;

class NgoSocialPostDispatcher
{
    public static function dispatchForFeedPost(FeedPost $post): void
    {
        if (! $post->ngo_id) {
            return;
        }

        $ngo = NGO::query()->with('socialChannels')->find($post->ngo_id);
        if (! $ngo) {
            return;
        }

        $text = trim($post->title."\n\n".$post->body);
        $payload = [
            'title' => $post->title,
            'body' => $post->body,
            'text' => $text,
            'feed_url' => url('/feeds/'.$post->id),
            'image_url' => self::absoluteImageUrl($post->primaryImagePath()),
        ];

        foreach ($ngo->socialChannels as $channel) {
            if (! $channel->auto_post_enabled) {
                continue;
            }
            if (! $channel->access_token) {
                continue;
            }
            if ($channel->platform === 'instagram' && empty($payload['image_url'])) {
                continue;
            }

            $job = NGOSocialPostJob::query()->create([
                'ngo_id' => $ngo->id,
                'source_type' => 'feed_post',
                'source_id' => $post->id,
                'platform' => $channel->platform,
                'payload' => $payload,
                'status' => 'queued',
            ]);

            ProcessNgoSocialPostJob::dispatch($job->id);
        }
    }

    private static function absoluteImageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return url($path);
    }
}
