<?php

namespace App\Services;

use App\Models\State;
use App\Models\District;
use App\Models\City;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class LocationService
{
    /**
     * Get default location (Karnataka)
     */
    public static function getDefaultLocation(): array
    {
        $state = State::getDefault();
        
        return [
            'state_id' => $state->id,
            'state_name' => $state->name,
            'state_code' => $state->code,
            'is_default' => true
        ];
    }

    /**
     * Get location hierarchy for a given level
     */
    public static function getLocationHierarchy(string $level, ?int $parentId = null): Collection
    {
        $cacheKey = "location_hierarchy_{$level}_{$parentId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($level, $parentId) {
            switch ($level) {
                case 'states':
                    return State::getActive();
                case 'districts':
                    return District::active()->when($parentId, function ($query) use ($parentId) {
                        return $query->where('state_id', $parentId);
                    })->with('state')->get();
                case 'cities':
                    return City::active()->when($parentId, function ($query) use ($parentId) {
                        return $query->where('district_id', $parentId);
                    })->with(['district', 'state'])->get();
                default:
                    return collect([]);
            }
        });
    }

    /**
     * Get all states with their statistics
     */
    public static function getStatesWithStats(): Collection
    {
        return Cache::remember('states_with_stats', 3600, function () {
            return State::getActive()->map(function ($state) {
                return array_merge($state->toArray(), $state->getLocationStats());
            });
        });
    }

    /**
     * Get districts for a state with statistics
     */
    public static function getDistrictsWithStats(int $stateId): Collection
    {
        $cacheKey = "districts_with_stats_{$stateId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($stateId) {
            return District::active()->inState($stateId)->with('state')->get()->map(function ($district) {
                return array_merge($district->toArray(), $district->getLocationStats());
            });
        });
    }

    /**
     * Get cities for a district with statistics
     */
    public static function getCitiesWithStats(int $districtId): Collection
    {
        $cacheKey = "cities_with_stats_{$districtId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($districtId) {
            return City::active()->inDistrict($districtId)->with(['district', 'state'])->get()->map(function ($city) {
                return array_merge($city->toArray(), $city->getLocationStats());
            });
        });
    }

    /**
     * Get location context for content filtering
     */
    public static function getLocationContext(?array $location = null): array
    {
        if (!$location) {
            $location = self::getDefaultLocation();
        }

        $context = [
            'state_id' => $location['state_id'] ?? null,
            'district_id' => $location['district_id'] ?? null,
            'city_id' => $location['city_id'] ?? null,
            'state_name' => $location['state_name'] ?? null,
            'district_name' => $location['district_name'] ?? null,
            'city_name' => $location['city_name'] ?? null,
        ];

        // Get location details if IDs are provided
        if ($context['state_id']) {
            $state = State::find($context['state_id']);
            if ($state) {
                $context['state_name'] = $state->name;
                $context['state_code'] = $state->code;
            }
        }

        if ($context['district_id']) {
            $district = District::find($context['district_id']);
            if ($district) {
                $context['district_name'] = $district->name;
                $context['state_id'] = $district->state_id;
            }
        }

        if ($context['city_id']) {
            $city = City::find($context['city_id']);
            if ($city) {
                $context['city_name'] = $city->name;
                $context['district_id'] = $city->district_id;
                $context['state_id'] = $city->state_id;
            }
        }

        return $context;
    }

    /**
     * Get location-based content filters
     */
    public static function getLocationFilters(?array $location = null): array
    {
        $context = self::getLocationContext($location);
        
        return [
            'state_id' => $context['state_id'],
            'district_id' => $context['district_id'],
            'city_id' => $context['city_id'],
            'location_name' => self::getLocationDisplayName($context)
        ];
    }

    /**
     * Get display name for location
     */
    public static function getLocationDisplayName(array $context): string
    {
        if ($context['city_name'] && $context['district_name']) {
            return "{$context['city_name']}, {$context['district_name']}";
        }
        
        if ($context['district_name'] && $context['state_name']) {
            return "{$context['district_name']}, {$context['state_name']}";
        }
        
        if ($context['state_name']) {
            return $context['state_name'];
        }
        
        return 'All Locations';
    }

    /**
     * Validate location data
     */
    public static function validateLocation(array $location): array
    {
        $errors = [];

        if (isset($location['state_id']) && !State::where('id', $location['state_id'])->exists()) {
            $errors['state_id'] = 'Invalid state selected';
        }

        if (isset($location['district_id'])) {
            if (!District::where('id', $location['district_id'])->exists()) {
                $errors['district_id'] = 'Invalid district selected';
            } elseif (isset($location['state_id']) && 
                     !District::where('id', $location['district_id'])->where('state_id', $location['state_id'])->exists()) {
                $errors['district_id'] = 'District does not belong to selected state';
            }
        }

        if (isset($location['city_id'])) {
            if (!City::where('id', $location['city_id'])->exists()) {
                $errors['city_id'] = 'Invalid city selected';
            } elseif (isset($location['district_id']) && 
                     !City::where('id', $location['city_id'])->where('district_id', $location['district_id'])->exists()) {
                $errors['city_id'] = 'City does not belong to selected district';
            }
        }

        return $errors;
    }

    /**
     * Get location statistics for dashboard
     */
    public static function getLocationStats(?array $location = null): array
    {
        $context = self::getLocationContext($location);
        
        $stats = [
            'total_states' => State::getActive()->count(),
            'total_districts' => District::active()->count(),
            'total_cities' => City::active()->count(),
            'current_location' => self::getLocationDisplayName($context)
        ];

        if ($context['state_id']) {
            $state = State::find($context['state_id']);
            if ($state) {
                $stats['state'] = $state->getLocationStats();
            }
        }

        if ($context['district_id']) {
            $district = District::find($context['district_id']);
            if ($district) {
                $stats['district'] = $district->getLocationStats();
            }
        }

        if ($context['city_id']) {
            $city = City::find($context['city_id']);
            if ($city) {
                $stats['city'] = $city->getLocationStats();
            }
        }

        return $stats;
    }

    /**
     * Clear location cache
     */
    public static function clearCache(): void
    {
        $patterns = [
            'location_hierarchy_*',
            'states_with_stats',
            'districts_with_stats_*',
            'cities_with_stats_*'
        ];

        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
        }
    }
}
