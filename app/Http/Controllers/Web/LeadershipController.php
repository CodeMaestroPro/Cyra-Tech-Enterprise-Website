<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\LeadershipService;
use Illuminate\View\View;

class LeadershipController extends Controller
{
    public function __construct(
        private readonly LeadershipService $leadershipService,
    ) {
    }

    public function __invoke(): View
    {
        return view('leadership.index', [
            'leadership' => $this->leadershipService->getLeadership(),
        ]);
    }
}
