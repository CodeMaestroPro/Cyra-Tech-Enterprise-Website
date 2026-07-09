<?php

namespace App\Repositories;

use App\Models\TeamMember;
use App\Repositories\Concerns\HasCatalogCrud;
use Illuminate\Database\Eloquent\Collection;

class TeamMemberRepository extends BaseRepository
{
    use HasCatalogCrud;
    public function __construct(TeamMember $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, TeamMember>
     */
    public function getActiveMembers(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * @return Collection<int, TeamMember>
     */
    public function getAllMembers(): Collection
    {
        return $this->model->newQuery()
            ->orderBy('sort_order')
            ->get();
    }
}
