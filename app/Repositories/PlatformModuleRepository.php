<?php

namespace App\Repositories;

use App\Models\PlatformModule;

class PlatformModuleRepository extends BaseRepository
{
    public function __construct(PlatformModule $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug): ?PlatformModule
    {
        return $this->model->newQuery()->where('slug', $slug)->first();
    }
}
