<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NavigationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function __construct(
        private readonly NavigationService $navigationService,
    ) {
    }

    public function publicNavigation(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->navigationService->getPublicNavigation(),
        ]);
    }

    public function adminNavigation(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->navigationService->getAdminNavigation($request->user()),
        ]);
    }
}
