<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrmAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_crm_pipeline(): void
    {
        $this->get(route('admin.crm.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_crm_pipeline(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.crm.index'));

        $response
            ->assertOk()
            ->assertViewIs('admin.crm.index')
            ->assertSee('Leads & CRM')
            ->assertSee('NovaBank Digital')
            ->assertSee('Pipeline Value');
    }

    public function test_manager_can_view_crm_pipeline(): void
    {
        $manager = User::factory()->create(['email' => 'manager-crm@cyratech.com']);
        $manager->syncRoles(['manager']);

        $this->actingAs($manager)->get(route('admin.crm.index'))->assertOk();
    }

    public function test_viewer_cannot_access_crm_pipeline(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-crm@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)->get(route('admin.crm.index'))->assertForbidden();
    }

    public function test_admin_can_create_lead(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->post(route('admin.crm.store'), [
            'name' => 'Test Lead',
            'email' => 'test.lead@example.com',
            'company' => 'Example Corp',
            'source' => 'website',
            'pipeline_stage' => 'new',
            'priority' => 'medium',
            'estimated_value' => 1500000,
            'notes' => 'Test lead created from admin form.',
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('crm_leads', [
            'name' => 'Test Lead',
            'email' => 'test.lead@example.com',
            'pipeline_stage' => 'new',
        ]);
    }

    public function test_admin_can_update_lead_stage(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->post(route('admin.crm.stage', 'CRM-SEED-004'), [
            'pipeline_stage' => 'qualified',
        ]);

        $response
            ->assertRedirect(route('admin.crm.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('crm_leads', [
            'reference' => 'CRM-SEED-004',
            'pipeline_stage' => 'qualified',
        ]);
    }
}
