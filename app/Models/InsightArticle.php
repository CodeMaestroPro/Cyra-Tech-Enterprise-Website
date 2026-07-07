<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsightArticle extends Model
{
    protected $fillable = [
        'slug',
        'category',
        'title',
        'tagline',
        'summary',
        'description',
        'author',
        'read_time',
        'topics',
        'takeaways',
        'published_label',
        'badge',
        'icon',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'topics' => 'array',
            'takeaways' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }
}
