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

    /**
     * @return Collection<int, ClientAccount>
     */
    public function getAllAccounts(): Collection
    {
        return $this->model->newQuery()
            ->orderBy('name')
            ->get();
    }

    public function findBySlug(string $slug): ?ClientAccount
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->first();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createAccount(array $attributes): ClientAccount
    {
        return $this->model->newQuery()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateAccount(ClientAccount $account, array $attributes): ClientAccount
    {
        $account->update($attributes);

        return $account->refresh();
    }

    public function deleteAccount(ClientAccount $account): void
    {
        $account->delete();
    }
}
