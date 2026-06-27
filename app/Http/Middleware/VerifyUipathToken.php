<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards the read-only ImpactOps Maestro agent API with a bearer token
 * (config: services.uipath.token). Sandbox/demo by default.
 */
class VerifyUipathToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('services.uipath.token');
        $provided = (string) $request->bearerToken();

        if ($expected === '' || ! hash_equals($expected, $provided)) {
            return response()->json(['error' => 'unauthorized'], 401);
        }

        return $next($request);
    }
}
