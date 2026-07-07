<?php

namespace App\Repositories;

use App\Models\PartnerProgram;
use Illuminate\Database\Eloquent\Collection;

class PartnerProgramRepository extends BaseRepository
{
    public function __construct(PartnerProgram $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, PartnerProgram>
     */
    public function getActivePrograms(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function findActiveBySlug(string $slug): ?PartnerProgram
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
}
