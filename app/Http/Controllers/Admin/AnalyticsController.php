<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function __construct(
        private readonly AnalyticsService $analyticsService,
    ) {
    }

    public function __invoke(Request $request): View
    {
        $rangeDays = (int) $request->query('range', config('cyra.analytics.default_range_days', 30));

        return view('admin.analytics.index', [
            'dashboard' => $this->analyticsService->getDashboard($rangeDays),
            'rangeDays' => $rangeDays,
            'rangeOptions' => config('cyra.analytics.range_options', [7, 14, 30]),
        ]);
    }
}
