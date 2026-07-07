<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\IndustryService;
use Illuminate\Http\JsonResponse;

class IndustriesController extends Controller
{
    public function __construct(
        private readonly IndustryService $industryService,
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->industryService->getIndustries(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $vertical = $this->industryService->getIndustry($slug);

        if ($vertical === null) {
            return response()->json([
                'success' => false,
                'message' => 'Industry not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $vertical,
        ]);
    }
}
