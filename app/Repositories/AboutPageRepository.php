<?php

namespace App\Repositories;

use App\Models\AboutPage;
use Illuminate\Database\Eloquent\Collection;

class AboutPageRepository extends BaseRepository
{
    public function __construct(AboutPage $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, AboutPage>
     */
    public function getActivePages(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function findActiveBySlug(string $slug): ?AboutPage
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
}
