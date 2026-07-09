<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCatalogItemRequest;
use App\Http\Requests\UpdateCatalogItemRequest;
use App\Services\CatalogAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogAdminController extends Controller
{
    public function __construct(
        private readonly CatalogAdminService $catalogAdminService,
    ) {
    }

    public function index(Request $request): View
    {
        $module = $this->resolveModule($request);

        return view('admin.catalog.index', [
            'module' => $module,
            'catalog' => $this->catalogAdminService->getAdminCatalog(
                $module,
                $request->query('category', 'all'),
            ),
            'categoryFilter' => $request->query('category', 'all'),
        ]);
    }

    public function create(Request $request): View
    {
        $module = $this->resolveModule($request);
        abort_unless(auth()->user()?->hasPermission('cms.create') ?? false, 403);

        return view('admin.catalog.create', [
            'module' => $module,
            'config' => $this->catalogAdminService->getModuleConfig($module),
            'options' => $this->catalogAdminService->getFormOptions($module),
        ]);
    }

    public function store(StoreCatalogItemRequest $request): RedirectResponse
    {
        $module = $this->resolveModule($request);
        $record = $this->catalogAdminService->createRecord($module, $request->validated());

        return redirect()
            ->route($this->catalogAdminService->routeName($module, 'edit'), $record['slug'])
            ->with('success', $this->catalogAdminService->getModuleConfig($module)['singular'].' created successfully.');
    }

    public function edit(Request $request, string $slug): View|RedirectResponse
    {
        $module = $this->resolveModule($request);
        $config = $this->catalogAdminService->getModuleConfig($module);
        $record = $this->catalogAdminService->getAdminRecord($module, $slug, detailed: true);

        if ($record === null) {
            return redirect()
                ->route($this->catalogAdminService->routeName($module, 'index'))
                ->with('error', $config['singular'].' not found.');
        }

        return view('admin.catalog.edit', [
            'module' => $module,
            'config' => $config,
            'record' => $record,
            'options' => $this->catalogAdminService->getFormOptions($module),
        ]);
    }

    public function update(UpdateCatalogItemRequest $request, string $slug): RedirectResponse
    {
        $module = $this->resolveModule($request);
        $config = $this->catalogAdminService->getModuleConfig($module);
        $record = $this->catalogAdminService->updateRecord($module, $slug, $request->validated());

        if ($record === null) {
            return redirect()
                ->route($this->catalogAdminService->routeName($module, 'index'))
                ->with('error', $config['singular'].' not found.');
        }

        return redirect()
            ->route($this->catalogAdminService->routeName($module, 'edit'), $record['slug'])
            ->with('success', $config['singular'].' updated successfully.');
    }

    public function destroy(Request $request, string $slug): RedirectResponse
    {
        $module = $this->resolveModule($request);
        $config = $this->catalogAdminService->getModuleConfig($module);
        abort_unless(auth()->user()?->hasPermission('cms.update') ?? false, 403);

        if (! $this->catalogAdminService->deleteRecord($module, $slug)) {
            return redirect()
                ->route($this->catalogAdminService->routeName($module, 'index'))
                ->with('error', $config['singular'].' not found.');
        }

        return redirect()
            ->route($this->catalogAdminService->routeName($module, 'index'))
            ->with('success', $config['singular'].' deleted successfully.');
    }

    private function resolveModule(Request $request): string
    {
        $module = $request->route('catalog_module');

        abort_unless(is_string($module) && config("admin_catalog.modules.{$module}") !== null, 404);

        return $module;
    }
}
