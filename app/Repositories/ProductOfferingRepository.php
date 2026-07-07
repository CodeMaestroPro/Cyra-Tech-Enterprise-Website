<?php

namespace App\Repositories;

use App\Models\ProductOffering;
use Illuminate\Database\Eloquent\Collection;

class ProductOfferingRepository extends BaseRepository
{
    public function __construct(ProductOffering $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, ProductOffering>
     */
    public function getActiveProducts(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function findActiveBySlug(string $slug): ?ProductOffering
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
}
