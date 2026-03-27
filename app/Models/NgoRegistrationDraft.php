<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NgoRegistrationDraft extends Model
{
    use HasFactory;

    protected $fillable = [
        'draft_id',
        'payload',
        'ip_address',
        'user_agent',
        'last_saved_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'last_saved_at' => 'datetime',
    ];
}
