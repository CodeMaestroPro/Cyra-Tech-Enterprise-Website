<?php

namespace App\Repositories;

use App\Models\InnovationInitiative;
use App\Repositories\Concerns\HasCatalogCrud;
use Illuminate\Database\Eloquent\Collection;

class InnovationInitiativeRepository extends BaseRepository
{
    use HasCatalogCrud;
    public function __construct(InnovationInitiative $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, InnovationInitiative>
     */
    public function getActiveInitiatives(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function findActiveBySlug(string $slug): ?InnovationInitiative
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
}
