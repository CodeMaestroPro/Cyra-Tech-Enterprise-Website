<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\InsightService;
use Illuminate\Http\JsonResponse;

class InsightsController extends Controller
{
    public function __construct(
        private readonly InsightService $insightService,
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->insightService->getInsights(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $article = $this->insightService->getArticle($slug);

        if ($article === null) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $article,
        ]);
    }
}
