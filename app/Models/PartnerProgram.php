<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerProgram extends Model
{
    protected $fillable = [
        'slug',
        'category',
        'title',
        'partner_type',
        'region',
        'engagement_model',
        'tagline',
        'summary',
        'description',
        'benefits',
        'requirements',
        'enablement',
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
            'requirements' => 'array',
            'enablement' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }
}
