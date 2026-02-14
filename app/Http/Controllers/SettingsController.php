<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('roles');
        
        return Inertia::render('Settings/Index', [
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'notifications' => 'boolean',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'language' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:50',
        ]);

        $user->settings()->updateOrCreate([], $validated);

        return back()->with('success', 'Settings updated successfully!');
    }
}
