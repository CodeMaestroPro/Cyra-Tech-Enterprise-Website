<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadershipProfile extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'title',
        'tier',
        'bio',
        'focus_areas',
        'linkedin_url',
        'email',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'focus_areas' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }
}
