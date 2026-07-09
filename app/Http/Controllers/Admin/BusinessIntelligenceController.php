<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BusinessIntelligenceService;
use Illuminate\View\View;

class BusinessIntelligenceController extends Controller
{
    public function __construct(
        private readonly BusinessIntelligenceService $businessIntelligenceService,
    ) {
    }

    public function __invoke(): View
    {
        return view('admin.business-intelligence.index', [
            'bi' => $this->businessIntelligenceService->getDashboard(),
        ]);
    }
}
