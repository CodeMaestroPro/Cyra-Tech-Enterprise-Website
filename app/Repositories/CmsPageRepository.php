<?php

namespace App\Repositories;

use App\Models\CmsPage;
use Illuminate\Database\Eloquent\Collection;

class CmsPageRepository extends BaseRepository
{
    public function __construct(CmsPage $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, CmsPage>
     */
    public function getPublishedPages(): Collection
    {
        return $this->model->newQuery()
            ->where('status', 'published')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function findPublishedBySlug(string $slug): ?CmsPage
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('is_active', true)
            ->first();
    }

    /**
     * @return Collection<int, CmsPage>
     */
    public function getAllPages(?string $status = null): Collection
    {
        return $this->model->newQuery()
            ->with('author')
            ->when($status !== null && $status !== 'all', fn ($query) => $query->where('status', $status))
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }

    public function findBySlug(string $slug): ?CmsPage
    {
        return $this->model->newQuery()
            ->with('author')
            ->where('slug', $slug)
            ->first();
    }

    public function createPage(array $attributes): CmsPage
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function updatePage(CmsPage $page, array $attributes): CmsPage
    {
        $page->update($attributes);

        return $page->fresh();
    }
}
