<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessIntelligenceAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_business_intelligence(): void
    {
        $this->get(route('admin.business-intelligence.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_business_intelligence(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.business-intelligence.index'));

        $response
            ->assertOk()
            ->assertViewIs('admin.business-intelligence.index')
            ->assertSee('Business Intelligence')
            ->assertSee('Data Domains')
            ->assertSee('Digital Performance')
            ->assertSee('CRM Pipeline')
            ->assertSee('Executive Insights');
    }

    public function test_manager_can_view_business_intelligence(): void
    {
        $manager = User::factory()->create(['email' => 'manager-bi@cyratech.com']);
        $manager->syncRoles(['manager']);

        $this->actingAs($manager)->get(route('admin.business-intelligence.index'))->assertOk();
    }

    public function test_viewer_can_view_business_intelligence_with_dashboard_access(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-bi@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)
            ->get(route('admin.business-intelligence.index'))
            ->assertOk()
            ->assertSee('Business Intelligence');
    }
}
