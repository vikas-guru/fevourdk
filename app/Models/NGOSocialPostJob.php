<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NGOSocialPostJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'ngo_id',
        'source_type',
        'source_id',
        'platform',
        'payload',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'sent_at' => 'datetime',
    ];

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class, 'ngo_id');
    }
}
