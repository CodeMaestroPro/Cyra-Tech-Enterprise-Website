<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignSystemResource;
use App\Services\DesignSystemService;
use Illuminate\Http\JsonResponse;

class DesignSystemController extends Controller
{
    public function __construct(
        private readonly DesignSystemService $designSystemService,
    ) {
    }

    public function tokens(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new DesignSystemResource($this->designSystemService->getTokens()),
        ]);
    }

    public function catalog(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new DesignSystemResource($this->designSystemService->getCatalog()),
        ]);
    }
}
