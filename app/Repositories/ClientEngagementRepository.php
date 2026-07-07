<?php

namespace App\Repositories;

use App\Models\ClientEngagement;
use Illuminate\Database\Eloquent\Collection;

class ClientEngagementRepository extends BaseRepository
{
    public function __construct(ClientEngagement $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, ClientEngagement>
     */
    public function getActiveEngagementsForAccount(int $accountId): Collection
    {
        return $this->model->newQuery()
            ->where('client_account_id', $accountId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function findActiveBySlugForAccount(int $accountId, string $slug): ?ClientEngagement
    {
        return $this->model->newQuery()
            ->where('client_account_id', $accountId)
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
}
