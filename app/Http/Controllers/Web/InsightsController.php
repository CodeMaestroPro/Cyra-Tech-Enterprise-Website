<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\InsightService;
use Illuminate\View\View;

class InsightsController extends Controller
{
    public function __construct(
        private readonly InsightService $insightService,
    ) {
    }

    public function index(): View
    {
        return view('insights.index', [
            'catalog' => $this->insightService->getInsights(),
        ]);
    }

    public function show(string $slug): View
    {
        $article = $this->insightService->getArticle($slug);

        abort_if($article === null, 404);

        return view('insights.show', [
            'article' => $article,
            'catalog' => $this->insightService->getInsights(),
        ]);
    }
}
