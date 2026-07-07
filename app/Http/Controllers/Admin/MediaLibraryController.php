<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMediaAssetRequest;
use App\Http\Requests\UpdateMediaAssetRequest;
use App\Services\MediaLibraryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaLibraryController extends Controller
{
    public function __construct(
        private readonly MediaLibraryService $mediaLibraryService,
    ) {
    }

    public function index(Request $request): View
    {
        $category = $request->query('category', 'all');

        return view('admin.media.index', [
            'catalog' => $this->mediaLibraryService->getAdminCatalog($category),
            'categoryFilter' => $category,
        ]);
    }

    public function edit(string $uuid): View|RedirectResponse
    {
        $asset = $this->mediaLibraryService->getAdminAsset($uuid);

        if ($asset === null) {
            return redirect()
                ->route('admin.media.index')
                ->with('error', 'Media asset not found.');
        }

        return view('admin.media.edit', [
            'asset' => $asset,
            'categories' => config('cyra.media_library.categories', []),
        ]);
    }

    public function store(StoreMediaAssetRequest $request): RedirectResponse
    {
        $asset = $this->mediaLibraryService->uploadAsset(
            $request->file('file'),
            $request->validated(),
            $request->user(),
        );

        return redirect()
            ->route('admin.media.edit', $asset['uuid'])
            ->with('success', 'Media asset uploaded successfully.');
    }

    public function update(UpdateMediaAssetRequest $request, string $uuid): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $asset = $this->mediaLibraryService->updateAsset($uuid, $data);

        if ($asset === null) {
            return redirect()
                ->route('admin.media.index')
                ->with('error', 'Media asset not found.');
        }

        return redirect()
            ->route('admin.media.edit', $asset['uuid'])
            ->with('success', 'Media asset updated successfully.');
    }

    public function destroy(string $uuid): RedirectResponse
    {
        if (! $this->mediaLibraryService->deleteAsset($uuid)) {
            return redirect()
                ->route('admin.media.index')
                ->with('error', 'Media asset not found.');
        }

        return redirect()
            ->route('admin.media.index')
            ->with('success', 'Media asset deleted successfully.');
    }
}
