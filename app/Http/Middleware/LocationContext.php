<?php

namespace App\Http\Middleware;

use App\Services\LocationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LocationContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set default location if not already set
        if (!Session::has('location_context')) {
            $defaultLocation = LocationService::getDefaultLocation();
            Session::put('location_context', $defaultLocation);
        }

        // Handle location change requests
        if ($request->has('set_location')) {
            $locationData = $request->only(['state_id', 'district_id', 'city_id']);
            
            // Validate location data
            $errors = LocationService::validateLocation($locationData);
            
            if (empty($errors)) {
                $context = LocationService::getLocationContext($locationData);
                Session::put('location_context', $context);
                
                // Clear location cache to ensure fresh data
                LocationService::clearCache();
            }
        }

        // Share location context with all views
        $locationContext = Session::get('location_context', LocationService::getDefaultLocation());
        view()->share('location_context', $locationContext);
        view()->share('location_filters', LocationService::getLocationFilters($locationContext));
        view()->share('location_stats', LocationService::getLocationStats($locationContext));

        return $next($request);
    }
}
