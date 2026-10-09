<?php

namespace App\Repositories;

use App\Models\StudentRegistration;

class StudentRegistrationRepository extends BaseRepository
{
    public function __construct(StudentRegistration $model)
    {
        parent::__construct($model);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createRegistration(array $attributes): StudentRegistration
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function findByReference(string $reference): ?StudentRegistration
    {
        return $this->model->newQuery()
            ->whereRaw('UPPER(reference) = ?', [strtoupper(trim($reference))])
            ->first();
    }

    public function findByReferenceAndEmail(string $reference, string $email): ?StudentRegistration
    {
        return $this->model->newQuery()
            ->whereRaw('UPPER(reference) = ?', [strtoupper(trim($reference))])
            ->whereRaw('LOWER(email) = ?', [strtolower(trim($email))])
            ->first();
    }

    public function findByPaystackReference(string $paystackReference): ?StudentRegistration
    {
        return $this->model->newQuery()
            ->where(function ($query) use ($paystackReference): void {
                $query->where('paystack_reference', $paystackReference)
                    ->orWhere('balance_paystack_reference', $paystackReference);
            })
            ->first();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateRegistration(StudentRegistration $registration, array $attributes): StudentRegistration
    {
        $registration->fill($attributes);
        $registration->save();

        return $registration;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, StudentRegistration>
     */
    public function getAllOrdered()
    {
        return $this->model->newQuery()
            ->orderByDesc('created_at')
            ->get();
    }
}
