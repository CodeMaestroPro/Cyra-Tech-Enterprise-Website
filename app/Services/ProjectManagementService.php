<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\User;
use App\Repositories\ProjectRepository;
use App\Repositories\ProjectTaskRepository;
use Illuminate\Support\Str;

class ProjectManagementService extends BaseService
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
        private readonly ProjectTaskRepository $projectTaskRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getPortfolio(?string $status = null, ?string $phase = null): array
    {
        $projects = $this->projectRepository
            ->getActiveProjects($status, $phase)
            ->map(fn (Project $project) => $this->formatProject($project))
            ->values()
            ->all();

        return [
            'summary' => $this->buildSummary($projects),
            'statuses' => config('cyra.project_management.statuses', []),
            'phases' => config('cyra.project_management.phases', []),
            'priorities' => config('cyra.project_management.priorities', []),
            'task_statuses' => config('cyra.project_management.task_statuses', []),
            'projects' => $projects,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getTaskBoard(?string $status = null): array
    {
        $tasks = $this->projectTaskRepository
            ->getActiveTasks($status)
            ->map(fn (ProjectTask $task) => $this->formatTask($task))
            ->values()
            ->all();

        return [
            'summary' => [
                'total' => count($tasks),
                'pending' => collect($tasks)->where('status', 'pending')->count(),
                'in_progress' => collect($tasks)->where('status', 'in-progress')->count(),
                'completed' => collect($tasks)->where('status', 'completed')->count(),
                'overdue' => collect($tasks)->filter(fn (array $task) => $task['is_overdue'] ?? false)->count(),
            ],
            'task_statuses' => config('cyra.project_management.task_statuses', []),
            'tasks' => $tasks,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getProject(string $reference): ?array
    {
        $project = $this->projectRepository->findByReference($reference);

        if ($project === null) {
            return null;
        }

        return $this->formatProject($project, detailed: true);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createProject(array $data, User $user): array
    {
        $project = $this->projectRepository->createProject([
            'reference' => $this->generateProjectReference(),
            'name' => $data['name'],
            'client_name' => $data['client_name'] ?? null,
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'planning',
            'phase' => $data['phase'] ?? 'discovery',
            'priority' => $data['priority'] ?? 'medium',
            'progress' => $data['progress'] ?? 0,
            'budget' => $data['budget'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'project_manager_id' => $data['project_manager_id'] ?? $user->id,
            'client_engagement_id' => $data['client_engagement_id'] ?? null,
            'crm_lead_id' => $data['crm_lead_id'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => true,
        ]);

        return $this->formatProject($project->load(['manager', 'tasks.assignee']), detailed: true);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateProject(string $reference, array $data): ?array
    {
        $project = $this->projectRepository->findByReference($reference);

        if ($project === null) {
            return null;
        }

        $project = $this->projectRepository->updateProject($project, [
            'name' => $data['name'],
            'client_name' => $data['client_name'] ?? null,
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
            'phase' => $data['phase'],
            'priority' => $data['priority'],
            'progress' => $data['progress'] ?? $project->progress,
            'budget' => $data['budget'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'project_manager_id' => $data['project_manager_id'] ?? null,
            'client_engagement_id' => $data['client_engagement_id'] ?? null,
            'crm_lead_id' => $data['crm_lead_id'] ?? null,
            'sort_order' => $data['sort_order'] ?? $project->sort_order,
            'is_active' => $data['is_active'] ?? $project->is_active,
        ]);

        return $this->formatProject($project, detailed: true);
    }

    public function updateProgress(string $reference, int $progress): ?array
    {
        $project = $this->projectRepository->findByReference($reference);

        if ($project === null) {
            return null;
        }

        $status = $progress >= 100 ? 'completed' : ($project->status === 'completed' ? 'in-progress' : $project->status);

        $project = $this->projectRepository->updateProject($project, [
            'progress' => min(100, max(0, $progress)),
            'status' => $status,
        ]);

        return $this->formatProject($project, detailed: true);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createTask(string $projectReference, array $data): ?array
    {
        $project = $this->projectRepository->findByReference($projectReference);

        if ($project === null) {
            return null;
        }

        $task = $this->projectTaskRepository->createTask([
            'reference' => $this->generateTaskReference(),
            'project_id' => $project->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'priority' => $data['priority'] ?? 'medium',
            'assigned_to' => $data['assigned_to'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => true,
        ]);

        $this->syncProjectProgress($project->fresh(['tasks']));

        return $this->formatTask($task->load(['project', 'assignee']), detailed: true);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateTask(string $reference, array $data): ?array
    {
        $task = $this->projectTaskRepository->findByReference($reference);

        if ($task === null) {
            return null;
        }

        $task = $this->projectTaskRepository->updateTask($task, [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
            'priority' => $data['priority'],
            'assigned_to' => $data['assigned_to'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'sort_order' => $data['sort_order'] ?? $task->sort_order,
            'is_active' => $data['is_active'] ?? $task->is_active,
        ]);

        $this->syncProjectProgress($task->project->fresh(['tasks']));

        return $this->formatTask($task, detailed: true);
    }

    /**
     * @param  array<string, mixed>  $seed
     */
    public function seedProject(array $seed, ?User $manager = null): Project
    {
        return Project::query()->updateOrCreate(
            ['reference' => $seed['reference']],
            [
                'name' => $seed['name'],
                'client_name' => $seed['client_name'] ?? null,
                'description' => $seed['description'] ?? null,
                'status' => $seed['status'] ?? 'planning',
                'phase' => $seed['phase'] ?? 'discovery',
                'priority' => $seed['priority'] ?? 'medium',
                'progress' => $seed['progress'] ?? 0,
                'budget' => $seed['budget'] ?? null,
                'start_date' => isset($seed['start_date']) ? $seed['start_date'] : now()->subDays($seed['started_days_ago'] ?? 30)->toDateString(),
                'due_date' => isset($seed['due_date']) ? $seed['due_date'] : now()->addDays($seed['due_in_days'] ?? 60)->toDateString(),
                'project_manager_id' => $manager?->id,
                'sort_order' => $seed['sort_order'] ?? 0,
                'is_active' => true,
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $seed
     */
    public function seedTask(Project $project, array $seed): ProjectTask
    {
        return ProjectTask::query()->updateOrCreate(
            ['reference' => $seed['reference']],
            [
                'project_id' => $project->id,
                'title' => $seed['title'],
                'description' => $seed['description'] ?? null,
                'status' => $seed['status'] ?? 'pending',
                'priority' => $seed['priority'] ?? 'medium',
                'due_date' => isset($seed['due_date'])
                    ? $seed['due_date']
                    : now()->addDays($seed['due_in_days'] ?? 7)->toDateString(),
                'sort_order' => $seed['sort_order'] ?? 0,
                'is_active' => true,
            ],
        );
    }

    /**
     * @param  list<array<string, mixed>>  $projects
     * @return array<string, mixed>
     */
    private function buildSummary(array $projects): array
    {
        $collection = collect($projects);

        return [
            'total' => $collection->count(),
            'in_progress' => $collection->whereIn('status', ['in-progress', 'planning'])->count(),
            'completed' => $collection->where('status', 'completed')->count(),
            'on_hold' => $collection->where('status', 'on-hold')->count(),
            'average_progress' => $collection->count() > 0
                ? (int) round($collection->avg('progress'))
                : 0,
            'open_tasks' => $collection->sum('open_tasks_count'),
        ];
    }

    private function syncProjectProgress(Project $project): void
    {
        $tasks = $project->tasks()->where('is_active', true)->get();

        if ($tasks->isEmpty()) {
            return;
        }

        $progress = (int) round($tasks->where('status', 'completed')->count() / $tasks->count() * 100);

        $project->update([
            'progress' => $progress,
            'status' => $progress >= 100 ? 'completed' : ($project->status === 'completed' ? 'in-progress' : $project->status),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function formatProject(Project $project, bool $detailed = false): array
    {
        $formatted = [
            'reference' => $project->reference,
            'name' => $project->name,
            'client_name' => $project->client_name,
            'status' => $project->status,
            'status_label' => $this->statusLabel($project->status),
            'status_variant' => $this->statusVariant($project->status),
            'phase' => $project->phase,
            'phase_label' => $this->phaseLabel($project->phase),
            'priority' => $project->priority,
            'priority_label' => ucfirst($project->priority),
            'progress' => $project->progress,
            'budget' => $project->budget !== null ? (float) $project->budget : null,
            'budget_label' => $this->formatCurrency($project->budget),
            'start_date' => $project->start_date?->toDateString(),
            'due_date' => $project->due_date?->toDateString(),
            'manager' => $project->manager?->name,
            'project_manager_id' => $project->project_manager_id,
            'open_tasks_count' => $project->open_tasks_count ?? $project->tasks?->where('is_active', true)->where('status', '!=', 'completed')->count(),
            'updated_at' => $project->updated_at?->toIso8601String(),
        ];

        if ($detailed) {
            $formatted['description'] = $project->description;
            $formatted['sort_order'] = $project->sort_order;
            $formatted['is_active'] = $project->is_active;
            $formatted['client_engagement_id'] = $project->client_engagement_id;
            $formatted['client_engagement_title'] = $project->clientEngagement?->title;
            $formatted['crm_lead_id'] = $project->crm_lead_id;
            $formatted['crm_lead_reference'] = $project->crmLead?->reference;
            $formatted['tasks'] = $project->tasks
                ?->where('is_active', true)
                ->map(fn (ProjectTask $task) => $this->formatTask($task, detailed: true))
                ->values()
                ->all() ?? [];
        }

        return $formatted;
    }

    /**
     * @return array<string, mixed>
     */
    private function formatTask(ProjectTask $task, bool $detailed = false): array
    {
        $formatted = [
            'reference' => $task->reference,
            'title' => $task->title,
            'status' => $task->status,
            'status_label' => $this->taskStatusLabel($task->status),
            'priority' => $task->priority,
            'priority_label' => ucfirst($task->priority),
            'assignee' => $task->assignee?->name,
            'assigned_to' => $task->assigned_to,
            'due_date' => $task->due_date?->toDateString(),
            'is_overdue' => $task->due_date !== null
                && $task->due_date->isPast()
                && $task->status !== 'completed',
            'project_reference' => $task->project?->reference,
            'project_name' => $task->project?->name,
        ];

        if ($detailed) {
            $formatted['description'] = $task->description;
            $formatted['sort_order'] = $task->sort_order;
            $formatted['is_active'] = $task->is_active;
            $formatted['project_id'] = $task->project_id;
        }

        return $formatted;
    }

    private function generateProjectReference(): string
    {
        return 'PRJ-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));
    }

    private function generateTaskReference(): string
    {
        return 'TSK-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));
    }

    private function statusLabel(string $status): string
    {
        return collect(config('cyra.project_management.statuses', []))
            ->firstWhere('slug', $status)['label'] ?? ucfirst(str_replace('-', ' ', $status));
    }

    private function statusVariant(string $status): string
    {
        return collect(config('cyra.project_management.statuses', []))
            ->firstWhere('slug', $status)['variant'] ?? 'primary';
    }

    private function phaseLabel(string $phase): string
    {
        return collect(config('cyra.project_management.phases', []))
            ->firstWhere('slug', $phase)['label'] ?? ucfirst(str_replace('-', ' ', $phase));
    }

    private function taskStatusLabel(string $status): string
    {
        return collect(config('cyra.project_management.task_statuses', []))
            ->firstWhere('slug', $status)['label'] ?? ucfirst(str_replace('-', ' ', $status));
    }

    private function formatCurrency(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return '₦'.number_format((float) $value, 0);
    }
}
