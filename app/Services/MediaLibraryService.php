<?php

namespace App\Services;

use App\Models\MediaAsset;
use App\Models\User;
use App\Repositories\MediaAssetRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaLibraryService extends BaseService
{
    public function __construct(
        private readonly MediaAssetRepository $mediaAssetRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getPublicCatalog(?string $category = null): array
    {
        $assets = $this->mediaAssetRepository
            ->getActiveAssets($category)
            ->map(fn (MediaAsset $asset) => $this->formatAsset($asset))
            ->values()
            ->all();

        return [
            'categories' => config('cyra.media_library.categories', []),
            'assets' => $assets,
            'summary' => [
                'total' => count($assets),
            ],
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getPublicAsset(string $uuid): ?array
    {
        $asset = $this->mediaAssetRepository->findActiveByUuid($uuid);

        if ($asset === null) {
            return null;
        }

        return $this->formatAsset($asset);
    }

    /**
     * @return array<string, mixed>
     */
    public function getAdminCatalog(?string $category = null): array
    {
        $assets = $this->mediaAssetRepository
            ->getAllAssets($category)
            ->map(fn (MediaAsset $asset) => $this->formatAdminAsset($asset))
            ->values()
            ->all();

        $activeCount = collect($assets)->where('is_active', true)->count();

        return [
            'categories' => config('cyra.media_library.categories', []),
            'allowed_mime_types' => config('cyra.media_library.allowed_mime_types', []),
            'max_upload_kb' => config('cyra.media_library.max_upload_kb', 5120),
            'assets' => $assets,
            'summary' => [
                'total' => count($assets),
                'active' => $activeCount,
                'inactive' => count($assets) - $activeCount,
            ],
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAdminAsset(string $uuid): ?array
    {
        $asset = $this->mediaAssetRepository->findByUuid($uuid);

        if ($asset === null) {
            return null;
        }

        return $this->formatAdminAsset($asset);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function uploadAsset(UploadedFile $file, array $data, User $user): array
    {
        $category = $data['category'] ?? 'general';
        $uuid = (string) Str::uuid();
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $filename = $uuid.'-'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$extension;
        $path = $file->storeAs('media/'.$category, $filename, 'public');

        $metadata = $this->buildMetadata($file);

        $asset = $this->mediaAssetRepository->createAsset([
            'uuid' => $uuid,
            'title' => $data['title'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'filename' => $filename,
            'disk' => 'public',
            'path' => $path,
            'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
            'extension' => $extension,
            'size_bytes' => $file->getSize(),
            'category' => $category,
            'alt_text' => $data['alt_text'] ?? null,
            'caption' => $data['caption'] ?? null,
            'description' => $data['description'] ?? null,
            'metadata' => $metadata,
            'uploaded_by' => $user->id,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return $this->formatAdminAsset($asset->load('uploader'));
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateAsset(string $uuid, array $data): ?array
    {
        $asset = $this->mediaAssetRepository->findByUuid($uuid);

        if ($asset === null) {
            return null;
        }

        $asset = $this->mediaAssetRepository->updateAsset($asset, [
            'title' => $data['title'],
            'category' => $data['category'],
            'alt_text' => $data['alt_text'] ?? null,
            'caption' => $data['caption'] ?? null,
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? $asset->sort_order,
            'is_active' => $data['is_active'] ?? $asset->is_active,
        ]);

        return $this->formatAdminAsset($asset);
    }

    public function deleteAsset(string $uuid): bool
    {
        $asset = $this->mediaAssetRepository->findByUuid($uuid);

        if ($asset === null) {
            return false;
        }

        if (Storage::disk($asset->disk)->exists($asset->path)) {
            Storage::disk($asset->disk)->delete($asset->path);
        }

        return $this->mediaAssetRepository->deleteAsset($asset);
    }

    /**
     * @param  array<string, mixed>  $seed
     */
    public function seedAssetFromFixture(array $seed, ?User $user = null): MediaAsset
    {
        $fixturePath = database_path('seeders/fixtures/media/'.$seed['fixture']);
        $category = $seed['category'] ?? 'general';
        $uuid = $seed['uuid'] ?? (string) Str::uuid();
        $filename = $seed['filename'] ?? basename($fixturePath);
        $destination = 'media/'.$category.'/'.$filename;

        Storage::disk('public')->makeDirectory('media/'.$category);

        if (file_exists($fixturePath)) {
            Storage::disk('public')->put($destination, file_get_contents($fixturePath));
        }

        $sizeBytes = Storage::disk('public')->exists($destination)
            ? Storage::disk('public')->size($destination)
            : 0;

        return MediaAsset::query()->updateOrCreate(
            ['uuid' => $uuid],
            [
                'title' => $seed['title'],
                'filename' => $filename,
                'disk' => 'public',
                'path' => $destination,
                'mime_type' => $seed['mime_type'] ?? 'image/svg+xml',
                'extension' => $seed['extension'] ?? pathinfo($filename, PATHINFO_EXTENSION),
                'size_bytes' => $sizeBytes,
                'category' => $category,
                'alt_text' => $seed['alt_text'] ?? null,
                'caption' => $seed['caption'] ?? null,
                'description' => $seed['description'] ?? null,
                'metadata' => $seed['metadata'] ?? [],
                'uploaded_by' => $user?->id,
                'sort_order' => $seed['sort_order'] ?? 0,
                'is_active' => $seed['is_active'] ?? true,
            ],
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function formatAsset(MediaAsset $asset): array
    {
        return [
            'uuid' => $asset->uuid,
            'title' => $asset->title,
            'filename' => $asset->filename,
            'url' => $asset->publicUrl(),
            'mime_type' => $asset->mime_type,
            'extension' => $asset->extension,
            'size_bytes' => $asset->size_bytes,
            'size_label' => $this->formatBytes($asset->size_bytes),
            'category' => $asset->category,
            'category_label' => $this->categoryLabel($asset->category),
            'alt_text' => $asset->alt_text,
            'caption' => $asset->caption,
            'description' => $asset->description,
            'metadata' => $asset->metadata ?? [],
            'is_image' => $asset->isImage(),
            'sort_order' => $asset->sort_order,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatAdminAsset(MediaAsset $asset): array
    {
        return [
            ...$this->formatAsset($asset),
            'is_active' => $asset->is_active,
            'uploaded_by' => $asset->uploader?->name,
            'created_at' => $asset->created_at?->toIso8601String(),
            'updated_at' => $asset->updated_at?->toIso8601String(),
        ];
    }

    /**
     * @return array<string, int|null>
     */
    private function buildMetadata(UploadedFile $file): array
    {
        if (! str_starts_with($file->getMimeType() ?? '', 'image/')) {
            return [];
        }

        $dimensions = @getimagesize($file->getRealPath());

        if ($dimensions === false) {
            return [];
        }

        return [
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
        ];
    }

    private function categoryLabel(string $category): string
    {
        return collect(config('cyra.media_library.categories', []))
            ->firstWhere('slug', $category)['label'] ?? ucfirst(str_replace('-', ' ', $category));
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1).' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1).' KB';
        }

        return $bytes.' B';
    }
}
