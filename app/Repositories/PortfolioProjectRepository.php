<?php

namespace App\Repositories;

use App\Models\PortfolioProject;
use Illuminate\Database\Eloquent\Collection;

class PortfolioProjectRepository extends BaseRepository
{
    public function __construct(PortfolioProject $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, PortfolioProject>
     */
    public function getActiveProjects(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function findActiveBySlug(string $slug): ?PortfolioProject
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
}
