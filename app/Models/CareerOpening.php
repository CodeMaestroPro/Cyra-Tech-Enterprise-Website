<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerOpening extends Model
{
    protected $fillable = [
        'slug',
        'category',
        'title',
        'department',
        'location',
        'work_type',
        'tagline',
        'summary',
        'description',
        'responsibilities',
        'requirements',
        'experience_level',
        'badge',
        'icon',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'responsibilities' => 'array',
            'requirements' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }
}
