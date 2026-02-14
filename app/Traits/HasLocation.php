<?php

namespace App\Traits;

use App\Models\State;
use App\Models\District;
use App\Models\City;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasLocation
{
    /**
     * Get the state that owns the model.
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get the district that owns the model.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the city that owns the model.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get full location information
     */
    public function getLocationInfo(): array
    {
        $location = [
            'state_id' => $this->state_id,
            'district_id' => $this->district_id,
            'city_id' => $this->city_id,
        ];

        if ($this->state) {
            $location['state_name'] = $this->state->name;
            $location['state_code'] = $this->state->code;
        }

        if ($this->district) {
            $location['district_name'] = $this->district->name;
        }

        if ($this->city) {
            $location['city_name'] = $this->city->name;
            $location['city_type'] = $this->city->type;
        }

        return $location;
    }

    /**
     * Get full location display name
     */
    public function getFullLocationAttribute(): ?string
    {
        $parts = [];

        if ($this->city && $this->city->name) {
            $parts[] = $this->city->name;
        }

        if ($this->district && $this->district->name) {
            $parts[] = $this->district->name;
        }

        if ($this->state && $this->state->name) {
            $parts[] = $this->state->name;
        }

        return empty($parts) ? null : implode(', ', $parts);
    }

    /**
     * Get short location display name
     */
    public function getShortLocationAttribute(): ?string
    {
        if ($this->city && $this->city->name) {
            return "{$this->city->name}, {$this->state?->code}";
        }

        if ($this->district && $this->district->name) {
            return "{$this->district->name}, {$this->state?->code}";
        }

        return $this->state?->name;
    }

    /**
     * Scope for models in a specific state
     */
    public function scopeInState($query, $stateId)
    {
        return $query->where('state_id', $stateId);
    }

    /**
     * Scope for models in a specific district
     */
    public function scopeInDistrict($query, $districtId)
    {
        return $query->where('district_id', $districtId);
    }

    /**
     * Scope for models in a specific city
     */
    public function scopeInCity($query, $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    /**
     * Scope for models in location hierarchy
     */
    public function scopeInLocation($query, array $location)
    {
        if (!empty($location['state_id'])) {
            $query->where('state_id', $location['state_id']);
        }

        if (!empty($location['district_id'])) {
            $query->where('district_id', $location['district_id']);
        }

        if (!empty($location['city_id'])) {
            $query->where('city_id', $location['city_id']);
        }

        return $query;
    }

    /**
     * Scope for models near a location (within same district)
     */
    public function scopeNearLocation($query, array $location)
    {
        if (!empty($location['district_id'])) {
            return $query->where('district_id', $location['district_id']);
        }

        if (!empty($location['state_id'])) {
            return $query->where('state_id', $location['state_id']);
        }

        return $query;
    }

    /**
     * Set location for the model
     */
    public function setLocation(array $location): self
    {
        $this->state_id = $location['state_id'] ?? null;
        $this->district_id = $location['district_id'] ?? null;
        $this->city_id = $location['city_id'] ?? null;
        
        return $this;
    }

    /**
     * Check if model is in the same location as another model
     */
    public function isInSameLocationAs($model): bool
    {
        return $this->state_id === $model->state_id &&
               $this->district_id === $model->district_id &&
               $this->city_id === $model->city_id;
    }

    /**
     * Check if model is in the same state as another model
     */
    public function isInSameStateAs($model): bool
    {
        return $this->state_id === $model->state_id;
    }

    /**
     * Check if model is in the same district as another model
     */
    public function isInSameDistrictAs($model): bool
    {
        return $this->district_id === $model->district_id;
    }

    /**
     * Get location level (state, district, or city)
     */
    public function getLocationLevel(): string
    {
        if ($this->city_id) {
            return 'city';
        }

        if ($this->district_id) {
            return 'district';
        }

        if ($this->state_id) {
            return 'state';
        }

        return 'none';
    }

    /**
     * Get location coordinates
     */
    public function getLocationCoordinates(): array
    {
        $coordinates = [
            'latitude' => null,
            'longitude' => null
        ];

        if ($this->city && $this->city->latitude && $this->city->longitude) {
            $coordinates['latitude'] = $this->city->latitude;
            $coordinates['longitude'] = $this->city->longitude;
        } elseif ($this->district && $this->district->latitude && $this->district->longitude) {
            $coordinates['latitude'] = $this->district->latitude;
            $coordinates['longitude'] = $this->district->longitude;
        } elseif ($this->state && $this->state->latitude && $this->state->longitude) {
            $coordinates['latitude'] = $this->state->latitude;
            $coordinates['longitude'] = $this->state->longitude;
        }

        return $coordinates;
    }
}
