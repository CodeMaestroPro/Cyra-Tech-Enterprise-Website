<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientAccountRequest;
use App\Http\Requests\UpdateClientAccountRequest;
use App\Services\ClientPortalAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientPortalAdminController extends Controller
{
    public function __construct(
        private readonly ClientPortalAdminService $clientPortalAdminService,
    ) {
    }

    public function index(): View
    {
        return view('admin.client-portal.index', [
            'catalog' => $this->clientPortalAdminService->getAdminCatalog(),
        ]);
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->hasPermission('cms.create') ?? false, 403);

        return view('admin.client-portal.create');
    }

    public function store(StoreClientAccountRequest $request): RedirectResponse
    {
        $account = $this->clientPortalAdminService->createAccount($request->validated());

        return redirect()
            ->route('admin.client-portal.edit', $account['slug'])
            ->with('success', 'Client account created successfully.');
    }

    public function edit(string $slug): View|RedirectResponse
    {
        $account = $this->clientPortalAdminService->getAdminAccount($slug);

        if ($account === null) {
            return redirect()
                ->route('admin.client-portal.index')
                ->with('error', 'Client account not found.');
        }

        return view('admin.client-portal.edit', [
            'account' => $account,
        ]);
    }

    public function update(UpdateClientAccountRequest $request, string $slug): RedirectResponse
    {
        $account = $this->clientPortalAdminService->updateAccount($slug, $request->validated());

        if ($account === null) {
            return redirect()
                ->route('admin.client-portal.index')
                ->with('error', 'Client account not found.');
        }

        return redirect()
            ->route('admin.client-portal.edit', $account['slug'])
            ->with('success', 'Client account updated successfully.');
    }

    public function destroy(string $slug): RedirectResponse
    {
        abort_unless(auth()->user()?->hasPermission('cms.update') ?? false, 403);

        if (! $this->clientPortalAdminService->deleteAccount($slug)) {
            return redirect()
                ->route('admin.client-portal.index')
                ->with('error', 'Client account not found.');
        }

        return redirect()
            ->route('admin.client-portal.index')
            ->with('success', 'Client account deleted successfully.');
    }
}
