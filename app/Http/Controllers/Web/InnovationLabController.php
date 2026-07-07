<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\InnovationLabService;
use Illuminate\View\View;

class InnovationLabController extends Controller
{
    public function __construct(
        private readonly InnovationLabService $innovationLabService,
    ) {
    }

    public function index(): View
    {
        return view('innovation-lab.index', [
            'catalog' => $this->innovationLabService->getInnovationLab(),
        ]);
    }

    public function show(string $slug): View
    {
        $initiative = $this->innovationLabService->getInitiative($slug);

        abort_if($initiative === null, 404);

        return view('innovation-lab.show', [
            'initiative' => $initiative,
            'catalog' => $this->innovationLabService->getInnovationLab(),
        ]);
    }
}
