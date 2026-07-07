<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\MediaLibraryService;
use Illuminate\Database\Seeder;

class MediaLibrarySeeder extends Seeder
{
    public function run(): void
    {
        $uploader = User::query()
            ->where('email', config('cyra.admin.email'))
            ->first();

        $service = app(MediaLibraryService::class);
        $sort = 1;

        foreach (config('cyra.media_library.seed_assets', []) as $asset) {
            $service->seedAssetFromFixture([
                ...$asset,
                'sort_order' => $sort++,
            ], $uploader);
        }
    }
}
