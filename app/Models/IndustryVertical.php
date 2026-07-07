<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndustryVertical extends Model
{
    protected $fillable = [
        'slug',
        'category',
        'title',
        'tagline',
        'summary',
        'description',
        'challenges',
        'capabilities',
        'compliance',
        'icon',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'challenges' => 'array',
            'capabilities' => 'array',
            'compliance' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }
}
