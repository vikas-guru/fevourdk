<?php

namespace App\Http\Controllers\NGO;

use App\Http\Controllers\Controller;
use App\Models\NgoFieldSession;
use App\Models\NgoFieldTask;
use App\Models\NgoFieldTrackPoint;
use App\Models\NGO;
use App\Models\User;
use App\Support\Geo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class FieldOperationsController extends Controller
{
    private function ngoOrAbort(): NGO
    {
        $ngo = Auth::user()?->ngo;
        if (! $ngo) {
            abort(403, 'NGO not found.');
        }

        return $ngo;
    }

    private function isNgoAdmin(): bool
    {
        return Auth::user()?->hasRole('ngo_admin') ?? false;
    }

    public function hub(): Response
    {
        $ngo = $this->ngoOrAbort();

        $tasksQuery = NgoFieldTask::query()->where('ngo_id', $ngo->id)->with(['assignee:id,name,email', 'createdBy:id,name']);
        if (! $this->isNgoAdmin()) {
            $tasksQuery->where(function ($q) {
                $q->where('assignee_id', Auth::id())->orWhereNull('assignee_id');
            });
        }
        $tasks = $tasksQuery->orderByDesc('created_at')->limit(100)->get();

        $activeSessions = NgoFieldSession::query()
            ->where('ngo_id', $ngo->id)
            ->where('status', 'active')
            ->with(['user:id,name,email', 'task:id,title'])
            ->get()
            ->map(function (NgoFieldSession $s) {
                $last = NgoFieldTrackPoint::query()
                    ->where('field_session_id', $s->id)
                    ->orderByDesc('recorded_at')
                    ->first();

                return [
                    'id' => $s->id,
                    'started_at' => $s->started_at?->toIso8601String(),
                    'user' => $s->user,
                    'task' => $s->task,
                    'last_point' => $last ? [
                        'lat' => (float) $last->latitude,
                        'lng' => (float) $last->longitude,
                        'recorded_at' => $last->recorded_at?->toIso8601String(),
                    ] : null,
                ];
            });

        $staffUsers = [];
        if ($this->isNgoAdmin()) {
            $staffUsers = User::query()
                ->where('ngo_id', $ngo->id)
                ->where('is_active', true)
                ->whereHas('roles', function ($q) {
                    $q->whereIn('name', ['ngo_admin', 'ngo_staff']);
                })
                ->orderBy('name')
                ->get(['id', 'name', 'email']);
        }

        return Inertia::render('NGO/Field/Hub', [
            'ngo' => $ngo,
            'tasks' => $tasks,
            'activeSessions' => $activeSessions,
            'isNgoAdmin' => $this->isNgoAdmin(),
            'staffUsers' => $staffUsers,
        ]);
    }

    public function app(): Response
    {
        $ngo = $this->ngoOrAbort();

        $myTasks = NgoFieldTask::query()
            ->where('ngo_id', $ngo->id)
            ->whereIn('status', ['assigned', 'open', 'in_progress'])
            ->where(function ($q) {
                $q->where('assignee_id', Auth::id())->orWhereNull('assignee_id');
            })
            ->orderByRaw("CASE priority WHEN 'high' THEN 0 WHEN 'normal' THEN 1 ELSE 2 END")
            ->orderBy('due_at')
            ->get(['id', 'title', 'status', 'priority', 'due_at', 'assignee_id']);

        $activeSession = NgoFieldSession::query()
            ->where('ngo_id', $ngo->id)
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->with('task:id,title')
            ->first();

        return Inertia::render('NGO/Field/App', [
            'ngo' => $ngo,
            'myTasks' => $myTasks,
            'activeSession' => $activeSession ? [
                'id' => $activeSession->id,
                'started_at' => $activeSession->started_at?->toIso8601String(),
                'field_task_id' => $activeSession->field_task_id,
                'task' => $activeSession->task,
            ] : null,
        ]);
    }

    public function storeTask(Request $request): RedirectResponse
    {
        $ngo = $this->ngoOrAbort();
        if (! $this->isNgoAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string|max:5000',
            'assignee_id' => 'nullable|exists:users,id',
            'priority' => 'nullable|in:low,normal,high',
            'due_at' => 'nullable|date',
            'task_type' => 'nullable|string|max:50',
        ]);

        if (! empty($validated['assignee_id'])) {
            $assignee = User::query()->find($validated['assignee_id']);
            if (! $assignee || (int) $assignee->ngo_id !== (int) $ngo->id) {
                return back()->withErrors(['assignee_id' => 'Assignee must belong to your NGO.'])->withInput();
            }
        }

        NgoFieldTask::query()->create([
            'ngo_id' => $ngo->id,
            'created_by_user_id' => Auth::id(),
            'assignee_id' => $validated['assignee_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'] ?? 'normal',
            'due_at' => $validated['due_at'] ?? null,
            'task_type' => $validated['task_type'] ?? 'field_visit',
            'status' => ($validated['assignee_id'] ?? null) ? 'assigned' : 'open',
            'assigned_at' => ($validated['assignee_id'] ?? null) ? now() : null,
        ]);

        return back()->with('success', 'Field task created.');
    }

    public function updateTask(Request $request, NgoFieldTask $task): RedirectResponse
    {
        $ngo = $this->ngoOrAbort();
        if ((int) $task->ngo_id !== (int) $ngo->id) {
            abort(404);
        }

        if ($this->isNgoAdmin()) {
            $validated = $request->validate([
                'status' => 'sometimes|in:draft,open,assigned,in_progress,completed,cancelled',
                'assignee_id' => 'nullable|exists:users,id',
            ]);
            if (array_key_exists('assignee_id', $validated)) {
                if ($validated['assignee_id']) {
                    $assignee = User::query()->find($validated['assignee_id']);
                    if (! $assignee || (int) $assignee->ngo_id !== (int) $ngo->id) {
                        return back()->withErrors(['assignee_id' => 'Assignee must belong to your NGO.']);
                    }
                }
                $task->assignee_id = $validated['assignee_id'];
                $task->assigned_at = $validated['assignee_id'] ? now() : null;
                if ($task->assignee_id && $task->status === 'open') {
                    $task->status = 'assigned';
                }
            }
            if (! empty($validated['status'])) {
                $task->status = $validated['status'];
                if ($validated['status'] === 'completed') {
                    $task->completed_at = now();
                }
            }
            $task->save();

            return back()->with('success', 'Task updated.');
        }

        if ((int) $task->assignee_id !== (int) Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:in_progress,completed,cancelled',
        ]);
        $task->status = $validated['status'];
        if ($validated['status'] === 'completed') {
            $task->completed_at = now();
        }
        $task->save();

        return back()->with('success', 'Task updated.');
    }

    public function startSession(Request $request): JsonResponse
    {
        $ngo = $this->ngoOrAbort();

        if (NgoFieldSession::query()
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->exists()) {
            return response()->json(['message' => 'You already have an active field session.'], 422);
        }

        $validated = $request->validate([
            'field_task_id' => 'nullable|exists:ngo_field_tasks,id',
            'notes' => 'nullable|string|max:2000',
        ]);

        $taskId = $validated['field_task_id'] ?? null;
        if ($taskId) {
            $task = NgoFieldTask::query()->where('ngo_id', $ngo->id)->findOrFail($taskId);
            if ($task->assignee_id && (int) $task->assignee_id !== (int) Auth::id()) {
                return response()->json(['message' => 'This task is assigned to another user.'], 403);
            }
            if (in_array($task->status, ['completed', 'cancelled'], true)) {
                return response()->json(['message' => 'Task is already closed.'], 422);
            }
        }

        $session = NgoFieldSession::query()->create([
            'ngo_id' => $ngo->id,
            'user_id' => Auth::id(),
            'field_task_id' => $taskId,
            'status' => 'active',
            'started_at' => now(),
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($taskId) {
            NgoFieldTask::query()->whereKey($taskId)->update(['status' => 'in_progress']);
        }

        return response()->json([
            'session' => [
                'id' => $session->id,
                'started_at' => $session->started_at->toIso8601String(),
                'field_task_id' => $session->field_task_id,
            ],
        ]);
    }

    public function appendPoints(Request $request, NgoFieldSession $session): JsonResponse
    {
        $ngo = $this->ngoOrAbort();
        if ((int) $session->ngo_id !== (int) $ngo->id || (int) $session->user_id !== (int) Auth::id()) {
            abort(403);
        }
        if ($session->status !== 'active') {
            return response()->json(['message' => 'Session is not active.'], 422);
        }

        $validated = $request->validate([
            'points' => 'required|array|max:40',
            'points.*.latitude' => 'required|numeric|between:-90,90',
            'points.*.longitude' => 'required|numeric|between:-180,180',
            'points.*.recorded_at' => 'required|date',
            'points.*.accuracy_m' => 'nullable|numeric|min:0|max:5000',
            'points.*.speed_ms' => 'nullable|numeric|min:0|max:200',
            'points.*.heading' => 'nullable|numeric|between:0,360',
            'points.*.altitude_m' => 'nullable|numeric|between:-500,9000',
        ]);

        $rows = [];
        $now = now();
        foreach ($validated['points'] as $p) {
            $rows[] = [
                'field_session_id' => $session->id,
                'latitude' => $p['latitude'],
                'longitude' => $p['longitude'],
                'recorded_at' => Carbon::parse($p['recorded_at'])->format('Y-m-d H:i:s'),
                'accuracy_m' => $p['accuracy_m'] ?? null,
                'speed_ms' => $p['speed_ms'] ?? null,
                'heading' => $p['heading'] ?? null,
                'altitude_m' => $p['altitude_m'] ?? null,
                'created_at' => $now,
            ];
        }
        NgoFieldTrackPoint::query()->insert($rows);

        $maxSpeed = collect($validated['points'])->pluck('speed_ms')->filter()->max();
        if ($maxSpeed !== null && ($session->max_speed_ms === null || (float) $maxSpeed > (float) $session->max_speed_ms)) {
            $session->update(['max_speed_ms' => $maxSpeed]);
        }

        return response()->json(['ok' => true, 'received' => count($rows)]);
    }

    public function endSession(Request $request, NgoFieldSession $session): JsonResponse
    {
        $ngo = $this->ngoOrAbort();
        if ((int) $session->ngo_id !== (int) $ngo->id || (int) $session->user_id !== (int) Auth::id()) {
            abort(403);
        }
        if ($session->status !== 'active') {
            return response()->json(['message' => 'Session already ended.'], 422);
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:2000',
        ]);

        $points = NgoFieldTrackPoint::query()
            ->where('field_session_id', $session->id)
            ->orderBy('recorded_at')
            ->get(['latitude', 'longitude']);

        $distance = 0.0;
        $prev = null;
        foreach ($points as $pt) {
            if ($prev !== null) {
                $distance += Geo::haversineMeters(
                    (float) $prev->latitude,
                    (float) $prev->longitude,
                    (float) $pt->latitude,
                    (float) $pt->longitude
                );
            }
            $prev = $pt;
        }

        DB::transaction(function () use ($session, $distance, $validated) {
            $session->update([
                'status' => 'completed',
                'ended_at' => now(),
                'distance_meters' => round($distance, 2),
                'notes' => $validated['notes'] ?? $session->notes,
            ]);

            if ($session->field_task_id) {
                NgoFieldTask::query()->whereKey($session->field_task_id)->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            }
        });

        return response()->json([
            'ok' => true,
            'distance_meters' => round($distance, 2),
            'distance_km' => round($distance / 1000, 3),
        ]);
    }

    public function sessionTrail(NgoFieldSession $session): JsonResponse
    {
        $ngo = $this->ngoOrAbort();
        if ((int) $session->ngo_id !== (int) $ngo->id) {
            abort(404);
        }
        if (! $this->isNgoAdmin() && (int) $session->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $points = NgoFieldTrackPoint::query()
            ->where('field_session_id', $session->id)
            ->orderBy('recorded_at')
            ->limit(2000)
            ->get(['latitude', 'longitude', 'recorded_at', 'speed_ms'])
            ->map(fn ($p) => [
                'lat' => (float) $p->latitude,
                'lng' => (float) $p->longitude,
                't' => $p->recorded_at?->toIso8601String(),
                'speed_kmh' => $p->speed_ms !== null ? round((float) $p->speed_ms * 3.6, 1) : null,
            ]);

        return response()->json(['points' => $points]);
    }
}
