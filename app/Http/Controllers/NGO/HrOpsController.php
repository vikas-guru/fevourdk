<?php

namespace App\Http\Controllers\NGO;

use App\Http\Controllers\Controller;
use App\Models\NgoLeaveRequest;
use App\Models\NgoLeaveType;
use App\Models\NGO;
use App\Models\NGOUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class HrOpsController extends Controller
{
    private function ngo(): NGO
    {
        $ngo = Auth::user()?->ngo;
        if (! $ngo) {
            abort(403);
        }

        return $ngo;
    }

    private function isNgoAdmin(): bool
    {
        return Auth::user()->hasRole('ngo_admin');
    }

    private function activeNgoAdminCount(int $ngoId, ?int $excludeUserId = null): int
    {
        $q = User::query()
            ->where('ngo_id', $ngoId)
            ->where('is_active', true)
            ->whereHas('roles', fn ($r) => $r->where('name', 'ngo_admin'));

        if ($excludeUserId !== null) {
            $q->where('id', '!=', $excludeUserId);
        }

        return $q->count();
    }

    /**
     * HRMS landing: one login for staff, links to people, leave, field site work, claims, updates.
     */
    public function index(): Response
    {
        $ngo = $this->ngo();

        return Inertia::render('NGO/Hr/Index', [
            'ngo' => $ngo,
            'isNgoAdmin' => $this->isNgoAdmin(),
        ]);
    }

    public function team(): Response
    {
        $ngo = $this->ngo();

        $members = User::query()
            ->where('ngo_id', $ngo->id)
            ->with(['roles:id,name', 'ngoUser.manager:id,name'])
            ->orderBy('name')
            ->get()
            ->map(function (User $u) {
                $nu = $u->ngoUser;

                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'phone' => $u->phone,
                    'is_active' => (bool) $u->is_active,
                    'roles' => $u->roles,
                    'designation' => $nu?->designation,
                    'job_title' => $nu?->job_title,
                    'department' => $nu?->department,
                    'joined_at' => $nu?->joined_at?->toDateString(),
                    'manager_name' => $nu?->manager?->name,
                    'manager_user_id' => $nu?->manager_user_id,
                    'employee_code' => $nu?->employee_code,
                    'employment_type' => $nu?->employment_type,
                    'work_location' => $nu?->work_location,
                    'hr_active' => $nu ? (bool) $nu->is_active : true,
                ];
            });

        $managers = User::query()
            ->where('ngo_id', $ngo->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return Inertia::render('NGO/Hr/Team', [
            'ngo' => $ngo,
            'members' => $members,
            'managers' => $managers,
            'isNgoAdmin' => $this->isNgoAdmin(),
        ]);
    }

    /**
     * Create a new employee or attach an existing account that has no NGO yet.
     */
    public function storeEmployee(Request $request): RedirectResponse
    {
        if (! $this->isNgoAdmin()) {
            abort(403);
        }
        $ngo = $this->ngo();

        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'workplace_role' => 'required|in:ngo_staff,ngo_admin,ngo_finance',
            'designation' => 'nullable|string|max:120',
            'job_title' => 'nullable|string|max:120',
            'department' => 'nullable|string|max:120',
            'joined_at' => 'nullable|date',
            'manager_user_id' => 'nullable|exists:users,id',
            'employee_code' => [
                'nullable',
                'string',
                'max:40',
                Rule::unique('ngo_users', 'employee_code')->where(fn ($q) => $q->where('ngo_id', $ngo->id)),
            ],
            'employment_type' => 'nullable|in:full_time,part_time,intern,volunteer,consultant',
            'work_location' => 'nullable|string|max:120',
            'send_invite' => 'boolean',
            'password' => 'nullable|string|min:8|max:255',
        ]);

        $validated['employee_code'] = ! empty($validated['employee_code']) ? trim($validated['employee_code']) : null;
        $validated['employment_type'] = ! empty($validated['employment_type']) ? $validated['employment_type'] : null;
        $validated['work_location'] = ! empty($validated['work_location']) ? trim($validated['work_location']) : null;

        $sendInvite = (bool) ($validated['send_invite'] ?? true);
        if (! $sendInvite && empty($validated['password'])) {
            return back()->withErrors(['password' => 'Enter a temporary password or enable “Send email invite”.'])->withInput();
        }

        if (! empty($validated['manager_user_id'])) {
            $mgr = User::query()->find($validated['manager_user_id']);
            if (! $mgr || (int) $mgr->ngo_id !== (int) $ngo->id) {
                return back()->withErrors(['manager_user_id' => 'Manager must belong to your NGO.'])->withInput();
            }
        }

        $email = Str::lower(trim($validated['email']));
        $existing = User::query()->whereRaw('LOWER(email) = ?', [$email])->first();

        if ($existing) {
            if ((int) $existing->ngo_id === (int) $ngo->id) {
                return back()->withErrors(['email' => 'This person is already on your team.'])->withInput();
            }
            if ($existing->ngo_id !== null) {
                return back()->withErrors(['email' => 'This email is already linked to another organisation.'])->withInput();
            }
            if ($existing->hasRole('super_admin') || $existing->hasRole('state_admin')) {
                return back()->withErrors(['email' => 'This platform account cannot be added to an NGO.'])->withInput();
            }

            return $this->attachEmployeeToNgo($ngo, $existing, $validated, $sendInvite);
        }

        $passwordHash = $sendInvite
            ? Hash::make(Str::password(32))
            : Hash::make($validated['password']);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $email,
            'phone' => $validated['phone'] ?? null,
            'password' => $passwordHash,
            'user_type' => 'ngo',
            'ngo_id' => $ngo->id,
            'is_active' => true,
            'email_verified_at' => $sendInvite ? null : now(),
        ]);

        $user->assignRole($validated['workplace_role']);

        $this->persistNgoUserRow($ngo, $user, $validated);

        if ($sendInvite) {
            Password::sendResetLink(['email' => $user->email]);
        }

        $msg = $sendInvite
            ? 'Employee added. They will receive an email to set their password and can use the same login as the rest of your team.'
            : 'Employee added with the password you set. Ask them to sign in and change it from profile settings.';

        return back()->with('success', $msg);
    }

    private function attachEmployeeToNgo(NGO $ngo, User $user, array $validated, bool $sendInvite): RedirectResponse
    {
        $user->forceFill([
            'name' => $validated['name'],
            'ngo_id' => $ngo->id,
            'user_type' => 'ngo',
            'phone' => $validated['phone'] ?? $user->phone,
            'is_active' => true,
        ])->save();

        $user->removeRole('ngo_staff');
        $user->removeRole('ngo_admin');
        $user->assignRole($validated['workplace_role']);

        $this->persistNgoUserRow($ngo, $user, $validated);

        if ($sendInvite) {
            Password::sendResetLink(['email' => $user->email]);
        }

        return back()->with('success', 'Existing account linked to your NGO.'.($sendInvite ? ' We sent a password reset link so they can sign in securely.' : ''));
    }

    private function persistNgoUserRow(NGO $ngo, User $user, array $validated): void
    {
        $ngoUser = NGOUser::query()->firstOrCreate(
            ['user_id' => $user->id, 'ngo_id' => $ngo->id],
            [
                'role' => $validated['workplace_role'] === 'ngo_admin' ? 'admin' : 'staff',
                'is_active' => true,
            ]
        );

        $ngoUser->fill([
            'role' => $validated['workplace_role'] === 'ngo_admin' ? 'admin' : 'staff',
            'designation' => $validated['designation'] ?? $ngoUser->designation,
            'job_title' => $validated['job_title'] ?? $ngoUser->job_title,
            'department' => $validated['department'] ?? $ngoUser->department,
            'joined_at' => $validated['joined_at'] ?? $ngoUser->joined_at,
            'manager_user_id' => $validated['manager_user_id'] ?? null,
            'employee_code' => $validated['employee_code'] ?? $ngoUser->employee_code,
            'employment_type' => $validated['employment_type'] ?? $ngoUser->employment_type,
            'work_location' => $validated['work_location'] ?? $ngoUser->work_location,
            'is_active' => true,
        ]);
        $ngoUser->save();
    }

    public function updateMember(Request $request, User $user): RedirectResponse
    {
        if (! $this->isNgoAdmin()) {
            abort(403);
        }
        $ngo = $this->ngo();
        if ((int) $user->ngo_id !== (int) $ngo->id) {
            abort(404);
        }

        $ngoUserRow = NGOUser::query()->firstOrCreate(
            ['user_id' => $user->id, 'ngo_id' => $ngo->id],
            ['role' => 'staff', 'is_active' => true]
        );

        $validated = $request->validate([
            'designation' => 'nullable|string|max:120',
            'job_title' => 'nullable|string|max:120',
            'department' => 'nullable|string|max:120',
            'joined_at' => 'nullable|date',
            'manager_user_id' => 'nullable|exists:users,id',
            'workplace_role' => 'nullable|in:ngo_staff,ngo_admin,ngo_finance',
            'employee_code' => [
                'nullable',
                'string',
                'max:40',
                Rule::unique('ngo_users', 'employee_code')->where(fn ($q) => $q->where('ngo_id', $ngo->id))->ignore($ngoUserRow->id),
            ],
            'employment_type' => 'nullable|in:full_time,part_time,intern,volunteer,consultant',
            'work_location' => 'nullable|string|max:120',
        ]);

        if (! empty($validated['manager_user_id'])) {
            $mgr = User::query()->find($validated['manager_user_id']);
            if (! $mgr || (int) $mgr->ngo_id !== (int) $ngo->id) {
                return back()->withErrors(['manager_user_id' => 'Manager must belong to your NGO.']);
            }
            if ((int) $validated['manager_user_id'] === (int) $user->id) {
                return back()->withErrors(['manager_user_id' => 'An employee cannot be their own manager.']);
            }
        }

        if (isset($validated['workplace_role'])) {
            if ($user->hasRole('ngo_admin') && in_array($validated['workplace_role'], ['ngo_staff', 'ngo_finance'], true)) {
                if ($this->activeNgoAdminCount($ngo->id) <= 1) {
                    return back()->withErrors(['workplace_role' => 'Keep at least one active NGO admin for your organisation.']);
                }
            }

            $user->removeRole('ngo_staff');
            $user->removeRole('ngo_admin');
            $user->removeRole('ngo_finance');
            $user->assignRole($validated['workplace_role']);

            $ngoUserRow->role = $validated['workplace_role'] === 'ngo_admin' ? 'admin' : 'staff';
        }

        $ngoUserRow->fill([
            'designation' => array_key_exists('designation', $validated) ? $validated['designation'] : $ngoUserRow->designation,
            'job_title' => array_key_exists('job_title', $validated) ? $validated['job_title'] : $ngoUserRow->job_title,
            'department' => array_key_exists('department', $validated) ? $validated['department'] : $ngoUserRow->department,
            'joined_at' => array_key_exists('joined_at', $validated) ? $validated['joined_at'] : $ngoUserRow->joined_at,
            'manager_user_id' => array_key_exists('manager_user_id', $validated) ? ($validated['manager_user_id'] ?: null) : $ngoUserRow->manager_user_id,
            'employee_code' => array_key_exists('employee_code', $validated)
                ? ($validated['employee_code'] !== '' && $validated['employee_code'] !== null ? $validated['employee_code'] : null)
                : $ngoUserRow->employee_code,
            'employment_type' => array_key_exists('employment_type', $validated)
                ? ($validated['employment_type'] !== '' ? $validated['employment_type'] : null)
                : $ngoUserRow->employment_type,
            'work_location' => array_key_exists('work_location', $validated)
                ? ($validated['work_location'] !== '' ? $validated['work_location'] : null)
                : $ngoUserRow->work_location,
        ]);
        $ngoUserRow->save();

        return back()->with('success', 'Employee profile updated.');
    }

    public function updateMemberActive(Request $request, User $user): RedirectResponse
    {
        if (! $this->isNgoAdmin()) {
            abort(403);
        }
        $ngo = $this->ngo();
        if ((int) $user->ngo_id !== (int) $ngo->id) {
            abort(404);
        }

        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);
        $active = (bool) $validated['is_active'];

        if (! $active && $user->hasRole('ngo_admin')) {
            if ($this->activeNgoAdminCount($ngo->id, $user->id) < 1) {
                return back()->withErrors(['is_active' => 'Add or promote another admin before deactivating this account.']);
            }
        }

        $user->forceFill(['is_active' => $active])->save();

        $ngoUserRow = NGOUser::query()->where('user_id', $user->id)->where('ngo_id', $ngo->id)->first();
        if ($ngoUserRow) {
            $ngoUserRow->is_active = $active;
            $ngoUserRow->save();
        }

        return back()->with('success', $active ? 'Employee reactivated.' : 'Employee deactivated. They can no longer sign in.');
    }

    public function storeLeaveType(Request $request): RedirectResponse
    {
        if (! $this->isNgoAdmin()) {
            abort(403);
        }
        $ngo = $this->ngo();

        $validated = $request->validate([
            'name' => 'required|string|max:80',
            'default_days_per_year' => 'nullable|integer|min:0|max:365',
            'is_paid' => 'boolean',
        ]);

        NgoLeaveType::query()->create([
            'ngo_id' => $ngo->id,
            'name' => $validated['name'],
            'default_days_per_year' => $validated['default_days_per_year'] ?? null,
            'is_paid' => (bool) ($validated['is_paid'] ?? true),
            'is_active' => true,
        ]);

        return back()->with('success', 'Leave type added.');
    }

    public function destroyLeaveType(NgoLeaveType $leaveType): RedirectResponse
    {
        if (! $this->isNgoAdmin()) {
            abort(403);
        }
        $ngo = $this->ngo();
        if ((int) $leaveType->ngo_id !== (int) $ngo->id) {
            abort(404);
        }
        if ($leaveType->leaveRequests()->where('status', 'pending')->exists()) {
            return back()->with('error', 'Cannot delete: pending requests use this type.');
        }
        $leaveType->delete();

        return back()->with('success', 'Leave type removed.');
    }

    public function leaves(): Response
    {
        $ngo = $this->ngo();
        $types = NgoLeaveType::query()->where('ngo_id', $ngo->id)->where('is_active', true)->orderBy('name')->get();

        $query = NgoLeaveRequest::query()
            ->where('ngo_id', $ngo->id)
            ->with(['user:id,name,email', 'leaveType:id,name', 'decidedBy:id,name']);

        if (! $this->isNgoAdmin()) {
            $query->where('user_id', Auth::id());
        }

        $requests = $query->orderByDesc('created_at')->limit(200)->get();

        return Inertia::render('NGO/Hr/Leaves', [
            'ngo' => $ngo,
            'leaveTypes' => $types,
            'leaveRequests' => $requests,
            'isNgoAdmin' => $this->isNgoAdmin(),
        ]);
    }

    public function storeLeaveRequest(Request $request): RedirectResponse
    {
        $ngo = $this->ngo();

        $validated = $request->validate([
            'leave_type_id' => 'required|exists:ngo_leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:2000',
        ]);

        $type = NgoLeaveType::query()->where('ngo_id', $ngo->id)->findOrFail($validated['leave_type_id']);

        $start = Carbon::parse($validated['start_date'])->startOfDay();
        $end = Carbon::parse($validated['end_date'])->startOfDay();
        $days = $start->diffInDays($end) + 1;

        NgoLeaveRequest::query()->create([
            'ngo_id' => $ngo->id,
            'user_id' => Auth::id(),
            'leave_type_id' => $type->id,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'days' => $days,
            'status' => 'pending',
            'reason' => $validated['reason'] ?? null,
        ]);

        return back()->with('success', 'Leave request submitted.');
    }

    public function decideLeave(Request $request, NgoLeaveRequest $leaveRequest): RedirectResponse
    {
        if (! $this->isNgoAdmin()) {
            abort(403);
        }
        $ngo = $this->ngo();
        if ((int) $leaveRequest->ngo_id !== (int) $ngo->id) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'decision_notes' => 'nullable|string|max:2000',
        ]);

        $leaveRequest->update([
            'status' => $validated['status'],
            'decided_by_user_id' => Auth::id(),
            'decided_at' => now(),
            'decision_notes' => $validated['decision_notes'] ?? null,
        ]);

        return back()->with('success', 'Leave request updated.');
    }
}
