<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePartnerProgramRequest;
use App\Http\Requests\UpdatePartnerProgramRequest;
use App\Services\PartnerHubService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartnersController extends Controller
{
    public function __construct(
        private readonly PartnerHubService $partnerHubService,
    ) {
    }

    public function index(Request $request): View
    {
        return view('admin.partners.index', [
            'catalog' => $this->partnerHubService->getAdminCatalog($request->query('category', 'all')),
            'categoryFilter' => $request->query('category', 'all'),
        ]);
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->hasPermission('partners.create') ?? false, 403);

        return view('admin.partners.create', [
            'options' => $this->partnerHubService->getFormOptions(),
        ]);
    }

    public function store(StorePartnerProgramRequest $request): RedirectResponse
    {
        $program = $this->partnerHubService->createProgram($request->validated());

        return redirect()
            ->route('admin.partners.edit', $program['slug'])
            ->with('success', 'Partner created successfully.');
    }

    public function edit(string $slug): View|RedirectResponse
    {
        abort_unless(auth()->user()?->hasPermission('partners.view') ?? false, 403);

        $program = $this->partnerHubService->getAdminProgram($slug);

        if ($program === null) {
            return redirect()
                ->route('admin.partners.index')
                ->with('error', 'Partner not found.');
        }

        return view('admin.partners.edit', [
            'program' => $program,
            'options' => $this->partnerHubService->getFormOptions(),
        ]);
    }

    public function update(UpdatePartnerProgramRequest $request, string $slug): RedirectResponse
    {
        $program = $this->partnerHubService->updateProgram($slug, $request->validated());

        if ($program === null) {
            return redirect()
                ->route('admin.partners.index')
                ->with('error', 'Partner not found.');
        }

        return redirect()
            ->route('admin.partners.edit', $program['slug'])
            ->with('success', 'Partner updated successfully.');
    }

    public function destroy(string $slug): RedirectResponse
    {
        abort_unless(auth()->user()?->hasPermission('partners.delete') ?? false, 403);

        if (! $this->partnerHubService->deleteProgram($slug)) {
            return redirect()
                ->route('admin.partners.index')
                ->with('error', 'Partner not found.');
        }

        return redirect()
            ->route('admin.partners.index')
            ->with('success', 'Partner deleted successfully.');
    }
}
