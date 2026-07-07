<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\PlatformService;
use Illuminate\View\View;

class InitializationController extends Controller
{
    public function __construct(
        private readonly PlatformService $platformService,
    ) {
    }

    public function __invoke(): View
    {
        return view('initialization.index', [
            'platform' => $this->platformService->getStatus(),
            'modules' => $this->platformService->getModules(),
        ]);
    }
}
