<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\InnovationLabService;
use Illuminate\Http\JsonResponse;

class InnovationLabController extends Controller
{
    public function __construct(
        private readonly InnovationLabService $innovationLabService,
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->innovationLabService->getInnovationLab(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $initiative = $this->innovationLabService->getInitiative($slug);

        if ($initiative === null) {
            return response()->json([
                'success' => false,
                'message' => 'Initiative not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $initiative,
        ]);
    }
}
