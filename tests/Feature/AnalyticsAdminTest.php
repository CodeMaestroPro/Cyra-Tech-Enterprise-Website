<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_analytics_dashboard(): void
    {
        $this->get(route('admin.analytics.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_analytics_dashboard(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.analytics.index'));

        $response
            ->assertOk()
            ->assertViewIs('admin.analytics.index')
            ->assertSee('Cyra Pulse Analytics')
            ->assertSee('Traffic Trend')
            ->assertSee('Top Pages');
    }

    public function test_manager_can_view_analytics_dashboard(): void
    {
        $manager = User::factory()->create(['email' => 'manager-analytics@cyratech.com']);
        $manager->syncRoles(['manager']);

        $this->actingAs($manager)->get(route('admin.analytics.index'))->assertOk();
    }

    public function test_viewer_cannot_access_analytics_dashboard(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-analytics@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)->get(route('admin.analytics.index'))->assertForbidden();
    }
}
