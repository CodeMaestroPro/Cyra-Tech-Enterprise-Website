<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlatformModuleResource;
use App\Http\Resources\PlatformStatusResource;
use App\Services\PlatformService;
use Illuminate\Http\JsonResponse;

class PlatformController extends Controller
{
    public function __construct(
        private readonly PlatformService $platformService,
    ) {
    }

    public function status(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new PlatformStatusResource($this->platformService->getStatus()),
        ]);
    }

    public function modules(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => PlatformModuleResource::collection(
                collect($this->platformService->getModules())
            ),
        ]);
    }
}
