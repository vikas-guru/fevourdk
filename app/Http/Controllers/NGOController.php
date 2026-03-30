<?php

namespace App\Http\Controllers;

use App\Models\NGO;
use App\Support\Seo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NGOController extends Controller
{
    public function index(Request $request)
    {
        if ($request->is('admin/*')) {
            $ngos = NGO::query()
                ->with(['state', 'district', 'city'])
                ->withCount('documents')
                ->select([
                    'id',
                    'name',
                    'slug',
                    'email',
                    'phone',
                    'verification_status',
                    'is_active',
                    'state_id',
                    'district_id',
                    'city_id',
                    'created_at',
                ])
                ->latest()
                ->paginate(15);

            return Inertia::render('Admin/NGOs/Index', [
                'ngos' => $ngos,
            ]);
        }

        $ngos = NGO::with(['state', 'district', 'city'])
            ->where('verification_status', 'verified')
            ->where('is_active', true)
            ->select('id', 'name', 'slug', 'description', 'logo', 'focus_areas', 'state_id', 'district_id', 'city_id', 'verification_status', 'created_at')
            ->latest()
            ->paginate(12);

        return Inertia::render('NGOs/Index', [
            'ngos' => $ngos,
            'seo' => Seo::page(
                'Verified NGOs',
                'Browse verified voluntary organisations across Karnataka — mission, focus areas, and contact on FEVOURD-K.',
                '/ngos',
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/NGOs/Create');
    }

    public function store(Request $request)
    {
        // Implementation for NGO creation
        return redirect()->route('admin.ngos.index')
            ->with('success', 'NGO created successfully.');
    }

    public function show(NGO $ngo)
    {
        return Inertia::render('Admin/NGOs/Show', [
            'ngo' => $ngo->load(['state', 'district', 'city', 'documents']),
        ]);
    }

    public function edit(NGO $ngo)
    {
        return Inertia::render('Admin/NGOs/Edit', [
            'ngo' => $ngo,
        ]);
    }

    public function update(Request $request, NGO $ngo)
    {
        // Implementation for NGO update
        return redirect()->route('admin.ngos.index')
            ->with('success', 'NGO updated successfully.');
    }

    public function destroy(NGO $ngo)
    {
        $ngo->delete();

        return redirect()->route('admin.ngos.index')
            ->with('success', 'NGO deleted successfully.');
    }

    public function pending()
    {
        $pendingNgos = NGO::where('verification_status', 'pending')
            ->select('id', 'name', 'email', 'created_at')
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/NGOs/Pending', [
            'ngos' => $pendingNgos,
        ]);
    }

    public function verify(NGO $ngo)
    {
        $ngo->update([
            'verification_status' => 'verified',
            'is_active' => true,
            'verified_at' => now(),
        ]);

        return redirect()->route('admin.ngos.index')
            ->with('success', 'NGO verified successfully.');
    }

    public function reject(Request $request, NGO $ngo)
    {
        $ngo->update([
            'verification_status' => 'suspended',
            'is_active' => false,
        ]);

        return redirect()->route('admin.ngos.index')
            ->with('success', 'NGO marked as rejected/suspended.');
    }
}
