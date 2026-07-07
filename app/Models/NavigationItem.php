<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    protected $fillable = [
        'location',
        'group_label',
        'label',
        'route_name',
        'route_params',
        'url',
        'sort_order',
        'style',
        'permission',
        'is_active',
        'is_available',
        'opens_in_new_tab',
    ];

    protected function casts(): array
    {
        return [
            'route_params' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_available' => 'boolean',
            'opens_in_new_tab' => 'boolean',
        ];
    }
}
