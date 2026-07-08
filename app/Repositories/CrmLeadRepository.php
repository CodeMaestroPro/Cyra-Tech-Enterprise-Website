<?php

namespace App\Repositories;

use App\Models\CrmLead;
use Illuminate\Database\Eloquent\Collection;

class CrmLeadRepository extends BaseRepository
{
    public function __construct(CrmLead $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, CrmLead>
     */
    public function getActiveLeads(?string $stage = null): Collection
    {
        return $this->model->newQuery()
            ->with(['assignee', 'contactInquiry'])
            ->where('is_active', true)
            ->when($stage !== null && $stage !== 'all', fn ($query) => $query->where('pipeline_stage', $stage))
            ->orderBy('sort_order')
            ->orderByDesc('updated_at')
            ->get();
    }

    public function findByReference(string $reference): ?CrmLead
    {
        return $this->model->newQuery()
            ->with(['assignee', 'contactInquiry'])
            ->where('reference', $reference)
            ->first();
    }

    public function createLead(array $attributes): CrmLead
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function updateLead(CrmLead $lead, array $attributes): CrmLead
    {
        $lead->update($attributes);

        return $lead->fresh(['assignee', 'contactInquiry']);
    }
}
