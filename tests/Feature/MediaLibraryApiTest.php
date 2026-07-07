<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaLibraryApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_admin_media_api_requires_authentication(): void
    {
        $this->getJson(route('api.media.index'))->assertUnauthorized();
    }

    public function test_admin_media_api_returns_catalog_for_admin_user(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->getJson(route('api.media.index'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.summary.total', 7)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'categories',
                    'allowed_mime_types',
                    'max_upload_kb',
                    'assets',
                    'summary',
                ],
            ]);
    }

    public function test_admin_media_api_returns_single_asset(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->getJson(route('api.media.show', 'a1000001-0000-4000-8000-000000000002'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Cyra-Tech Logo Mark');
    }

    public function test_admin_media_api_accepts_uploads(): void
    {
        Storage::fake('public');

        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();
        $file = UploadedFile::fake()->create('brief.pdf', 120, 'application/pdf');

        $response = $this->actingAs($admin)->postJson(route('api.media.store'), [
            'file' => $file,
            'title' => 'Enterprise Brief',
            'category' => 'documents',
            'description' => 'Enterprise sales brief PDF.',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Enterprise Brief');

        $this->assertDatabaseHas('media_assets', [
            'title' => 'Enterprise Brief',
            'category' => 'documents',
        ]);
    }

    public function test_admin_media_api_can_delete_asset(): void
    {
        Storage::fake('public');

        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();
        $uuid = 'a1000001-0000-4000-8000-000000000007';

        $this->actingAs($admin)
            ->deleteJson(route('api.media.destroy', $uuid))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('media_assets', ['uuid' => $uuid]);
    }
}
