<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());
        $role = $request->input('role');
        $status = $request->input('status');
        $perPage = in_array($request->input('per_page'), ['10', '15', '25', '50'], true)
            ? (int) $request->input('per_page')
            : 15;

        $query = User::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('full_name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(in_array($role, [User::ROLE_ADMIN, User::ROLE_USER], true), fn ($query) => $query->where('role', $role))
            ->when(in_array($status, ['active', 'inactive'], true), fn ($query) => $query->where('status', $status === 'active'));

        return view('Admin.users.index', [
            'users' => $query->orderBy('id')->paginate($perPage)->withQueryString(),
            'totalUsers' => User::count(),
            'activeUsers' => User::where('status', true)->count(),
            'inactiveUsers' => User::where('status', false)->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_USER])],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        User::create([
            'name' => $validated['full_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'status' => $validated['status'] === 'active',
        ]);

        return back()->with('success', 'User account created successfully.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users', 'username')->ignore($user)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_USER])],
        ]);

        if ($user->is(auth()->user()) && $validated['role'] !== User::ROLE_ADMIN) {
            return back()->with('danger', 'You cannot remove your own administrator role.');
        }

        if ($user->isAdmin() && $validated['role'] !== User::ROLE_ADMIN && User::where('role', User::ROLE_ADMIN)->count() === 1) {
            return back()->with('danger', 'You cannot remove the role from the last administrator account.');
        }

        $user->name = $validated['full_name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (filled($validated['password'] ?? null)) {
            $user->password = $validated['password'];
        }

        $user->save();

        return back()->with('success', 'User account updated successfully.');
    }

    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if ($user->is(auth()->user()) && $validated['status'] === 'inactive') {
            return back()->with('danger', 'You cannot deactivate your own account.');
        }

        $user->status = $validated['status'] === 'active';
        $user->save();

        return back()->with('success', 'User account status updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return back()->with('danger', 'You cannot delete your own account.');
        }

        if ($user->isAdmin() && User::where('role', User::ROLE_ADMIN)->count() === 1) {
            return back()->with('danger', 'You cannot delete the last administrator account.');
        }

        $user->delete();

        return back()->with('success', 'User account deleted successfully.');
    }
}
