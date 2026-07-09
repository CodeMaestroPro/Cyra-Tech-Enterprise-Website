<?php

namespace App\Repositories;

use App\Models\InsightArticle;
use Illuminate\Database\Eloquent\Collection;

class InsightArticleRepository extends BaseRepository
{
    public function __construct(InsightArticle $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, InsightArticle>
     */
    public function getActiveArticles(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function findActiveBySlug(string $slug): ?InsightArticle
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }

    public function findBySlug(string $slug): ?InsightArticle
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->first();
    }

    /**
     * @return Collection<int, InsightArticle>
     */
    public function getAllArticles(): Collection
    {
        return $this->model->newQuery()
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createArticle(array $attributes): InsightArticle
    {
        return $this->model->newQuery()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateArticle(InsightArticle $article, array $attributes): InsightArticle
    {
        $article->update($attributes);

        return $article->refresh();
    }

    public function deleteArticle(InsightArticle $article): void
    {
        $article->delete();
    }
}
