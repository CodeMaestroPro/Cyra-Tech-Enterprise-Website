<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\HomepageService;
use Illuminate\Http\JsonResponse;

class HomepageController extends Controller
{
    public function __construct(
        private readonly HomepageService $homepageService,
    ) {
    }

    public function show(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->homepageService->getHomepage(),
        ]);
    }
}
