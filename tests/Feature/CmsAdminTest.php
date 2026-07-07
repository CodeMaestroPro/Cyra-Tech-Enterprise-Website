<?php

namespace Tests\Feature;

use App\Models\CmsPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_cms_admin(): void
    {
        $this->get(route('admin.cms.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_cms_index(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.cms.index'));

        $response
            ->assertOk()
            ->assertViewIs('admin.cms.index')
            ->assertSee('Content Management')
            ->assertSee('Privacy Policy');
    }

    public function test_admin_can_create_cms_page(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->post(route('admin.cms.store'), [
            'title' => 'Security Overview',
            'slug' => 'security-overview',
            'template' => 'policy',
            'status' => 'draft',
            'eyebrow' => 'Security',
            'excerpt' => 'Security practices summary.',
            'description' => 'Overview of Cyra-Tech security practices.',
            'body' => "First paragraph.\n\nSecond paragraph.",
            'seo_title' => 'Security Overview | Cyra-Tech',
            'seo_description' => 'Overview of Cyra-Tech security practices.',
            'sort_order' => 10,
        ]);

        $response
            ->assertRedirect(route('admin.cms.edit', 'security-overview'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('cms_pages', [
            'slug' => 'security-overview',
            'title' => 'Security Overview',
            'status' => 'draft',
        ]);
    }

    public function test_admin_can_publish_and_unpublish_page(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $page = CmsPage::query()->where('slug', 'privacy-policy')->firstOrFail();
        $page->update(['status' => 'draft', 'published_at' => null]);

        $this->actingAs($admin)
            ->post(route('admin.cms.publish', 'privacy-policy'))
            ->assertRedirect(route('admin.cms.edit', 'privacy-policy'));

        $this->assertSame('published', $page->fresh()->status);

        $this->actingAs($admin)
            ->post(route('admin.cms.unpublish', 'privacy-policy'))
            ->assertRedirect(route('admin.cms.edit', 'privacy-policy'));

        $this->assertSame('draft', $page->fresh()->status);
    }

    public function test_viewer_cannot_access_cms_admin(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-cms@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)->get(route('admin.cms.index'))->assertForbidden();
    }
}
