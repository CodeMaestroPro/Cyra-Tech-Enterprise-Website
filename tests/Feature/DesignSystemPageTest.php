<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DesignSystemPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_design_system_page(): void
    {
        $this->get(route('admin.design-system'))->assertRedirect(route('login'));
    }

    public function test_authenticated_admin_can_view_design_system_showcase(): void
    {
        $user = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($user)->get(route('admin.design-system'));

        $response
            ->assertOk()
            ->assertViewIs('admin.design-system.index')
            ->assertSee('Cyra-Tech Design System')
            ->assertSee('No React')
            ->assertSee('Color Tokens');
    }

    public function test_viewer_without_modules_permission_cannot_access_design_system(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-design@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)->get(route('admin.design-system'))->assertForbidden();
    }
}
