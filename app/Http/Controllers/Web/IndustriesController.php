<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\IndustryService;
use Illuminate\View\View;

class IndustriesController extends Controller
{
    public function __construct(
        private readonly IndustryService $industryService,
    ) {
    }

    public function index(): View
    {
        return view('industries.index', [
            'catalog' => $this->industryService->getIndustries(),
        ]);
    }

    public function show(string $slug): View
    {
        $vertical = $this->industryService->getIndustry($slug);

        abort_if($vertical === null, 404);

        return view('industries.show', [
            'vertical' => $vertical,
            'catalog' => $this->industryService->getIndustries(),
        ]);
    }
}
