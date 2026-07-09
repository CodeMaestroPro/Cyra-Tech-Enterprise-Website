<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MarketingService;
use Illuminate\View\View;

class MarketingController extends Controller
{
    public function __construct(
        private readonly MarketingService $marketingService,
    ) {
    }

    public function __invoke(): View
    {
        return view('admin.marketing.index', [
            'marketing' => $this->marketingService->getWorkspace(),
        ]);
    }
}
