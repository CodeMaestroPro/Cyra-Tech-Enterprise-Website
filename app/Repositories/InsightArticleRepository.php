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
}
