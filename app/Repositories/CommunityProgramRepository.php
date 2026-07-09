<?php

namespace App\Repositories;

use App\Models\CommunityProgram;
use App\Repositories\Concerns\HasCatalogCrud;
use Illuminate\Database\Eloquent\Collection;

class CommunityProgramRepository extends BaseRepository
{
    use HasCatalogCrud;
    public function __construct(CommunityProgram $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, CommunityProgram>
     */
    public function getActivePrograms(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function findActiveBySlug(string $slug): ?CommunityProgram
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
}
