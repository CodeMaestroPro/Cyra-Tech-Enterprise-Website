<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityProgram extends Model
{
    protected $fillable = [
        'slug',
        'category',
        'title',
        'tagline',
        'summary',
        'description',
        'benefits',
        'activities',
        'membership',
        'schedule',
        'badge',
        'icon',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'benefits' => 'array',
            'activities' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }
}
