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

    /**
     * @return Collection<int, PartnerProgram>
     */
    public function getAllPrograms(): Collection
    {
        return $this->model->newQuery()
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }

    public function findActiveBySlug(string $slug): ?PartnerProgram
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }

    public function findBySlug(string $slug): ?PartnerProgram
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->first();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createProgram(array $attributes): PartnerProgram
    {
        return $this->model->newQuery()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateProgram(PartnerProgram $program, array $attributes): PartnerProgram
    {
        $program->update($attributes);

        return $program->refresh();
    }

    public function deleteProgram(PartnerProgram $program): void
    {
        $program->delete();
    }
}
