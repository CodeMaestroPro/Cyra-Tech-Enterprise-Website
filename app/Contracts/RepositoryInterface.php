<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function all(array $columns = ['*']): Collection;

    public function find(int|string $id, array $columns = ['*']): ?Model;

    public function create(array $attributes): Model;

    public function update(int|string $id, array $attributes): bool;

    public function delete(int|string $id): bool;
}
