<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnalyticsEventRequest;
use App\Services\AnalyticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AnalyticsController extends Controller
{
    public function __construct(
        private readonly AnalyticsService $analyticsService,
    ) {
    }

    public function dashboard(Request $request): JsonResponse
    {
        $rangeDays = (int) $request->query('range', config('cyra.analytics.default_range_days', 30));

        return response()->json([
            'success' => true,
            'data' => $this->analyticsService->getDashboard($rangeDays),
        ]);
    }

    public function store(StoreAnalyticsEventRequest $request): JsonResponse
    {
        $sessionHash = hash('sha256', $request->session()->getId() ?: Str::uuid()->toString());

        $event = $this->analyticsService->trackEvent(
            $request->validated(),
            $request->user(),
            $sessionHash,
        );

        return response()->json([
            'success' => true,
            'data' => $event,
        ], 201);
    }
}
