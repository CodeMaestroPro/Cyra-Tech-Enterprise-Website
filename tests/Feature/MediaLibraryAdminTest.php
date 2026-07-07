<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaLibraryAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_media_library_admin(): void
    {
        $this->get(route('admin.media.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_media_library_index(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.media.index'));

        $response
            ->assertOk()
            ->assertViewIs('admin.media.index')
            ->assertSee('Media Library')
            ->assertSee('Cyra-Tech Primary Logo');
    }

    public function test_admin_can_upload_media_asset(): void
    {
        Storage::fake('public');

        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();
        $file = UploadedFile::fake()->image('campaign-banner.jpg', 800, 450);

        $response = $this->actingAs($admin)->post(route('admin.media.store'), [
            'file' => $file,
            'title' => 'Campaign Banner',
            'category' => 'marketing',
            'alt_text' => 'Campaign banner artwork',
            'caption' => 'Q3 campaign',
            'description' => 'Marketing banner for the Q3 enterprise campaign.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('media_assets', [
            'title' => 'Campaign Banner',
            'category' => 'marketing',
        ]);
    }

    public function test_admin_can_update_and_delete_media_asset(): void
    {
        Storage::fake('public');

        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();
        $uuid = 'a1000001-0000-4000-8000-000000000001';

        $this->actingAs($admin)->put(route('admin.media.update', $uuid), [
            'title' => 'Updated Primary Logo',
            'category' => 'brand',
            'alt_text' => 'Updated alt text',
            'caption' => 'Updated caption',
            'description' => 'Updated description',
            'sort_order' => 1,
            'is_active' => 1,
        ])->assertRedirect(route('admin.media.edit', $uuid));

        $this->assertDatabaseHas('media_assets', [
            'uuid' => $uuid,
            'title' => 'Updated Primary Logo',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.media.destroy', $uuid))
            ->assertRedirect(route('admin.media.index'));

        $this->assertDatabaseMissing('media_assets', ['uuid' => $uuid]);
    }

    public function test_viewer_cannot_access_media_library_admin(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-media@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)->get(route('admin.media.index'))->assertForbidden();
    }
}
