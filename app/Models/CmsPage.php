<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'template',
        'status',
        'eyebrow',
        'excerpt',
        'description',
        'content',
        'seo',
        'author_id',
        'published_at',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'seo' => 'array',
            'published_at' => 'datetime',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
