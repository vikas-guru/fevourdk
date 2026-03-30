<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Support\Seo;
use Illuminate\Support\Str;

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
            'seo' => Seo::page(
                'Programs — FEVOURD-K',
                'FEVOURD-K programmes and thematic initiatives supporting voluntary organisations and communities across Karnataka.',
                '/programs',
            ),
        ]);
    }

    public function show(Program $program)
    {
        if (! $program->is_active) {
            abort(404);
        }

        $path = '/programs/'.($program->slug ?: $program->id);
        $desc = trim(strip_tags((string) ($program->description ?? '')));

        return inertia('Programs/Show', [
            'program' => $program,
            'seo' => Seo::page(
                $program->title.' — Program — FEVOURD-K',
                $desc !== '' ? Str::limit($desc, 158, '…') : 'FEVOURD-K programme details and impact.',
                $path,
            ),
        ]);
    }
}
