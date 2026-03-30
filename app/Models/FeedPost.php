<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeedPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ngo_id',
        'title',
        'body',
        'image_url',
        'media',
        'meta',
        'is_published',
        'views_count',
    ];

    protected $casts = [
        'meta' => 'array',
        'media' => 'array',
        'is_published' => 'boolean',
        'views_count' => 'integer',
    ];

    public function primaryImagePath(): ?string
    {
        foreach ($this->media ?? [] as $item) {
            if (($item['type'] ?? '') === 'image' && ! empty($item['path'])) {
                return $item['path'];
            }
        }

        return $this->image_url ?: null;
    }

    public function primaryImageAbsoluteUrl(): ?string
    {
        $path = $this->primaryImagePath();
        if (! $path) {
            return null;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return url($path);
    }

    /**
     * @return array<int, array{type: string, path: string}>
     */
    public function resolvedMediaItems(): array
    {
        $items = [];
        if (is_array($this->media) && $this->media !== []) {
            foreach ($this->media as $row) {
                if (! empty($row['path']) && ! empty($row['type'])) {
                    $items[] = [
                        'type' => $row['type'] === 'video' ? 'video' : 'image',
                        'path' => $row['path'],
                    ];
                }
            }
        }
        if ($items === [] && $this->image_url) {
            $items[] = ['type' => 'image', 'path' => $this->image_url];
        }

        return $items;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(FeedReaction::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(FeedComment::class);
    }

    public function shares(): HasMany
    {
        return $this->hasMany(FeedShare::class);
    }
}
