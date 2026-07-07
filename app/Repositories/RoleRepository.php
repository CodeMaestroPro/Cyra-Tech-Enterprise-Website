<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository extends BaseRepository
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug): ?Role
    {
        return $this->model->newQuery()->where('slug', $slug)->first();
    }

    public function allWithPermissions()
    {
        return $this->model->newQuery()->with('permissions')->orderBy('name')->get();
    }
}
