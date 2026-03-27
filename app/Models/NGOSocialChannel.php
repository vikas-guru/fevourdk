<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NGOSocialChannel extends Model
{
    use HasFactory;

    protected $fillable = [
        'ngo_id',
        'platform',
        'account_handle',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'auto_post_enabled',
        'meta',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'auto_post_enabled' => 'boolean',
        'meta' => 'array',
    ];

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class, 'ngo_id');
    }
}
