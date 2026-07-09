<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerApplicant extends Model
{
    protected $fillable = [
        'reference',
        'opening_slug',
        'name',
        'email',
        'phone',
        'location',
        'linkedin_url',
        'portfolio_url',
        'cover_letter',
        'resume_filename',
        'status',
        'source',
        'notes',
        'applied_at',
    ];

    protected function casts(): array
    {
        return [
            'applied_at' => 'datetime',
        ];
    }
}
