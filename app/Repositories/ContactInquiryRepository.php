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
}
