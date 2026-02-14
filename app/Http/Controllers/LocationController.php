<?php

namespace App\Http\Controllers;

use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;

class LocationController extends Controller
{
    /**
     * Get states with statistics
     */
    public function getStates(): JsonResponse
    {
        $states = LocationService::getStatesWithStats();
        
        return response()->json([
            'states' => $states,
            'default_location' => LocationService::getDefaultLocation()
        ]);
    }

    /**
     * Get districts for a state
     */
    public function getDistricts(Request $request): JsonResponse
    {
        $stateId = $request->input('state_id');
        
        if (!$stateId) {
            return response()->json(['error' => 'State ID is required'], 400);
        }

        $districts = LocationService::getDistrictsWithStats($stateId);
        
        return response()->json([
            'districts' => $districts
        ]);
    }

    /**
     * Get cities for a district
     */
    public function getCities(Request $request): JsonResponse
    {
        $districtId = $request->input('district_id');
        
        if (!$districtId) {
            return response()->json(['error' => 'District ID is required'], 400);
        }

        $cities = LocationService::getCitiesWithStats($districtId);
        
        return response()->json([
            'cities' => $cities
        ]);
    }

    /**
     * Set user's preferred location
     */
    public function setLocation(Request $request): JsonResponse
    {
        $locationData = $request->only(['state_id', 'district_id', 'city_id']);
        
        // Validate location data
        $errors = LocationService::validateLocation($locationData);
        
        if (!empty($errors)) {
            return response()->json([
                'error' => 'Invalid location data',
                'errors' => $errors
            ], 422);
        }

        // Store in session
        session(['location_context' => LocationService::getLocationContext($locationData)]);
        
        // Get updated context
        $context = session('location_context');
        
        return response()->json([
            'success' => true,
            'location' => $context,
            'display_name' => LocationService::getLocationDisplayName($context)
        ]);
    }

    /**
     * Get current location context
     */
    public function getCurrentLocation(): JsonResponse
    {
        $context = session('location_context', LocationService::getDefaultLocation());
        
        return response()->json([
            'location' => $context,
            'display_name' => LocationService::getLocationDisplayName($context),
            'stats' => LocationService::getLocationStats($context)
        ]);
    }

    /**
     * Get location statistics
     */
    public function getStats(Request $request): JsonResponse
    {
        $location = $request->only(['state_id', 'district_id', 'city_id']);
        $stats = LocationService::getLocationStats($location);
        
        return response()->json([
            'stats' => $stats
        ]);
    }

    /**
     * Search locations
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('query');
        $type = $request->input('type', 'all'); // all, states, districts, cities
        $limit = $request->input('limit', 20);

        if (!$query || strlen($query) < 2) {
            return response()->json(['error' => 'Query must be at least 2 characters'], 400);
        }

        $results = [];

        switch ($type) {
            case 'states':
            case 'all':
                $states = \App\Models\State::where('name', 'LIKE', "%{$query}%")
                    ->where('is_active', true)
                    ->limit($limit)
                    ->get(['id', 'name', 'code']);
                
                foreach ($states as $state) {
                    $results[] = [
                        'id' => $state->id,
                        'name' => $state->name,
                        'code' => $state->code,
                        'type' => 'state',
                        'display_name' => $state->name
                    ];
                }
                break;

            case 'districts':
            case 'all':
                $districts = \App\Models\District::where('name', 'LIKE', "%{$query}%")
                    ->where('is_active', true)
                    ->with('state:id,name,code')
                    ->limit($limit)
                    ->get(['id', 'name', 'state_id']);
                
                foreach ($districts as $district) {
                    $results[] = [
                        'id' => $district->id,
                        'name' => $district->name,
                        'state_id' => $district->state_id,
                        'state_name' => $district->state->name,
                        'state_code' => $district->state->code,
                        'type' => 'district',
                        'display_name' => "{$district->name}, {$district->state->code}"
                    ];
                }
                break;

            case 'cities':
            case 'all':
                $cities = \App\Models\City::where('name', 'LIKE', "%{$query}%")
                    ->where('is_active', true)
                    ->with(['district:id,name', 'state:id,name,code'])
                    ->limit($limit)
                    ->get(['id', 'name', 'district_id', 'state_id']);
                
                foreach ($cities as $city) {
                    $results[] = [
                        'id' => $city->id,
                        'name' => $city->name,
                        'district_id' => $city->district_id,
                        'district_name' => $city->district->name,
                        'state_id' => $city->state_id,
                        'state_name' => $city->state->name,
                        'state_code' => $city->state->code,
                        'type' => 'city',
                        'display_name' => "{$city->name}, {$city->state->code}"
                    ];
                }
                break;
        }

        return response()->json([
            'results' => $results,
            'query' => $query,
            'type' => $type
        ]);
    }

    /**
     * Get location hierarchy for navigation
     */
    public function getHierarchy(Request $request): JsonResponse
    {
        $level = $request->input('level', 'states');
        $parentId = $request->input('parent_id');
        
        $hierarchy = LocationService::getLocationHierarchy($level, $parentId);
        
        return response()->json([
            'level' => $level,
            'parent_id' => $parentId,
            'data' => $hierarchy
        ]);
    }

    /**
     * Reset location to default
     */
    public function resetLocation(): JsonResponse
    {
        $defaultLocation = LocationService::getDefaultLocation();
        session(['location_context' => $defaultLocation]);
        
        return response()->json([
            'success' => true,
            'location' => $defaultLocation,
            'display_name' => LocationService::getLocationDisplayName($defaultLocation)
        ]);
    }
}
