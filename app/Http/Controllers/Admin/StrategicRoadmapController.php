<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StrategicRoadmapService;
use Illuminate\View\View;

class StrategicRoadmapController extends Controller
{
    public function __construct(
        private readonly StrategicRoadmapService $strategicRoadmapService,
    ) {
    }

    public function __invoke(): View
    {
        return view('admin.strategic-roadmap.index', [
            'roadmap' => $this->strategicRoadmapService->getDashboard(),
        ]);
    }
}
