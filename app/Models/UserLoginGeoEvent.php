<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLoginGeoEvent extends Model
{
    public $timestamps = false;

    protected $table = 'user_login_geo_events';

    protected $fillable = [
        'user_id',
        'ngo_id',
        'ip_address',
        'country_code',
        'region_name',
        'city',
        'approx_lat',
        'approx_lng',
        'was_blocked',
        'block_reason',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'was_blocked' => 'boolean',
            'approx_lat' => 'decimal:7',
            'approx_lng' => 'decimal:7',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class, 'ngo_id');
    }
}
