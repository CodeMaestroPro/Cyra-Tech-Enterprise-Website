<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInsightArticleRequest;
use App\Http\Requests\UpdateInsightArticleRequest;
use App\Services\InsightService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InsightsController extends Controller
{
    public function __construct(
        private readonly InsightService $insightService,
    ) {
    }

    public function index(Request $request): View
    {
        return view('admin.insights.index', [
            'insights' => $this->insightService->getAdminWorkspace(),
            'categoryFilter' => $request->query('category', 'all'),
        ]);
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->hasPermission('cms.create') ?? false, 403);

        return view('admin.insights.create', [
            'options' => $this->insightService->getFormOptions(),
        ]);
    }

    public function store(StoreInsightArticleRequest $request): RedirectResponse
    {
        $article = $this->insightService->createArticle($request->validated());

        return redirect()
            ->route('admin.insights.edit', $article['slug'])
            ->with('success', 'Insight article created successfully.');
    }

    public function edit(string $slug): View|RedirectResponse
    {
        abort_unless(auth()->user()?->hasPermission('modules.view') ?? false, 403);

        $article = $this->insightService->getAdminArticle($slug);

        if ($article === null) {
            return redirect()
                ->route('admin.insights.index')
                ->with('error', 'Insight article not found.');
        }

        return view('admin.insights.edit', [
            'article' => $article,
            'options' => $this->insightService->getFormOptions(),
        ]);
    }

    public function update(UpdateInsightArticleRequest $request, string $slug): RedirectResponse
    {
        $article = $this->insightService->updateArticle($slug, $request->validated());

        if ($article === null) {
            return redirect()
                ->route('admin.insights.index')
                ->with('error', 'Insight article not found.');
        }

        return redirect()
            ->route('admin.insights.edit', $article['slug'])
            ->with('success', 'Insight article updated successfully.');
    }

    public function destroy(string $slug): RedirectResponse
    {
        abort_unless(auth()->user()?->hasPermission('cms.delete') ?? false, 403);

        if (! $this->insightService->deleteArticle($slug)) {
            return redirect()
                ->route('admin.insights.index')
                ->with('error', 'Insight article not found.');
        }

        return redirect()
            ->route('admin.insights.index')
            ->with('success', 'Insight article deleted successfully.');
    }
}
