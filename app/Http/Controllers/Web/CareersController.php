<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CareerService;
use Illuminate\View\View;

class CareersController extends Controller
{
    public function __construct(
        private readonly CareerService $careerService,
    ) {
    }

    public function index(): View
    {
        return view('careers.index', [
            'catalog' => $this->careerService->getCareers(),
        ]);
    }

    public function show(string $slug): View
    {
        $opening = $this->careerService->getOpening($slug);

        abort_if($opening === null, 404);

        return view('careers.show', [
            'opening' => $opening,
            'catalog' => $this->careerService->getCareers(),
        ]);
    }
}
