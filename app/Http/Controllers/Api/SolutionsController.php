<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SolutionService;
use Illuminate\Http\JsonResponse;

class SolutionsController extends Controller
{
    public function __construct(
        private readonly SolutionService $solutionService,
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->solutionService->getSolutions(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $offering = $this->solutionService->getOffering($slug);

        if ($offering === null) {
            return response()->json([
                'success' => false,
                'message' => 'Solution offering not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $offering,
        ]);
    }
}
