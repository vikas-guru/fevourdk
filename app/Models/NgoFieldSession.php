<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NgoFieldSession extends Model
{
    protected $table = 'ngo_field_sessions';

    protected $fillable = [
        'ngo_id',
        'user_id',
        'field_task_id',
        'status',
        'started_at',
        'ended_at',
        'distance_meters',
        'max_speed_ms',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'distance_meters' => 'decimal:2',
            'max_speed_ms' => 'decimal:3',
        ];
    }

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class, 'ngo_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(NgoFieldTask::class, 'field_task_id');
    }

    public function trackPoints(): HasMany
    {
        return $this->hasMany(NgoFieldTrackPoint::class, 'field_session_id')->orderBy('recorded_at');
    }
}
