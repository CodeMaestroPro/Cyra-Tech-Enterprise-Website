<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\StoreProjectTaskRequest;
use App\Http\Requests\UpdateProjectProgressRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Requests\UpdateProjectTaskRequest;
use App\Services\ProjectManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectManagementController extends Controller
{
    public function __construct(
        private readonly ProjectManagementService $projectManagementService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->projectManagementService->getPortfolio(
                $request->query('status'),
                $request->query('phase'),
            ),
        ]);
    }

    public function tasks(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->projectManagementService->getTaskBoard($request->query('status')),
        ]);
    }

    public function show(string $reference): JsonResponse
    {
        $project = $this->projectManagementService->getProject($reference);

        if ($project === null) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $project,
        ]);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = $this->projectManagementService->createProject(
            $request->validated(),
            $request->user(),
        );

        return response()->json([
            'success' => true,
            'data' => $project,
        ], 201);
    }

    public function update(UpdateProjectRequest $request, string $reference): JsonResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $project = $this->projectManagementService->updateProject($reference, $data);

        if ($project === null) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $project,
        ]);
    }

    public function updateProgress(UpdateProjectProgressRequest $request, string $reference): JsonResponse
    {
        $project = $this->projectManagementService->updateProgress(
            $reference,
            (int) $request->validated()['progress'],
        );

        if ($project === null) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $project,
        ]);
    }

    public function storeTask(StoreProjectTaskRequest $request, string $reference): JsonResponse
    {
        $task = $this->projectManagementService->createTask($reference, $request->validated());

        if ($task === null) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $task,
        ], 201);
    }

    public function updateTask(UpdateProjectTaskRequest $request, string $reference, string $taskReference): JsonResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $task = $this->projectManagementService->updateTask($taskReference, $data);

        if ($task === null) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $task,
        ]);
    }
}
