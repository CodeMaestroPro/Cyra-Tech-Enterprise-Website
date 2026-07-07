<?php

namespace App\Repositories;

use App\Models\CareerOpening;
use Illuminate\Database\Eloquent\Collection;

class CareerOpeningRepository extends BaseRepository
{
    public function __construct(CareerOpening $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, CareerOpening>
     */
    public function getActiveOpenings(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function findActiveBySlug(string $slug): ?CareerOpening
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
}
