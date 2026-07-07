<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CommunityService;
use Illuminate\Http\JsonResponse;

class CommunityController extends Controller
{
    public function __construct(
        private readonly CommunityService $communityService,
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->communityService->getCommunity(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $program = $this->communityService->getProgram($slug);

        if ($program === null) {
            return response()->json([
                'success' => false,
                'message' => 'Program not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $program,
        ]);
    }
}
