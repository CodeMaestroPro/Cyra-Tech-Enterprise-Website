<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyPulseAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_company_pulse(): void
    {
        $this->get(route('admin.company-pulse.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_company_pulse(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.company-pulse.index'));

        $response
            ->assertOk()
            ->assertViewIs('admin.company-pulse.index')
            ->assertSee('Company Pulse')
            ->assertSee('Overall Organizational Health')
            ->assertSee('Pulse Dimensions')
            ->assertSee('Financial Health')
            ->assertSee('Live Operational Signals');
    }

    public function test_manager_can_view_company_pulse(): void
    {
        $manager = User::factory()->create(['email' => 'manager-pulse@cyratech.com']);
        $manager->syncRoles(['manager']);

        $this->actingAs($manager)->get(route('admin.company-pulse.index'))->assertOk();
    }

    public function test_viewer_can_view_company_pulse_with_dashboard_access(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-pulse@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)
            ->get(route('admin.company-pulse.index'))
            ->assertOk()
            ->assertSee('Company Pulse');
    }
}
