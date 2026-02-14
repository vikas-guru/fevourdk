<?php

namespace App\Http\Controllers;

use App\Models\NGO;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NGOController extends Controller
{
    public function index()
    {
        $ngos = NGO::select('id', 'name', 'verification_status', 'created_at')
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/NGOs/Index', [
            'ngos' => $ngos,
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
            'ngo' => $ngo,
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
        $ngo->update(['verification_status' => 'verified']);
        return redirect()->route('admin.ngos.pending')
            ->with('success', 'NGO verified successfully.');
    }
}
