<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioProject extends Model
{
    protected $fillable = [
        'slug',
        'category',
        'title',
        'client_name',
        'tagline',
        'summary',
        'description',
        'services',
        'outcomes',
        'metrics',
        'duration',
        'icon',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'services' => 'array',
            'outcomes' => 'array',
            'metrics' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }
}
