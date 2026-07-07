<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOffering extends Model
{
    protected $fillable = [
        'slug',
        'category',
        'title',
        'tagline',
        'summary',
        'description',
        'badge',
        'features',
        'use_cases',
        'icon',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'use_cases' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }
}
