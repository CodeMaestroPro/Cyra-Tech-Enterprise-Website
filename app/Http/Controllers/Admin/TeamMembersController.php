<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TeamMembersService;
use Illuminate\View\View;

class TeamMembersController extends Controller
{
    public function __construct(
        private readonly TeamMembersService $teamMembersService,
    ) {
    }

    public function __invoke(): View
    {
        return view('admin.team-members.index', [
            'team' => $this->teamMembersService->getWorkspace(),
        ]);
    }
}
