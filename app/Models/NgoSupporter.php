<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NgoSupporter extends Model
{
    protected $table = 'ngo_supporters';

    protected $fillable = [
        'ngo_id',
        'user_id',
        'is_following',
        'is_supporting',
        'followed_at',
        'supported_at',
    ];

    protected $casts = [
        'is_following' => 'boolean',
        'is_supporting' => 'boolean',
        'followed_at' => 'datetime',
        'supported_at' => 'datetime',
    ];

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class, 'ngo_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
