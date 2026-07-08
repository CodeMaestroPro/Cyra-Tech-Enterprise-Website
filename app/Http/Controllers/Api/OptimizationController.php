<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RunOptimizationActionRequest;
use App\Services\TestingOptimizationService;
use Illuminate\Http\JsonResponse;

class OptimizationController extends Controller
{
    public function __construct(
        private readonly TestingOptimizationService $testingOptimizationService,
    ) {
    }

    public function dashboard(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->testingOptimizationService->getDashboard(),
        ]);
    }

    public function runAction(RunOptimizationActionRequest $request): JsonResponse
    {
        $result = $this->testingOptimizationService->runAction(
            $request->validated()['action'],
            $request->user(),
        );

        if (! ($result['success'] ?? false)) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Optimization action failed.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}
