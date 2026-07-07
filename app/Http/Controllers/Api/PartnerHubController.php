<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PartnerHubService;
use Illuminate\Http\JsonResponse;

class PartnerHubController extends Controller
{
    public function __construct(
        private readonly PartnerHubService $partnerHubService,
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->partnerHubService->getPartnerHub(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $program = $this->partnerHubService->getProgram($slug);

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
