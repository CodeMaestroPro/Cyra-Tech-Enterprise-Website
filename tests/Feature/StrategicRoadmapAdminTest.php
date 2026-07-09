<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StrategicRoadmapAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_strategic_roadmap(): void
    {
        $this->get(route('admin.strategic-roadmap.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_strategic_roadmap(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.strategic-roadmap.index'));

        $response
            ->assertOk()
            ->assertViewIs('admin.strategic-roadmap.index')
            ->assertSee('Strategic Roadmap')
            ->assertSee('Vision & Executive Summary')
            ->assertSee('Quarterly Initiatives')
            ->assertSee('Platform Module Roadmap')
            ->assertSee('Strategic Pillars');
    }

    public function test_manager_can_view_strategic_roadmap(): void
    {
        $manager = User::factory()->create(['email' => 'manager-roadmap@cyratech.com']);
        $manager->syncRoles(['manager']);

        $this->actingAs($manager)->get(route('admin.strategic-roadmap.index'))->assertOk();
    }

    public function test_viewer_can_view_strategic_roadmap_with_dashboard_access(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-roadmap@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)
            ->get(route('admin.strategic-roadmap.index'))
            ->assertOk()
            ->assertSee('Strategic Roadmap');
    }
}
