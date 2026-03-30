<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NgoFieldTrackPoint extends Model
{
    public $timestamps = false;

    protected $table = 'ngo_field_track_points';

    protected $fillable = [
        'field_session_id',
        'recorded_at',
        'latitude',
        'longitude',
        'accuracy_m',
        'speed_ms',
        'heading',
        'altitude_m',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'accuracy_m' => 'decimal:2',
            'speed_ms' => 'decimal:3',
            'heading' => 'decimal:2',
            'altitude_m' => 'decimal:2',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(NgoFieldSession::class, 'field_session_id');
    }
}
