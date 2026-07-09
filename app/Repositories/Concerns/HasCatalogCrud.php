<?php

namespace App\Repositories\Concerns;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait HasCatalogCrud
{
    /**
     * @return Collection<int, Model>
     */
    public function getAllRecords(): Collection
    {
        return $this->model->newQuery()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function findBySlug(string $slug): ?Model
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->first();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createRecord(array $attributes): Model
    {
        return $this->model->newQuery()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateRecord(Model $record, array $attributes): Model
    {
        $record->update($attributes);

        return $record->refresh();
    }

    public function deleteRecord(Model $record): void
    {
        $record->delete();
    }
}
