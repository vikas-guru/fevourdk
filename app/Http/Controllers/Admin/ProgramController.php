<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        
        return Inertia::render('Admin/Programs/Index', [
            'programs' => $programs,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Programs/Create', [
            'categories' => [
                'Capacity Building',
                'Women Empowerment',
                'Education & Child Welfare',
                'Health & Well-being',
                'Environmental Sustainability',
                'Rural Development',
                'Disaster Management',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:programs,slug',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('programs', 'public');
            $validated['image'] = $imagePath;
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        Program::create($validated);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program created successfully.');
    }

    public function show(Program $program)
    {
        return Inertia::render('Admin/Programs/Show', [
            'program' => $program,
        ]);
    }

    public function edit(Program $program)
    {
        return Inertia::render('Admin/Programs/Edit', [
            'program' => $program,
            'categories' => [
                'Capacity Building',
                'Women Empowerment',
                'Education & Child Welfare',
                'Health & Well-being',
                'Environmental Sustainability',
                'Rural Development',
                'Disaster Management',
            ],
        ]);
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:programs,slug,' . $program->id,
            'description' => 'required|string',
            'content' => 'nullable|string',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($program->image) {
                Storage::disk('public')->delete($program->image);
            }
            
            $imagePath = $request->file('image')->store('programs', 'public');
            $validated['image'] = $imagePath;
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $program->update($validated);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program updated successfully.');
    }

    public function destroy(Program $program)
    {
        // Delete image if exists
        if ($program->image) {
            Storage::disk('public')->delete($program->image);
        }

        $program->delete();

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program deleted successfully.');
    }
}
