<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PlatformService;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    public function __construct(
        private readonly PlatformService $platformService,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $health = $this->platformService->getHealth();
        $statusCode = collect($health['checks'])->every(fn (bool $check) => $check) ? 200 : 503;

        return response()->json([
            'success' => $statusCode === 200,
            'data' => $health,
        ], $statusCode);
    }
}
