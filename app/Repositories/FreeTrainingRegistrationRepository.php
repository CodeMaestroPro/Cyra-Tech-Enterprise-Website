<?php

namespace App\Repositories;

use App\Models\FreeTrainingRegistration;

class FreeTrainingRegistrationRepository extends BaseRepository
{
    public function __construct(FreeTrainingRegistration $model)
    {
        parent::__construct($model);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createRegistration(array $attributes): FreeTrainingRegistration
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function findByReference(string $reference): ?FreeTrainingRegistration
    {
        return $this->model->newQuery()
            ->whereRaw('UPPER(reference) = ?', [strtoupper(trim($reference))])
            ->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, FreeTrainingRegistration>
     */
    public function getAllOrdered()
    {
        return $this->model->newQuery()
            ->orderByDesc('created_at')
            ->get();
    }
}
