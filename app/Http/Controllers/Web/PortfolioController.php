<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\PortfolioService;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function __construct(
        private readonly PortfolioService $portfolioService,
    ) {
    }

    public function index(): View
    {
        return view('portfolio.index', [
            'catalog' => $this->portfolioService->getPortfolio(),
        ]);
    }

    public function show(string $slug): View
    {
        $project = $this->portfolioService->getProject($slug);

        abort_if($project === null, 404);

        return view('portfolio.show', [
            'project' => $project,
            'catalog' => $this->portfolioService->getPortfolio(),
        ]);
    }
}
