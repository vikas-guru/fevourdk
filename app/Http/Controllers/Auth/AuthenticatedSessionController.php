<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserLoginGeoEvent;
use App\Services\IpGeoLookupService;
use App\Support\NgoLoginGeoEvaluator;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'seo' => Seo::page(
                'Sign in',
                'Log in to your FEVOURD-K account — donors, NGOs, CSR partners, and field teams.',
                '/login',
            ),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = trim($validated['login']);
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);

        // Check if user exists by email or phone
        $user = \App\Models\User::query()
            ->when($isEmail, fn ($query) => $query->where('email', $login))
            ->when(! $isEmail, fn ($query) => $query->where('phone', $login))
            ->first();

        if (! $user) {
            return back()->withErrors([
                'login' => 'No account found with this email or phone number.',
            ])->withInput($request->only('login'));
        }

        // Check if password is correct
        if (! \Illuminate\Support\Facades\Hash::check($validated['password'], $user->password)) {
            return back()->withErrors([
                'password' => 'The password you entered is incorrect.',
            ])->withInput($request->only('login'));
        }

        if (! $user->is_active) {
            return back()->withErrors([
                'login' => 'This account has been disabled. Contact support if you believe this is a mistake.',
            ])->withInput($request->only('login'));
        }

        $ip = (string) $request->ip();
        $geo = app(IpGeoLookupService::class)->lookup($ip);

        if ($user->ngo_id && ($user->hasRole('ngo_staff') || $user->hasRole('ngo_admin') || $user->hasRole('ngo_finance'))) {
            $ngo = $user->ngo;
            if ($ngo && ($ngo->login_geo_policy ?? 'log_only') !== 'none') {
                $eval = NgoLoginGeoEvaluator::evaluate($ngo, $geo, $ip);

                UserLoginGeoEvent::query()->create([
                    'user_id' => $user->id,
                    'ngo_id' => $ngo->id,
                    'ip_address' => $ip,
                    'country_code' => $geo['country_code'] ?? null,
                    'region_name' => $geo['region_name'] ?? null,
                    'city' => $geo['city'] ?? null,
                    'approx_lat' => isset($geo['approx_lat']) ? $geo['approx_lat'] : null,
                    'approx_lng' => isset($geo['approx_lng']) ? $geo['approx_lng'] : null,
                    'was_blocked' => ! $eval['allowed'],
                    'block_reason' => $eval['allowed'] ? null : ($eval['reason'] ?? 'blocked'),
                    'user_agent' => mb_substr((string) $request->userAgent(), 0, 512),
                    'created_at' => now(),
                ]);

                if (! $eval['allowed']) {
                    return back()->withErrors([
                        'login' => $eval['user_message'] ?? 'Sign-in is not allowed from this network.',
                    ])->withInput($request->only('login'));
                }
            }
        }

        // Attempt authentication
        if (Auth::attempt(['email' => $user->email, 'password' => $validated['password']])) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Debug: Log user roles
            \Log::info('User roles for '.$user->email.': '.$user->roles->pluck('name')->join(', '));

            // Redirect based on user role
            if ($user->hasRole('super_admin') || $user->hasRole('state_admin')) {
                \Log::info('Redirecting to admin dashboard');

                return redirect()->intended('/admin/dashboard');
            }

            if (
                $user->hasRole('ngo_finance')
                && ! $user->hasRole('ngo_admin')
                && ! $user->hasRole('ngo_staff')
            ) {
                return redirect()->intended('/ngo/finance');
            }

            \Log::info('Redirecting to user dashboard');

            return redirect()->intended('/dashboard');
        }

        // Fallback error (shouldn't reach here with above checks)
        return back()->withErrors([
            'login' => 'Invalid credentials. Please try again.',
        ])->withInput($request->only('login'));
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
