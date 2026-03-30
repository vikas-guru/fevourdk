<?php

namespace App\Services;

use App\Models\FeedPost;
use Illuminate\Support\Str;

class FeedPostSeoService
{
    /**
     * @return array{meta_description: string, keywords: string, og_title: string, og_description: string, og_image: ?string, og_url: string, twitter_card: string}
     */
    public static function forPost(FeedPost $post, string $publicUrl): array
    {
        $plain = trim(preg_replace('/\s+/', ' ', strip_tags((string) $post->body)));
        $desc = Str::limit($plain !== '' ? $plain : $post->title, 158, '…');

        $ogImage = $post->primaryImageAbsoluteUrl();

        return [
            'meta_description' => $desc,
            'keywords' => self::keywordsFromText($post->title.' '.$plain),
            'og_title' => $post->title,
            'og_description' => $desc,
            'og_image' => $ogImage,
            'og_url' => $publicUrl,
            'twitter_card' => $ogImage ? 'summary_large_image' : 'summary',
        ];
    }

    private static function keywordsFromText(string $text): string
    {
        $text = strtolower(strip_tags($text));
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text) ?? '';
        $words = array_filter(str_word_count($text, 1), fn ($w) => strlen($w) > 3);
        $freq = array_count_values($words);
        arsort($freq);
        $top = array_slice(array_keys($freq), 0, 12);

        return implode(', ', array_unique($top)) ?: 'ngo, community, impact, fevourd-k';
    }
}
