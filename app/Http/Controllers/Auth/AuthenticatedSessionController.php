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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if user exists
        $user = \App\Models\User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'No account found with this email address.',
            ])->withInput($request->only('email'));
        }

        // Check if password is correct
        if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'The password you entered is incorrect.',
            ])->withInput($request->only('email'));
        }

        // Attempt authentication
        if (Auth::attempt($credentials)) {
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
            'email' => 'Invalid credentials. Please try again.',
        ])->withInput($request->only('email'));
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
