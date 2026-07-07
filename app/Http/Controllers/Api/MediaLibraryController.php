<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMediaAssetRequest;
use App\Http\Requests\UpdateMediaAssetRequest;
use App\Services\MediaLibraryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaLibraryController extends Controller
{
    public function __construct(
        private readonly MediaLibraryService $mediaLibraryService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $category = $request->query('category', 'all');

        return response()->json([
            'success' => true,
            'data' => $this->mediaLibraryService->getPublicCatalog($category),
        ]);
    }

    public function show(string $uuid): JsonResponse
    {
        $asset = $this->mediaLibraryService->getPublicAsset($uuid);

        if ($asset === null) {
            return response()->json([
                'success' => false,
                'message' => 'Media asset not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $asset,
        ]);
    }

    public function adminIndex(Request $request): JsonResponse
    {
        $category = $request->query('category', 'all');

        return response()->json([
            'success' => true,
            'data' => $this->mediaLibraryService->getAdminCatalog($category),
        ]);
    }

    public function adminShow(string $uuid): JsonResponse
    {
        $asset = $this->mediaLibraryService->getAdminAsset($uuid);

        if ($asset === null) {
            return response()->json([
                'success' => false,
                'message' => 'Media asset not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $asset,
        ]);
    }

    public function store(StoreMediaAssetRequest $request): JsonResponse
    {
        $asset = $this->mediaLibraryService->uploadAsset(
            $request->file('file'),
            $request->validated(),
            $request->user(),
        );

        return response()->json([
            'success' => true,
            'data' => $asset,
        ], 201);
    }

    public function update(UpdateMediaAssetRequest $request, string $uuid): JsonResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $asset = $this->mediaLibraryService->updateAsset($uuid, $data);

        if ($asset === null) {
            return response()->json([
                'success' => false,
                'message' => 'Media asset not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $asset,
        ]);
    }

    public function destroy(string $uuid): JsonResponse
    {
        if (! $this->mediaLibraryService->deleteAsset($uuid)) {
            return response()->json([
                'success' => false,
                'message' => 'Media asset not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Media asset deleted successfully.',
        ]);
    }
}
