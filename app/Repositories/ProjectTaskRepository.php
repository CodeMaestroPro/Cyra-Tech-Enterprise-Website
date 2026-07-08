<?php

namespace App\Repositories;

use App\Models\ProjectTask;
use Illuminate\Database\Eloquent\Collection;

class ProjectTaskRepository extends BaseRepository
{
    public function __construct(ProjectTask $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection<int, ProjectTask>
     */
    public function getActiveTasks(?string $status = null): Collection
    {
        return $this->model->newQuery()
            ->with(['project', 'assignee'])
            ->where('is_active', true)
            ->when($status !== null && $status !== 'all', fn ($query) => $query->where('status', $status))
            ->orderBy('sort_order')
            ->orderBy('due_date')
            ->orderByDesc('updated_at')
            ->get();
    }

    public function findByReference(string $reference): ?ProjectTask
    {
        return $this->model->newQuery()
            ->with(['project', 'assignee'])
            ->where('reference', $reference)
            ->first();
    }

    public function createTask(array $attributes): ProjectTask
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function updateTask(ProjectTask $task, array $attributes): ProjectTask
    {
        $task->update($attributes);

        return $task->fresh(['project', 'assignee']);
    }
}
