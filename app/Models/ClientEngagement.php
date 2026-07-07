<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientEngagement extends Model
{
    protected $fillable = [
        'client_account_id',
        'slug',
        'portfolio_slug',
        'title',
        'status',
        'phase',
        'progress',
        'tagline',
        'summary',
        'description',
        'milestones',
        'deliverables',
        'team',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'milestones' => 'array',
            'deliverables' => 'array',
            'team' => 'array',
            'progress' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(ClientAccount::class, 'client_account_id');
    }
}
