<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->newQuery()->where('email', $email)->first();
    }

    public function markLastLogin(User $user): void
    {
        $user->forceFill(['last_login_at' => now()])->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    public function getAllWithRoles()
    {
        return $this->model->newQuery()
            ->with('roles')
            ->orderBy('name')
            ->get();
    }

    public function findWithRoles(int $id): ?User
    {
        return $this->model->newQuery()
            ->with('roles')
            ->find($id);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createUser(array $attributes): User
    {
        return $this->model->newQuery()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateUser(User $user, array $attributes): User
    {
        $user->update($attributes);

        return $user->refresh();
    }
}
