<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\StoreProjectTaskRequest;
use App\Http\Requests\UpdateProjectProgressRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Requests\UpdateProjectTaskRequest;
use App\Models\User;
use App\Services\ProjectManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectManagementController extends Controller
{
    public function __construct(
        private readonly ProjectManagementService $projectManagementService,
    ) {
    }

    public function index(Request $request): View
    {
        return view('admin.projects.index', [
            'portfolio' => $this->projectManagementService->getPortfolio(
                $request->query('status'),
                $request->query('phase'),
            ),
            'statusFilter' => $request->query('status', 'all'),
            'phaseFilter' => $request->query('phase', 'all'),
        ]);
    }

    public function tasks(Request $request): View
    {
        return view('admin.projects.tasks', [
            'board' => $this->projectManagementService->getTaskBoard($request->query('status')),
            'statusFilter' => $request->query('status', 'all'),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.create', [
            'options' => $this->formOptions(),
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = $this->projectManagementService->createProject(
            $request->validated(),
            $request->user(),
        );

        return redirect()
            ->route('admin.projects.edit', $project['reference'])
            ->with('success', 'Project created successfully.');
    }

    public function edit(string $reference): View|RedirectResponse
    {
        $project = $this->projectManagementService->getProject($reference);

        if ($project === null) {
            return redirect()
                ->route('admin.projects.index')
                ->with('error', 'Project not found.');
        }

        return view('admin.projects.edit', [
            'project' => $project,
            'options' => $this->formOptions(),
        ]);
    }

    public function update(UpdateProjectRequest $request, string $reference): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $project = $this->projectManagementService->updateProject($reference, $data);

        if ($project === null) {
            return redirect()
                ->route('admin.projects.index')
                ->with('error', 'Project not found.');
        }

        return redirect()
            ->route('admin.projects.edit', $project['reference'])
            ->with('success', 'Project updated successfully.');
    }

    public function updateProgress(UpdateProjectProgressRequest $request, string $reference): RedirectResponse
    {
        $project = $this->projectManagementService->updateProgress(
            $reference,
            (int) $request->validated()['progress'],
        );

        if ($project === null) {
            return redirect()
                ->route('admin.projects.index')
                ->with('error', 'Project not found.');
        }

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project progress updated to '.$project['progress'].'%.');
    }

    public function storeTask(StoreProjectTaskRequest $request, string $reference): RedirectResponse
    {
        $task = $this->projectManagementService->createTask($reference, $request->validated());

        if ($task === null) {
            return redirect()
                ->route('admin.projects.index')
                ->with('error', 'Project not found.');
        }

        return redirect()
            ->route('admin.projects.edit', $reference)
            ->with('success', 'Task added successfully.');
    }

    public function updateTask(UpdateProjectTaskRequest $request, string $reference, string $taskReference): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $task = $this->projectManagementService->updateTask($taskReference, $data);

        if ($task === null) {
            return redirect()
                ->route('admin.projects.edit', $reference)
                ->with('error', 'Task not found.');
        }

        return redirect()
            ->route('admin.projects.edit', $reference)
            ->with('success', 'Task updated successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(): array
    {
        return [
            'statuses' => config('cyra.project_management.statuses', []),
            'phases' => config('cyra.project_management.phases', []),
            'priorities' => config('cyra.project_management.priorities', []),
            'task_statuses' => config('cyra.project_management.task_statuses', []),
            'managers' => User::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->all(),
        ];
    }
}
