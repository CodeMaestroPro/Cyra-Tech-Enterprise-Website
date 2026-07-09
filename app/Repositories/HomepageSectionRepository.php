<?php

namespace App\Repositories;

use App\Models\HomepageSection;
use Illuminate\Database\Eloquent\Collection;

class HomepageSectionRepository extends BaseRepository
{
    public function __construct(HomepageSection $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, HomepageSection>
     */
    public function getActiveSections(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * @return Collection<int, HomepageSection>
     */
    public function getAllSections(): Collection
    {
        return $this->model->newQuery()
            ->orderBy('sort_order')
            ->get();
    }
}
