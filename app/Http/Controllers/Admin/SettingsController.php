<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function index(): Response
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get();
        $groupedSettings = $settings->groupBy('group');

        return Inertia::render('Admin/Settings/Index', [
            'settings' => $groupedSettings,
            'groups' => [
                'general' => 'General Settings',
                'theme' => 'Theme & Appearance',
                'branding' => 'Branding & Logo',
                'social' => 'Social Media',
                'contact' => 'Contact Information',
                'seo' => 'SEO & Meta',
                'features' => 'Feature Flags',
                'maintenance' => 'Maintenance Mode',
                'system' => 'System Configuration',
                'email' => 'Email Settings',
                'security' => 'Security Settings',
            ]
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable',
            'settings.*.type' => 'required|in:text,color,image,boolean,number,json',
            'settings.*.group' => 'required|string',
            'settings.*.description' => 'nullable|string',
            'settings.*.is_public' => 'required|boolean',
        ]);

        foreach ($validated['settings'] as $settingData) {
            Setting::updateOrCreate(
                ['key' => $settingData['key']],
                [
                    'value' => $settingData['value'] ?? '',
                    'type' => $settingData['type'],
                    'group' => $settingData['group'],
                    'description' => $settingData['description'] ?? '',
                    'is_public' => $settingData['is_public'],
                ]
            );
        }

        return back()->with('success', 'Settings updated successfully!');
    }

    public function resetTheme()
    {
        // Reset theme settings to defaults
        $defaultThemeSettings = [
            'primary_color' => '#2563eb',
            'secondary_color' => '#64748b',
            'accent_color' => '#10b981',
            'background_color' => '#ffffff',
            'text_color' => '#1f2937',
            'header_style' => 'modern',
            'footer_style' => 'comprehensive',
        ];

        foreach ($defaultThemeSettings as $key => $value) {
            Setting::setValue($key, $value, 'color', 'theme', true);
        }

        return back()->with('success', 'Theme reset to defaults!');
    }

    public function exportSettings()
    {
        $settings = Setting::all()->map(function ($setting) {
            return [
                'key' => $setting->key,
                'value' => $setting->value,
                'type' => $setting->type,
                'group' => $setting->group,
                'description' => $setting->description,
                'is_public' => $setting->is_public,
            ];
        });

        return response()->json($settings);
    }

    public function importSettings(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable',
            'settings.*.type' => 'required|in:text,color,image,boolean,number,json',
            'settings.*.group' => 'required|string',
            'settings.*.description' => 'nullable|string',
            'settings.*.is_public' => 'required|boolean',
        ]);

        foreach ($validated['settings'] as $settingData) {
            Setting::updateOrCreate(
                ['key' => $settingData['key']],
                [
                    'value' => $settingData['value'] ?? '',
                    'type' => $settingData['type'],
                    'group' => $settingData['group'],
                    'description' => $settingData['description'] ?? '',
                    'is_public' => $settingData['is_public'],
                ]
            );
        }

        return back()->with('success', 'Settings imported successfully!');
    }
}
