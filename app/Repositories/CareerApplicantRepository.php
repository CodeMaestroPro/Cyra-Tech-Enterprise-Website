<?php

namespace App\Repositories;

use App\Models\CareerApplicant;
use Illuminate\Database\Eloquent\Collection;

class CareerApplicantRepository extends BaseRepository
{
    public function __construct(CareerApplicant $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, CareerApplicant>
     */
    public function getAllApplicants(): Collection
    {
        return $this->model->newQuery()
            ->orderByDesc('applied_at')
            ->orderByDesc('id')
            ->get();
    }

    public function findByReference(string $reference): ?CareerApplicant
    {
        return $this->model->newQuery()
            ->where('reference', $reference)
            ->first();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateApplicant(CareerApplicant $applicant, array $attributes): CareerApplicant
    {
        $applicant->update($attributes);

        return $applicant->refresh();
    }

    public function deleteApplicant(CareerApplicant $applicant): void
    {
        $applicant->delete();
    }
}
