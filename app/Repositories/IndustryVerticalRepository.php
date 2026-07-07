<?php

namespace App\Repositories;

use App\Models\IndustryVertical;
use Illuminate\Database\Eloquent\Collection;

class IndustryVerticalRepository extends BaseRepository
{
    public function __construct(IndustryVertical $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, IndustryVertical>
     */
    public function getActiveVerticals(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function findActiveBySlug(string $slug): ?IndustryVertical
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
}
