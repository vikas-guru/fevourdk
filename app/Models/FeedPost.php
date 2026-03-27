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
        'meta',
        'is_published',
    ];

    protected $casts = [
        'meta' => 'array',
        'is_published' => 'boolean',
    ];

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
