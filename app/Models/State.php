<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_active',
        'is_default',
        'country_code',
        'latitude',
        'longitude',
        'timezone',
        'population',
        'area_km2'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'latitude' => 'decimal:8,6',
        'longitude' => 'decimal:9,6',
        'population' => 'integer',
        'area_km2' => 'integer'
    ];

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
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
     * Get the default state (Karnataka)
     */
    public static function getDefault()
    {
        return static::where('is_default', true)->first() 
            ?? static::where('code', 'KA')->first()
            ?? static::first();
    }

    /**
     * Get all active states
     */
    public static function getActive()
    {
        return static::where('is_active', true)->orderBy('name')->get();
    }

    /**
     * Get location statistics
     */
    public function getLocationStats()
    {
        return [
            'districts_count' => $this->districts()->count(),
            'ngos_count' => $this->ngos()->where('is_verified', true)->count(),
            'active_campaigns' => $this->campaigns()->where('status', 'active')->count(),
            'total_population' => $this->population,
            'area_km2' => $this->area_km2
        ];
    }
}
