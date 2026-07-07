<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientAccount extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'industry',
        'region',
        'account_manager',
        'support_email',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function engagements(): HasMany
    {
        return $this->hasMany(ClientEngagement::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
