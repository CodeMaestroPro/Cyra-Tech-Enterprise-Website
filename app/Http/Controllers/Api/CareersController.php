<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CareerService;
use Illuminate\Http\JsonResponse;

class CareersController extends Controller
{
    public function __construct(
        private readonly CareerService $careerService,
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->careerService->getCareers(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $opening = $this->careerService->getOpening($slug);

        if ($opening === null) {
            return response()->json([
                'success' => false,
                'message' => 'Opening not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $opening,
        ]);
    }
}
