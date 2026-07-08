<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmLead extends Model
{
    protected $fillable = [
        'reference',
        'name',
        'email',
        'company',
        'phone',
        'job_title',
        'source',
        'pipeline_stage',
        'priority',
        'estimated_value',
        'notes',
        'assigned_to',
        'contact_inquiry_id',
        'last_contacted_at',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'estimated_value' => 'decimal:2',
            'last_contacted_at' => 'datetime',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function contactInquiry(): BelongsTo
    {
        return $this->belongsTo(ContactInquiry::class);
    }
}
