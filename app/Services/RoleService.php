<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;

class RoleService extends BaseService
{
    public function __construct(
        private readonly RoleRepository $roleRepository,
        private readonly PermissionRepository $permissionRepository,
    ) {
    }

    public function userCan(User $user, string $permission): bool
    {
        return $user->hasPermission($permission);
    }

    public function userHasRole(User $user, string $role): bool
    {
        return $user->hasRole($role);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function listRoles(): array
    {
        return $this->roleRepository->allWithPermissions()->map(function (Role $role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
                'description' => $role->description,
                'permissions' => $role->permissions->pluck('slug')->values()->all(),
            ];
        })->values()->all();
    }

    public function syncConfiguredRolesAndPermissions(): void
    {
        foreach (config('cyra.permissions', []) as $slug => $meta) {
            Permission::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $meta['name'],
                    'group' => $meta['group'],
                    'description' => $meta['name'],
                ],
            );
        }

        $wildcard = Permission::query()->firstOrCreate(
            ['slug' => '*'],
            ['name' => 'All Permissions', 'group' => 'System', 'description' => 'Grants all permissions'],
        );

        foreach (config('cyra.roles', []) as $slug => $meta) {
            $role = Role::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $meta['name'],
                    'description' => $meta['description'] ?? null,
                    'is_system' => true,
                ],
            );

            $permissionIds = collect($meta['permissions'] ?? [])
                ->flatMap(function (string $permissionSlug) use ($wildcard) {
                    if ($permissionSlug === '*') {
                        return [$wildcard->id];
                    }

                    $permission = $this->permissionRepository->findBySlug($permissionSlug);

                    return $permission ? [$permission->id] : [];
                })
                ->unique()
                ->values()
                ->all();

            $role->permissions()->sync($permissionIds);
        }
    }
}
