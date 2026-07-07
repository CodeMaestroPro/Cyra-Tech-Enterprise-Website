<?php

namespace App\Repositories;

use App\Models\MediaAsset;
use Illuminate\Database\Eloquent\Collection;

class MediaAssetRepository extends BaseRepository
{
    public function __construct(MediaAsset $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, MediaAsset>
     */
    public function getActiveAssets(?string $category = null): Collection
    {
        return $this->model->newQuery()
            ->with('uploader')
            ->where('is_active', true)
            ->when($category !== null && $category !== 'all', fn ($query) => $query->where('category', $category))
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }

    /**
     * @return Collection<int, MediaAsset>
     */
    public function getAllAssets(?string $category = null): Collection
    {
        return $this->model->newQuery()
            ->with('uploader')
            ->when($category !== null && $category !== 'all', fn ($query) => $query->where('category', $category))
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }

    public function findByUuid(string $uuid): ?MediaAsset
    {
        return $this->model->newQuery()
            ->with('uploader')
            ->where('uuid', $uuid)
            ->first();
    }

    public function findActiveByUuid(string $uuid): ?MediaAsset
    {
        return $this->model->newQuery()
            ->where('uuid', $uuid)
            ->where('is_active', true)
            ->first();
    }

    public function createAsset(array $attributes): MediaAsset
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function updateAsset(MediaAsset $asset, array $attributes): MediaAsset
    {
        $asset->update($attributes);

        return $asset->fresh(['uploader']);
    }

    public function deleteAsset(MediaAsset $asset): bool
    {
        return (bool) $asset->delete();
    }
}
