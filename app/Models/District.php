<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'state_id',
        'code',
        'is_active',
        'latitude',
        'longitude',
        'population',
        'area_km2',
        'headquarters',
        'literacy_rate',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8,6',
        'longitude' => 'decimal:9,6',
        'population' => 'integer',
        'area_km2' => 'integer',
        'literacy_rate' => 'decimal:5,2'
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function ngos(): HasMany
    {
        return $this->hasMany(NGO::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get full location name
     */
    public function getFullLocationAttribute(): string
    {
        return "{$this->name}, {$this->state->name}";
    }

    /**
     * Get location statistics
     */
    public function getLocationStats()
    {
        return [
            'cities_count' => $this->cities()->count(),
            'ngos_count' => $this->ngos()->where('verification_status', 'verified')->count(),
            'active_campaigns' => \App\Models\Campaign::whereHas('ngo', fn ($q) => $q->where('district_id', $this->id))->where('status', 'active')->count(),
            'total_population' => $this->population,
            'literacy_rate' => $this->literacy_rate
        ];
    }

    /**
     * Scope for active districts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for districts in a state
     */
    public function scopeInState($query, $stateId)
    {
        return $query->where('state_id', $stateId);
    }
}
