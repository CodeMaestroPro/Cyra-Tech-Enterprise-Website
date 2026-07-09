<?php

namespace App\Repositories;

use App\Models\LeadershipProfile;
use App\Repositories\Concerns\HasCatalogCrud;
use Illuminate\Database\Eloquent\Collection;

class LeadershipProfileRepository extends BaseRepository
{
    use HasCatalogCrud;
    public function __construct(LeadershipProfile $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, LeadershipProfile>
     */
    public function getActiveProfiles(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function findActiveBySlug(string $slug): ?LeadershipProfile
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
}
