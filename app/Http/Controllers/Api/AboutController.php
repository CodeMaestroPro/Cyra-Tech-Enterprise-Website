<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AboutService;
use Illuminate\Http\JsonResponse;

class AboutController extends Controller
{
    public function __construct(
        private readonly AboutService $aboutService,
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->aboutService->getCatalog(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $page = $this->aboutService->getPage($slug);

        if ($page === null) {
            return response()->json([
                'success' => false,
                'message' => 'About page not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $page,
        ]);
    }
}
