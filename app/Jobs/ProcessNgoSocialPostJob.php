<?php

namespace App\Jobs;

use App\Models\NGOSocialChannel;
use App\Models\NGOSocialPostJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessNgoSocialPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $socialPostJobId
    ) {}

    public function handle(): void
    {
        $job = NGOSocialPostJob::query()->find($this->socialPostJobId);
        if (! $job || $job->status !== 'queued') {
            return;
        }

        $channel = NGOSocialChannel::query()
            ->where('ngo_id', $job->ngo_id)
            ->where('platform', $job->platform)
            ->first();

        if (! $channel || ! $channel->access_token) {
            $this->markFailed($job, 'No access token for this platform.');

            return;
        }

        try {
            $token = Crypt::decryptString($channel->access_token);
        } catch (\Throwable $e) {
            $this->markFailed($job, 'Invalid stored token. Re-save your access token on Social connect.');

            return;
        }

        $payload = is_array($job->payload) ? $job->payload : [];
        $message = (string) ($payload['text'] ?? '');
        $link = (string) ($payload['feed_url'] ?? url('/feeds'));
        $imageUrl = $payload['image_url'] ?? null;

        try {
            match ($job->platform) {
                'facebook' => $this->postFacebook($job, $channel, $token, $message, $link),
                'instagram' => $this->postInstagram($job, $channel, $token, $message, $imageUrl),
                'linkedin' => $this->postLinkedIn($job, $channel, $token, $message, $link),
                'google_business' => throw new \RuntimeException('Google Business auto-post is not wired to the API yet. Disable auto-post or remove this channel.'),
                default => throw new \RuntimeException('Unknown platform.'),
            };
        } catch (\Throwable $e) {
            Log::warning('NGO social post failed', [
                'job_id' => $job->id,
                'platform' => $job->platform,
                'error' => $e->getMessage(),
            ]);
            $this->markFailed($job, $e->getMessage());

            return;
        }

        $job->update([
            'status' => 'sent',
            'sent_at' => now(),
            'error_message' => null,
        ]);
    }

    private function postFacebook(NGOSocialPostJob $job, NGOSocialChannel $channel, string $token, string $message, string $link): void
    {
        $pageId = $channel->meta['page_id'] ?? null;
        if (! is_string($pageId) || $pageId === '') {
            throw new \RuntimeException('Facebook Page ID missing. Add it under Social connect.');
        }

        $response = Http::timeout(45)
            ->asForm()
            ->post("https://graph.facebook.com/v19.0/{$pageId}/feed", [
                'message' => $message,
                'link' => $link,
                'access_token' => $token,
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException($response->json('error.message') ?? $response->body());
        }
    }

    private function postInstagram(NGOSocialPostJob $job, NGOSocialChannel $channel, string $token, string $message, ?string $imageUrl): void
    {
        $igId = $channel->meta['instagram_account_id'] ?? null;
        if (! is_string($igId) || $igId === '') {
            throw new \RuntimeException('Instagram Business account ID missing. Add it under Social connect.');
        }
        if (! is_string($imageUrl) || $imageUrl === '') {
            throw new \RuntimeException('Instagram requires a public image URL. Add an image to your feed post.');
        }

        $create = Http::timeout(60)->post("https://graph.facebook.com/v19.0/{$igId}/media", [
            'image_url' => $imageUrl,
            'caption' => $message,
            'access_token' => $token,
        ]);

        if (! $create->successful()) {
            throw new \RuntimeException($create->json('error.message') ?? $create->body());
        }

        $creationId = $create->json('id');
        if (! is_string($creationId) && ! is_numeric($creationId)) {
            throw new \RuntimeException('Instagram media creation returned no id.');
        }

        $publish = Http::timeout(60)->post("https://graph.facebook.com/v19.0/{$igId}/media_publish", [
            'creation_id' => (string) $creationId,
            'access_token' => $token,
        ]);

        if (! $publish->successful()) {
            throw new \RuntimeException($publish->json('error.message') ?? $publish->body());
        }
    }

    private function postLinkedIn(NGOSocialPostJob $job, NGOSocialChannel $channel, string $token, string $message, string $link): void
    {
        $orgId = $channel->meta['linkedin_organization_id'] ?? null;
        if (! is_string($orgId) && ! is_numeric($orgId)) {
            throw new \RuntimeException('LinkedIn organization ID missing. Add it under Social connect.');
        }

        $author = 'urn:li:organization:'.preg_replace('/\D/', '', (string) $orgId);

        $body = [
            'author' => $author,
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => [
                        'text' => $message."\n\n".$link,
                    ],
                    'shareMediaCategory' => 'NONE',
                ],
            ],
            'visibility' => [
                'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
            ],
        ];

        $response = Http::timeout(45)
            ->withToken($token)
            ->withHeaders([
                'X-Restli-Protocol-Version' => '2.0.0',
                'Content-Type' => 'application/json',
            ])
            ->post('https://api.linkedin.com/v2/ugcPosts', $body);

        if (! $response->successful()) {
            throw new \RuntimeException($response->json('message') ?? $response->body());
        }
    }

    private function markFailed(NGOSocialPostJob $job, string $message): void
    {
        $job->update([
            'status' => 'failed',
            'error_message' => mb_substr($message, 0, 2000),
        ]);
    }
}
