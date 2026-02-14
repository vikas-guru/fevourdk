<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'district_id',
        'state_id',
        'code',
        'is_active',
        'latitude',
        'longitude',
        'population',
        'area_km2',
        'pincode',
        'type', // city, town, village
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8,6',
        'longitude' => 'decimal:9,6',
        'population' => 'integer',
        'area_km2' => 'integer'
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
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
        return "{$this->name}, {$this->district->name}, {$this->state->name}";
    }

    /**
     * Get short location name
     */
    public function getShortLocationAttribute(): string
    {
        return "{$this->name}, {$this->state->code}";
    }

    /**
     * Get location statistics
     */
    public function getLocationStats()
    {
        return [
            'ngos_count' => $this->ngos()->where('is_verified', true)->count(),
            'active_campaigns' => $this->campaigns()->where('status', 'active')->count(),
            'total_population' => $this->population,
            'type' => $this->type
        ];
    }

    /**
     * Scope for active cities
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for cities in a district
     */
    public function scopeInDistrict($query, $districtId)
    {
        return $query->where('district_id', $districtId);
    }

    /**
     * Scope for cities in a state
     */
    public function scopeInState($query, $stateId)
    {
        return $query->where('state_id', $stateId);
    }

    /**
     * Scope for cities by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get state through district relationship
     */
    public function getStateThroughDistrict()
    {
        return $this->district->state;
    }
}
