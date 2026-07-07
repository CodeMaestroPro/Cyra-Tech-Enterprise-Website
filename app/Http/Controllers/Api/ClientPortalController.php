<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClientPortalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientPortalController extends Controller
{
    public function __construct(
        private readonly ClientPortalService $clientPortalService,
    ) {
    }

    public function show(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->clientPortalService->getPortal(),
        ]);
    }

    public function dashboard(Request $request): JsonResponse
    {
        $dashboard = $this->clientPortalService->getDashboard($request->user());

        if ($dashboard === null) {
            return response()->json([
                'success' => false,
                'message' => 'Client account not assigned.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $dashboard,
        ]);
    }

    public function showEngagement(Request $request, string $slug): JsonResponse
    {
        $engagement = $this->clientPortalService->getEngagement($request->user(), $slug);

        if ($engagement === null) {
            return response()->json([
                'success' => false,
                'message' => 'Engagement not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $engagement,
        ]);
    }
}
