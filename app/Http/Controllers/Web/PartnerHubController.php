<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\PartnerHubService;
use Illuminate\View\View;

class PartnerHubController extends Controller
{
    public function __construct(
        private readonly PartnerHubService $partnerHubService,
    ) {
    }

    public function index(): View
    {
        return view('partner-hub.index', [
            'catalog' => $this->partnerHubService->getPartnerHub(),
        ]);
    }

    public function show(string $slug): View
    {
        $program = $this->partnerHubService->getProgram($slug);

        abort_if($program === null, 404);

        return view('partner-hub.show', [
            'program' => $program,
            'catalog' => $this->partnerHubService->getPartnerHub(),
        ]);
    }
}
