<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformModule extends Model
{
    protected $fillable = [
        'module_id',
        'slug',
        'name',
        'status',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'module_id' => 'integer',
            'completed_at' => 'datetime',
        ];
    }
}
