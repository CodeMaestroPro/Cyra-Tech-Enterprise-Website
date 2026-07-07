<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService extends BaseService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function attempt(array $credentials, bool $remember = false): User
    {
        $email = $credentials['email'] ?? null;
        $user = $email ? $this->userRepository->findByEmail($email) : null;

        if (! $user || ! $user->isActive()) {
            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        if (! Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'is_active' => true,
        ], $remember)) {
            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        /** @var User $authenticated */
        $authenticated = Auth::user();
        $this->userRepository->markLastLogin($authenticated);

        return $authenticated;
    }

    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    public function resolveHomeRoute(User $user): string
    {
        if ($user->hasPermission('dashboard.access')) {
            return route('admin.dashboard');
        }

        if ($user->hasPermission('client-portal.access')) {
            return route('client-portal.dashboard');
        }

        return route('home');
    }

    /**
     * @return array<string, mixed>
     */
    public function profile(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_active' => $user->is_active,
            'last_login_at' => $user->last_login_at?->toIso8601String(),
            'roles' => $user->roles()->get(['name', 'slug'])->map(fn ($role) => [
                'name' => $role->name,
                'slug' => $role->slug,
            ])->values()->all(),
            'primary_role' => $user->getPrimaryRoleName(),
            'permissions' => $user->getPermissionSlugs()->values()->all(),
        ];
    }
}
