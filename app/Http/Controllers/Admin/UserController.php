<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->select('id', 'name', 'email', 'created_at', 'email_verified_at', 'is_active', 'user_type')
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        $roles = Role::all(['id', 'name']);

        return Inertia::render('Admin/Users/Create', [
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->roles()->sync($validated['roles']);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $user->load('roles');
        $roles = Role::all(['id', 'name']);

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        $user->roles()->sync($validated['roles']);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function show(User $user): Response
    {
        return Inertia::render('Admin/Users/Show', $this->buildAdminUserDetailProps($user, 'users'));
    }

    public function individuals()
    {
        $users = User::query()
            ->where('user_type', 'individual')
            ->with(['roles', 'donor'])
            ->select('id', 'name', 'email', 'phone', 'is_active', 'email_verified_at', 'created_at')
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Individuals/Index', [
            'users' => $users,
        ]);
    }

    public function showIndividual(User $user): Response
    {
        if ($user->user_type !== 'individual') {
            abort(404);
        }

        return Inertia::render('Admin/Users/Show', $this->buildAdminUserDetailProps($user, 'individuals'));
    }

    public function toggleBlock(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot block your own account.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', $user->is_active ? 'User unblocked.' : 'User blocked (cannot sign in).');
    }

    public function approveIndividual(User $user)
    {
        if ($user->user_type !== 'individual') {
            abort(404);
        }

        $user->update([
            'is_active' => true,
            'email_verified_at' => $user->email_verified_at ?? now(),
        ]);

        return back()->with('success', 'Individual approved successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function buildAdminUserDetailProps(User $user, string $entrySource): array
    {
        $user->load([
            'roles',
            'ngo',
            'ngoUser.ngo',
            'donor.donations' => static fn ($q) => $q->with('campaign')->latest()->limit(30),
        ]);

        $activityLog = DB::table('analytics_page_views')
            ->where('user_id', $user->id)
            ->where('viewed_at', '>=', now()->subDays(90))
            ->orderByDesc('viewed_at')
            ->limit(120)
            ->get()
            ->map(static function ($row) {
                return [
                    'id' => $row->id,
                    'path' => $row->path,
                    'full_url' => $row->full_url,
                    'route_name' => $row->route_name,
                    'device_type' => $row->device_type,
                    'browser_name' => $row->browser_name,
                    'os_name' => $row->os_name,
                    'country_code' => $row->country_code,
                    'city' => $row->city,
                    'region' => $row->region,
                    'ip_address' => $row->ip_address,
                    'viewed_at' => $row->viewed_at,
                    'user_agent' => $row->user_agent ? mb_substr((string) $row->user_agent, 0, 180) : null,
                ];
            });

        $activitySummary = [
            'views_7d' => (int) DB::table('analytics_page_views')
                ->where('user_id', $user->id)
                ->where('viewed_at', '>=', now()->subDays(7))
                ->count(),
            'views_30d' => (int) DB::table('analytics_page_views')
                ->where('user_id', $user->id)
                ->where('viewed_at', '>=', now()->subDays(30))
                ->count(),
            'top_paths_30d' => DB::table('analytics_page_views')
                ->select('path', DB::raw('COUNT(*) as count'))
                ->where('user_id', $user->id)
                ->where('viewed_at', '>=', now()->subDays(30))
                ->groupBy('path')
                ->orderByDesc('count')
                ->limit(10)
                ->get(),
            'last_seen' => DB::table('analytics_page_views')
                ->where('user_id', $user->id)
                ->max('viewed_at'),
        ];

        return [
            'profile' => $user,
            'entrySource' => $entrySource,
            'canApproveIndividual' => $entrySource === 'individuals' && $user->user_type === 'individual',
            'activityLog' => $activityLog,
            'activitySummary' => $activitySummary,
        ];
    }
}
