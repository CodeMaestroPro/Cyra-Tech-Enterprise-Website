<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UsersRolesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UsersRolesController extends Controller
{
    public function __construct(
        private readonly UsersRolesService $usersRolesService,
    ) {
    }

    public function index(): View
    {
        return view('admin.users-roles.index', [
            'access' => $this->usersRolesService->getWorkspace(),
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', User::class);

        return view('admin.users-roles.create', [
            'formOptions' => $this->usersRolesService->getFormOptions(auth()->user()),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = $this->usersRolesService->createUser(
            $request->user(),
            $request->validated(),
        );

        return redirect()
            ->route('admin.users-roles.edit', $user)
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        Gate::authorize('update', $user);

        $user->load('roles');

        return view('admin.users-roles.edit', [
            'user' => $user,
            'formOptions' => $this->usersRolesService->getFormOptions(auth()->user()),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->usersRolesService->updateUser(
            $request->user(),
            $user,
            $request->validated(),
        );

        return redirect()
            ->route('admin.users-roles.edit', $user)
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $this->usersRolesService->deleteUser(auth()->user(), $user);

        return redirect()
            ->route('admin.users-roles.index')
            ->with('success', 'User deleted successfully.');
    }
}
