<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return inertia('Programs', [
            'programs' => $programs,
        ]);
    }

    public function show(Program $program)
    {
        if (!$program->is_active) {
            abort(404);
        }

        return inertia('Programs/Show', [
            'program' => $program,
        ]);
    }
}
