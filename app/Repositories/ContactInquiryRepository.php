<?php

namespace App\Repositories;

use App\Models\ContactInquiry;

class ContactInquiryRepository extends BaseRepository
{
    public function __construct(ContactInquiry $model)
    {
        parent::__construct($model);
    }

    public function createInquiry(array $attributes): ContactInquiry
    {
        return $this->model->newQuery()->create($attributes);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, ContactInquiry>
     */
    public function getAllOrdered()
    {
        return $this->model->newQuery()
            ->orderByDesc('created_at')
            ->get();
    }

    public function findByReference(string $reference): ?ContactInquiry
    {
        return $this->model->newQuery()
            ->where('reference', $reference)
            ->first();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateInquiry(ContactInquiry $inquiry, array $attributes): ContactInquiry
    {
        $inquiry->update($attributes);

        return $inquiry->refresh();
    }

    public function deleteInquiry(ContactInquiry $inquiry): void
    {
        $inquiry->delete();
    }
}
