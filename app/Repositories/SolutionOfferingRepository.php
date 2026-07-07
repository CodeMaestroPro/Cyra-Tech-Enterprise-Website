<?php

namespace App\Repositories;

use App\Models\SolutionOffering;
use Illuminate\Database\Eloquent\Collection;

class SolutionOfferingRepository extends BaseRepository
{
    public function __construct(SolutionOffering $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, SolutionOffering>
     */
    public function getActiveOfferings(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function findActiveBySlug(string $slug): ?SolutionOffering
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
}
