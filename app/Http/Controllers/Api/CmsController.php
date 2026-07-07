<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function __construct(
        private readonly CmsService $cmsService,
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->cmsService->getPublicCatalog(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $page = $this->cmsService->getPublicPage($slug);

        if ($page === null) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $page,
        ]);
    }

    public function adminIndex(Request $request): JsonResponse
    {
        $status = $request->query('status', 'all');

        return response()->json([
            'success' => true,
            'data' => $this->cmsService->getAdminCatalog($status),
        ]);
    }

    public function adminShow(string $slug): JsonResponse
    {
        $page = $this->cmsService->getAdminPage($slug);

        if ($page === null) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $page,
        ]);
    }
}
