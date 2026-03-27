<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login');
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
            ->when(!$isEmail, fn ($query) => $query->where('phone', $login))
            ->first();
        
        if (!$user) {
            return back()->withErrors([
                'login' => 'No account found with this email or phone number.',
            ])->withInput($request->only('login'));
        }

        // Check if password is correct
        if (!\Illuminate\Support\Facades\Hash::check($validated['password'], $user->password)) {
            return back()->withErrors([
                'password' => 'The password you entered is incorrect.',
            ])->withInput($request->only('login'));
        }

        // Attempt authentication
        if (Auth::attempt(['email' => $user->email, 'password' => $validated['password']])) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Debug: Log user roles
            \Log::info('User roles for ' . $user->email . ': ' . $user->roles->pluck('name')->join(', '));
            
            // Redirect based on user role
            if ($user->hasRole('super_admin') || $user->hasRole('state_admin')) {
                \Log::info('Redirecting to admin dashboard');
                return redirect()->intended('/admin/dashboard');
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
