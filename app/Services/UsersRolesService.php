<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsersRolesService extends BaseService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly RoleRepository $roleRepository,
        private readonly PermissionRepository $permissionRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getWorkspace(): array
    {
        $configured = config('cyra.users_roles_workspace', []);
        $roleMeta = $configured['role_meta'] ?? [];

        $users = $this->userRepository->getAllWithRoles();
        $roles = $this->roleRepository->allWithPermissions();
        $permissions = $this->permissionRepository->getAllOrdered();

        $mappedUsers = $users
            ->map(fn (User $user) => $this->formatUser($user, $roleMeta))
            ->values()
            ->all();

        $mappedRoles = $roles
            ->map(fn (Role $role) => $this->formatRole($role, $roleMeta, $users))
            ->values()
            ->all();

        return [
            'summary' => [
                'total_users' => $users->count(),
                'active_users' => $users->where('is_active', true)->count(),
                'inactive_users' => $users->where('is_active', false)->count(),
                'total_roles' => $roles->count(),
                'system_roles' => $roles->where('is_system', true)->count(),
                'total_permissions' => $permissions->count(),
            ],
            'description' => $configured['summary'] ?? 'Manage platform users, roles, and permission assignments.',
            'users' => $mappedUsers,
            'roles' => $mappedRoles,
            'permission_groups' => $this->buildPermissionGroups($permissions),
            'role_distribution' => $this->buildRoleDistribution($mappedRoles),
            'quick_links' => $this->buildQuickLinks($configured['quick_links'] ?? []),
            'workspace_notes' => $configured['workspace_notes'] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getFormOptions(User $actor): array
    {
        $roleMeta = config('cyra.users_roles_workspace.role_meta', []);

        return [
            'can_manage_roles' => $actor->can('assignRoles', User::class),
            'assignable_roles' => $this->getAssignableRoles($actor)
                ->map(fn (Role $role) => [
                    'slug' => $role->slug,
                    'name' => $role->name,
                    'description' => $role->description,
                    'icon' => $roleMeta[$role->slug]['icon'] ?? 'shield',
                ])
                ->values()
                ->all(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createUser(User $actor, array $data): User
    {
        $roleSlugs = $data['roles'] ?? [];

        if ($actor->can('assignRoles', User::class)) {
            $this->assertRoleSlugsAllowed($actor, $roleSlugs);
        } else {
            $roleSlugs = [];
        }

        $user = $this->userRepository->createUser([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => (bool) ($data['is_active'] ?? true),
            'email_verified_at' => now(),
        ]);

        if ($roleSlugs !== []) {
            $user->syncRoles($roleSlugs);
        }

        return $user->load('roles');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateUser(User $actor, User $target, array $data): User
    {
        if ($actor->id === $target->id && array_key_exists('is_active', $data) && ! $data['is_active']) {
            throw ValidationException::withMessages([
                'is_active' => 'You cannot deactivate your own account.',
            ]);
        }

        $attributes = [
            'name' => $data['name'],
            'email' => $data['email'],
            'is_active' => (bool) ($data['is_active'] ?? false),
        ];

        if (! empty($data['password'])) {
            $attributes['password'] = Hash::make($data['password']);
        }

        $user = $this->userRepository->updateUser($target, $attributes);

        if ($actor->can('assignRoles', User::class) && array_key_exists('roles', $data)) {
            $roleSlugs = $data['roles'];

            if ($actor->id === $target->id && ! in_array('super-admin', $roleSlugs, true)) {
                throw ValidationException::withMessages([
                    'roles' => 'You cannot remove the super administrator role from your own account.',
                ]);
            }

            $this->assertRoleSlugsAllowed($actor, $roleSlugs);
            $user->syncRoles($roleSlugs);
        }

        return $user->load('roles');
    }

    public function deleteUser(User $actor, User $target): void
    {
        if ($actor->id === $target->id) {
            throw ValidationException::withMessages([
                'user' => 'You cannot delete your own account.',
            ]);
        }

        $target->roles()->detach();
        $target->delete();
    }

    /**
     * @param  list<string>  $roleSlugs
     */
    public function assertRoleSlugsAllowed(User $actor, array $roleSlugs): void
    {
        if (! $actor->can('assignRoles', User::class)) {
            throw ValidationException::withMessages([
                'roles' => 'You do not have permission to assign roles.',
            ]);
        }

        $allowed = $this->getAssignableRoles($actor)->pluck('slug')->all();
        $invalid = array_values(array_diff($roleSlugs, $allowed));

        if ($invalid !== []) {
            throw ValidationException::withMessages([
                'roles' => 'One or more selected roles cannot be assigned.',
            ]);
        }
    }

    /**
     * @return \Illuminate\Support\Collection<int, Role>
     */
    private function getAssignableRoles(User $actor)
    {
        $roles = $this->roleRepository->allWithPermissions();

        if (! $actor->can('assignRoles', User::class)) {
            return collect();
        }

        if ($actor->hasRole('super-admin')) {
            return $roles;
        }

        return $roles->where('slug', '!=', 'super-admin')->values();
    }

    /**
     * @param  array<string, array<string, string>>  $roleMeta
     * @return array<string, mixed>
     */
    private function formatUser(User $user, array $roleMeta): array
    {
        $nameParts = preg_split('/\s+/', trim($user->name)) ?: [];
        $initials = collect($nameParts)
            ->take(2)
            ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
            ->implode('');

        $primaryRole = $user->roles->sortBy('id')->first();
        $primarySlug = $primaryRole?->slug ?? 'unassigned';
        $meta = $roleMeta[$primarySlug] ?? ['icon' => 'users', 'accent' => 'text-cyra-accent'];

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'initials' => $initials,
            'is_active' => $user->is_active,
            'roles' => $user->roles->pluck('name')->values()->all(),
            'role_slugs' => $user->roles->pluck('slug')->values()->all(),
            'primary_role' => $primaryRole?->name ?? 'Unassigned',
            'primary_role_slug' => $primarySlug,
            'role_icon' => $meta['icon'] ?? 'users',
            'permission_count' => $user->getPermissionSlugs()->contains('*')
                ? 'All'
                : (string) $user->getPermissionSlugs()->count(),
            'last_login_at' => $user->last_login_at?->format('M j, Y g:i A'),
            'last_login_ago' => $user->last_login_at?->diffForHumans() ?? 'Never',
            'is_client' => $user->client_account_id !== null,
            'edit_url' => route('admin.users-roles.edit', $user),
        ];
    }

    /**
     * @param  array<string, array<string, string>>  $roleMeta
     * @param  \Illuminate\Database\Eloquent\Collection<int, User>  $users
     * @return array<string, mixed>
     */
    private function formatRole(Role $role, array $roleMeta, $users): array
    {
        $meta = $roleMeta[$role->slug] ?? ['icon' => 'shield', 'accent' => 'text-cyra-primary'];
        $permissionSlugs = $role->permissions->pluck('slug')->values()->all();
        $hasWildcard = in_array('*', $permissionSlugs, true);

        return [
            'id' => $role->id,
            'name' => $role->name,
            'slug' => $role->slug,
            'description' => $role->description,
            'icon' => $meta['icon'] ?? 'shield',
            'is_system' => $role->is_system,
            'user_count' => $users->filter(fn (User $user) => $user->roles->contains('id', $role->id))->count(),
            'permission_count' => $hasWildcard ? 'All' : count($permissionSlugs),
            'permissions' => $hasWildcard ? ['All permissions'] : collect($permissionSlugs)->take(8)->values()->all(),
            'permissions_overflow' => $hasWildcard ? 0 : max(0, count($permissionSlugs) - 8),
        ];
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Collection<int, Permission>  $permissions
     * @return list<array<string, mixed>>
     */
    private function buildPermissionGroups($permissions): array
    {
        return $permissions
            ->groupBy('group')
            ->map(function ($group, string $groupName) {
                return [
                    'group' => $groupName,
                    'count' => $group->count(),
                    'permissions' => $group->map(fn (Permission $permission) => [
                        'slug' => $permission->slug,
                        'name' => $permission->name,
                    ])->values()->all(),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  list<array<string, mixed>>  $roles
     * @return list<array<string, mixed>>
     */
    private function buildRoleDistribution(array $roles): array
    {
        return collect($roles)
            ->map(fn (array $role) => [
                'slug' => $role['slug'],
                'name' => $role['name'],
                'icon' => $role['icon'],
                'user_count' => $role['user_count'],
            ])
            ->sortByDesc('user_count')
            ->values()
            ->all();
    }

    /**
     * @param  list<array<string, mixed>>  $links
     * @return list<array<string, mixed>>
     */
    private function buildQuickLinks(array $links): array
    {
        return collect($links)
            ->map(function (array $link) {
                $route = $link['route'] ?? null;

                return [
                    'label' => $link['label'] ?? '',
                    'icon' => $link['icon'] ?? 'link',
                    'description' => $link['description'] ?? '',
                    'href' => $route ? route($route) : ($link['url'] ?? '#'),
                    'external' => $link['external'] ?? false,
                ];
            })
            ->values()
            ->all();
    }
}
