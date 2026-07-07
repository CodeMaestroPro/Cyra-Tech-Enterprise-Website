<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InnovationInitiative extends Model
{
    protected $fillable = [
        'slug',
        'category',
        'title',
        'tagline',
        'summary',
        'description',
        'focus_areas',
        'deliverables',
        'timeline',
        'badge',
        'icon',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'focus_areas' => 'array',
            'deliverables' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }
}
