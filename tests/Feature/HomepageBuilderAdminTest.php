<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageBuilderAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_homepage_builder(): void
    {
        $this->get(route('admin.homepage-builder.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_homepage_builder(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.homepage-builder.index'));

        $response
            ->assertOk()
            ->assertViewIs('admin.homepage-builder.index')
            ->assertSee('Homepage Builder')
            ->assertSee('Homepage Sections')
            ->assertSee('SEO & Metadata')
            ->assertSee('hero')
            ->assertSee('View Live Homepage');
    }

    public function test_manager_can_view_homepage_builder(): void
    {
        $manager = User::factory()->create(['email' => 'manager-hb@cyratech.com']);
        $manager->syncRoles(['manager']);

        $this->actingAs($manager)->get(route('admin.homepage-builder.index'))->assertOk();
    }

    public function test_viewer_without_modules_permission_cannot_access_homepage_builder(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-hb@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)->get(route('admin.homepage-builder.index'))->assertForbidden();
    }
}
