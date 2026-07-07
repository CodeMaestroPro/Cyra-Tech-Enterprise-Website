<?php

namespace App\Repositories;

use App\Models\ClientAccount;
use App\Models\ClientEngagement;
use Illuminate\Database\Eloquent\Collection;

class ClientAccountRepository extends BaseRepository
{
    public function __construct(ClientAccount $model)
    {
        parent::__construct($model);
    }

    public function findActiveBySlug(string $slug): ?ClientAccount
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
}
