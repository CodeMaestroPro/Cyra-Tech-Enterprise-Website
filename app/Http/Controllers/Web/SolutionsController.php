<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\SolutionService;
use Illuminate\View\View;

class SolutionsController extends Controller
{
    public function __construct(
        private readonly SolutionService $solutionService,
    ) {
    }

    public function index(): View
    {
        return view('solutions.index', [
            'solutions' => $this->solutionService->getSolutions(),
        ]);
    }

    public function show(string $slug): View
    {
        $offering = $this->solutionService->getOffering($slug);

        abort_if($offering === null, 404);

        return view('solutions.show', [
            'offering' => $offering,
            'solutions' => $this->solutionService->getSolutions(),
        ]);
    }
}
