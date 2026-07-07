<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    protected $fillable = [
        'slug',
        'route_name',
        'nav_label',
        'eyebrow',
        'title',
        'description',
        'content',
        'seo',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'seo' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
