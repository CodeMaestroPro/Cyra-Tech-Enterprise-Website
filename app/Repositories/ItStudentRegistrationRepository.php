<?php

namespace App\Repositories;

use App\Models\ItStudentRegistration;

class ItStudentRegistrationRepository extends BaseRepository
{
    public function __construct(ItStudentRegistration $model)
    {
        parent::__construct($model);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createRegistration(array $attributes): ItStudentRegistration
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function findByReference(string $reference): ?ItStudentRegistration
    {
        return $this->model->newQuery()
            ->where('reference', $reference)
            ->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, ItStudentRegistration>
     */
    public function getAllOrdered()
    {
        return $this->model->newQuery()
            ->orderByDesc('created_at')
            ->get();
    }
}
