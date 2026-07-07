<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PortfolioService;
use Illuminate\Http\JsonResponse;

class PortfolioController extends Controller
{
    public function __construct(
        private readonly PortfolioService $portfolioService,
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->portfolioService->getPortfolio(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $project = $this->portfolioService->getProject($slug);

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
}
