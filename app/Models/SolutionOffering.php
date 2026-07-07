<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolutionOffering extends Model
{
    protected $fillable = [
        'slug',
        'category',
        'title',
        'summary',
        'description',
        'capabilities',
        'outcomes',
        'icon',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'capabilities' => 'array',
            'outcomes' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }
}
