<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PlatformService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly PlatformService $platformService,
    ) {
    }

    public function __invoke(): View
    {
        $user = auth()->user();

        return view('admin.dashboard.index', [
            'user' => $user,
            'platform' => $this->platformService->getStatus(),
            'modules' => $this->platformService->getModules(),
        ]);
    }
}
