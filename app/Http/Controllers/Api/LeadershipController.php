<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LeadershipService;
use Illuminate\Http\JsonResponse;

class LeadershipController extends Controller
{
    public function __construct(
        private readonly LeadershipService $leadershipService,
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->leadershipService->getLeadership(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $profile = $this->leadershipService->getProfile($slug);

        if ($profile === null) {
            return response()->json([
                'success' => false,
                'message' => 'Leadership profile not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $profile,
        ]);
    }
}
