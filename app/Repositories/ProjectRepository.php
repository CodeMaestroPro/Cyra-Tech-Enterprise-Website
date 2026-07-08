<?php

namespace App\Repositories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository extends BaseRepository
{
    public function __construct(Project $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, Project>
     */
    public function getActiveProjects(?string $status = null, ?string $phase = null): Collection
    {
        return $this->model->newQuery()
            ->with(['manager', 'tasks' => fn ($query) => $query->where('is_active', true)])
            ->withCount(['tasks as open_tasks_count' => fn ($query) => $query
                ->where('is_active', true)
                ->whereNotIn('status', ['completed'])])
            ->where('is_active', true)
            ->when($status !== null && $status !== 'all', fn ($query) => $query->where('status', $status))
            ->when($phase !== null && $phase !== 'all', fn ($query) => $query->where('phase', $phase))
            ->orderBy('sort_order')
            ->orderByDesc('updated_at')
            ->get();
    }

    public function findByReference(string $reference): ?Project
    {
        return $this->model->newQuery()
            ->with(['manager', 'clientEngagement.account', 'crmLead', 'tasks.assignee'])
            ->where('reference', $reference)
            ->first();
    }

    public function createProject(array $attributes): Project
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function updateProject(Project $project, array $attributes): Project
    {
        $project->update($attributes);

        return $project->fresh(['manager', 'clientEngagement.account', 'crmLead', 'tasks.assignee']);
    }
}
