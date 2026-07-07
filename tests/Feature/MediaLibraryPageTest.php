<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaLibraryPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_public_media_assets_api_returns_seeded_catalog(): void
    {
        $response = $this->getJson(route('api.media.assets.index'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.summary.total', 7)
            ->assertJsonPath('data.assets.0.title', 'Cyra-Tech Primary Logo');
    }

    public function test_public_media_asset_api_returns_single_asset(): void
    {
        $response = $this->getJson(route('api.media.assets.show', 'a1000001-0000-4000-8000-000000000001'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Cyra-Tech Primary Logo')
            ->assertJsonPath('data.is_image', true);
    }

    public function test_inactive_media_asset_is_hidden_from_public_api(): void
    {
        \App\Models\MediaAsset::query()
            ->where('uuid', 'a1000001-0000-4000-8000-000000000001')
            ->update(['is_active' => false]);

        $this->getJson(route('api.media.assets.show', 'a1000001-0000-4000-8000-000000000001'))
            ->assertNotFound();
    }
}
