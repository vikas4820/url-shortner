<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Traits\HasInvitableRoles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    use HasInvitableRoles;

    public function index(): View
    {
        Gate::authorize('viewAny', User::class);

        $user = Auth::user();
        $users = User::where('company_id', $user->company_id)
            ->with('roles')
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        Gate::authorize('create', User::class);

        $user = Auth::user();
        $availableRoles = $this->getInvitableRoles($user);

        return view('users.create', compact('availableRoles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $user = Auth::user();

        $newUser = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'company_id' => $user->company_id,
            'email_verified_at' => now(),
        ]);

        $newUser->assignRole($request->validated('role'));

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user): View
    {
        Gate::authorize('view', $user);

        $user->load('roles');

        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        Gate::authorize('update', $user);

        $availableRoles = $this->getInvitableRoles(Auth::user());
        $userRoles = $user->getRoleNames()->toArray();

        return view('users.edit', compact('user', 'availableRoles', 'userRoles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $user->update([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => $request->validated('password')]);
        }

        $user->syncRoles([$request->validated('role')]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $currentUser = Auth::user();

        if ($user->id === $currentUser->id) {
            return back()->withErrors(['user' => 'You cannot delete yourself.']);
        }

        Gate::authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
