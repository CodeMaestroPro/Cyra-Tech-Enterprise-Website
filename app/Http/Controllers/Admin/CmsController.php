<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCmsPageRequest;
use App\Http\Requests\UpdateCmsPageRequest;
use App\Services\CmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmsController extends Controller
{
    public function __construct(
        private readonly CmsService $cmsService,
    ) {
    }

    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');

        return view('admin.cms.index', [
            'catalog' => $this->cmsService->getAdminCatalog($status),
            'statusFilter' => $status,
        ]);
    }

    public function create(): View
    {
        return view('admin.cms.create', [
            'templates' => config('cyra.cms.templates', []),
            'statuses' => config('cyra.cms.statuses', []),
        ]);
    }

    public function store(StoreCmsPageRequest $request): RedirectResponse
    {
        $page = $this->cmsService->createPage(
            $request->validated(),
            $request->user(),
        );

        return redirect()
            ->route('admin.cms.edit', $page['slug'])
            ->with('success', 'Page created successfully.');
    }

    public function edit(string $slug): View|RedirectResponse
    {
        $page = $this->cmsService->getAdminPage($slug);

        if ($page === null) {
            return redirect()
                ->route('admin.cms.index')
                ->with('error', 'Page not found.');
        }

        return view('admin.cms.edit', [
            'page' => $page,
            'templates' => config('cyra.cms.templates', []),
            'statuses' => config('cyra.cms.statuses', []),
        ]);
    }

    public function update(UpdateCmsPageRequest $request, string $slug): RedirectResponse
    {
        $page = $this->cmsService->updatePage($slug, $request->validated());

        if ($page === null) {
            return redirect()
                ->route('admin.cms.index')
                ->with('error', 'Page not found.');
        }

        return redirect()
            ->route('admin.cms.edit', $page['slug'])
            ->with('success', 'Page updated successfully.');
    }

    public function publish(string $slug): RedirectResponse
    {
        $page = $this->cmsService->publishPage($slug);

        if ($page === null) {
            return redirect()
                ->route('admin.cms.index')
                ->with('error', 'Page not found.');
        }

        return redirect()
            ->route('admin.cms.edit', $page['slug'])
            ->with('success', 'Page published successfully.');
    }

    public function unpublish(string $slug): RedirectResponse
    {
        $page = $this->cmsService->unpublishPage($slug);

        if ($page === null) {
            return redirect()
                ->route('admin.cms.index')
                ->with('error', 'Page not found.');
        }

        return redirect()
            ->route('admin.cms.edit', $page['slug'])
            ->with('success', 'Page moved back to draft.');
    }
}
