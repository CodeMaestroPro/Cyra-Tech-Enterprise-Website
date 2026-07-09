<?php

namespace App\Repositories;

use App\Models\Permission;

class PermissionRepository extends BaseRepository
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug): ?Permission
    {
        return $this->model->newQuery()->where('slug', $slug)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Permission>
     */
    public function getAllOrdered()
    {
        return $this->model->newQuery()
            ->where('slug', '!=', '*')
            ->orderBy('group')
            ->orderBy('name')
            ->get();
    }
}
