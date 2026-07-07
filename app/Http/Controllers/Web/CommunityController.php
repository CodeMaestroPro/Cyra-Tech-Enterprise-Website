<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CommunityService;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public function __construct(
        private readonly CommunityService $communityService,
    ) {
    }

    public function index(): View
    {
        return view('community.index', [
            'catalog' => $this->communityService->getCommunity(),
        ]);
    }

    public function show(string $slug): View
    {
        $program = $this->communityService->getProgram($slug);

        abort_if($program === null, 404);

        return view('community.show', [
            'program' => $program,
            'catalog' => $this->communityService->getCommunity(),
        ]);
    }
}
