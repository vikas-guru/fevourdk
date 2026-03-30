<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NgoFieldTask;
use App\Models\NGO;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NgoFieldTaskController extends Controller
{
    public function index(Request $request): Response
    {
        $ngoId = $request->integer('ngo_id') ?: null;

        $query = NgoFieldTask::query()
            ->with(['ngo:id,name,slug', 'assignee:id,name,email', 'createdBy:id,name', 'assignedBy:id,name'])
            ->orderByDesc('created_at');

        if ($ngoId) {
            $query->where('ngo_id', $ngoId);
        }

        $tasks = $query->paginate(25)->withQueryString();

        $ngos = NGO::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return Inertia::render('Admin/FieldTasks/Index', [
            'tasks' => $tasks,
            'ngos' => $ngos,
            'filters' => ['ngo_id' => $ngoId],
        ]);
    }

    public function create(): Response
    {
        $ngos = NGO::query()->orderBy('name')->get(['id', 'name', 'slug']);

        return Inertia::render('Admin/FieldTasks/Create', [
            'ngos' => $ngos,
        ]);
    }

    public function staffForNgo(NGO $ngo): JsonResponse
    {
        $users = User::query()
            ->where('ngo_id', $ngo->id)
            ->where('is_active', true)
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['ngo_admin', 'ngo_staff']);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return response()->json(['users' => $users]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ngo_id' => 'required|exists:ngos,id',
            'title' => 'required|string|max:200',
            'description' => 'nullable|string|max:5000',
            'assignee_id' => 'nullable|exists:users,id',
            'priority' => 'nullable|in:low,normal,high',
            'due_at' => 'nullable|date',
            'task_type' => 'nullable|string|max:50',
        ]);

        if (! empty($validated['assignee_id'])) {
            $assignee = User::query()->find($validated['assignee_id']);
            if (! $assignee || (int) $assignee->ngo_id !== (int) $validated['ngo_id']) {
                return back()->withErrors(['assignee_id' => 'User must belong to the selected NGO.'])->withInput();
            }
        }

        NgoFieldTask::query()->create([
            'ngo_id' => $validated['ngo_id'],
            'created_by_user_id' => $request->user()->id,
            'assigned_by_user_id' => $request->user()->id,
            'assignee_id' => $validated['assignee_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'] ?? 'normal',
            'due_at' => $validated['due_at'] ?? null,
            'task_type' => $validated['task_type'] ?? 'field_visit',
            'status' => ($validated['assignee_id'] ?? null) ? 'assigned' : 'open',
            'assigned_at' => ($validated['assignee_id'] ?? null) ? now() : null,
        ]);

        return redirect()->route('admin.field-tasks.index')
            ->with('success', 'Field task assigned to NGO.');
    }
}
