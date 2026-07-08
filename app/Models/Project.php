<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'reference',
        'name',
        'client_name',
        'description',
        'status',
        'phase',
        'priority',
        'progress',
        'budget',
        'start_date',
        'due_date',
        'project_manager_id',
        'client_engagement_id',
        'crm_lead_id',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'progress' => 'integer',
            'budget' => 'decimal:2',
            'start_date' => 'date',
            'due_date' => 'date',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function clientEngagement(): BelongsTo
    {
        return $this->belongsTo(ClientEngagement::class);
    }

    public function crmLead(): BelongsTo
    {
        return $this->belongsTo(CrmLead::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class)->orderBy('sort_order')->orderBy('id');
    }
}
