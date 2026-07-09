<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateApplicantRequest;
use App\Models\NavigationItem;
use App\Http\Requests\UpdateNavigationItemRequest;
use App\Services\NavigationAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NavigationAdminController extends Controller
{
    public function __construct(
        private readonly NavigationAdminService $navigationAdminService,
    ) {
    }

    public function index(): View
    {
        return view('admin.navigation.index', [
            'navigation' => $this->navigationAdminService->getAdminWorkspace(),
        ]);
    }

    public function edit(NavigationItem $item): View|RedirectResponse
    {
        if ($item->location !== 'admin_sidebar') {
            return redirect()
                ->route('admin.navigation.index')
                ->with('error', 'Navigation item not found.');
        }

        $formatted = $this->navigationAdminService->getAdminItem($item);

        return view('admin.navigation.edit', [
            'item' => $formatted,
        ]);
    }

    public function update(UpdateNavigationItemRequest $request, NavigationItem $item): RedirectResponse
    {
        if ($item->location !== 'admin_sidebar') {
            return redirect()
                ->route('admin.navigation.index')
                ->with('error', 'Navigation item not found.');
        }

        $updated = $this->navigationAdminService->updateItem($item, $request->validated());

        return redirect()
            ->route('admin.navigation.edit', $item)
            ->with('success', 'Navigation item updated successfully.');
    }
}
