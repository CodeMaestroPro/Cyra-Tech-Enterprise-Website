<?php

namespace Tests\Feature;

use App\Models\PartnerProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnersAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_partners_admin(): void
    {
        $this->get(route('admin.partners.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_partners_index(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.partners.index'));

        $response
            ->assertOk()
            ->assertViewIs('admin.partners.index')
            ->assertSee('Partners')
            ->assertSee('Cloud Alliance Program')
            ->assertSee('Add Partner');
    }

    public function test_viewer_cannot_access_partners_admin(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-partners@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)->get(route('admin.partners.index'))->assertForbidden();
    }

    public function test_admin_can_create_partner(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->post(route('admin.partners.store'), [
            'slug' => 'regional-tech-partners',
            'category' => 'technology',
            'title' => 'Regional Tech Partners',
            'partner_type' => 'Technology Partner',
            'region' => 'Africa',
            'engagement_model' => 'Co-innovate',
            'tagline' => 'Build regional technology alliances.',
            'summary' => 'Partner with Cyra-Tech on regional technology programs.',
            'description' => 'Regional Tech Partners collaborate on product integrations and joint delivery.',
            'benefits' => "Joint go-to-market\nIntegration support",
            'requirements' => "Enterprise product\nDedicated liaison",
            'enablement' => "Partner onboarding\nSolution workshops",
            'badge' => 'Regional',
            'icon' => 'integration',
            'sort_order' => 10,
            'is_active' => '1',
            'is_featured' => '1',
        ]);

        $response
            ->assertRedirect(route('admin.partners.edit', 'regional-tech-partners'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('partner_programs', [
            'slug' => 'regional-tech-partners',
            'title' => 'Regional Tech Partners',
            'category' => 'technology',
            'is_active' => true,
            'is_featured' => true,
        ]);
    }

    public function test_admin_can_update_partner(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();
        $program = PartnerProgram::query()->where('slug', 'cloud-alliance-program')->firstOrFail();

        $response = $this->actingAs($admin)->put(route('admin.partners.update', $program->slug), [
            'category' => 'cloud',
            'title' => 'Cloud Alliance Program Updated',
            'partner_type' => $program->partner_type,
            'region' => $program->region,
            'engagement_model' => $program->engagement_model,
            'tagline' => $program->tagline,
            'summary' => $program->summary,
            'description' => $program->description,
            'benefits' => implode("\n", $program->benefits ?? []),
            'requirements' => implode("\n", $program->requirements ?? []),
            'enablement' => implode("\n", $program->enablement ?? []),
            'badge' => $program->badge,
            'icon' => $program->icon,
            'sort_order' => $program->sort_order,
            'is_active' => '1',
            'is_featured' => '1',
        ]);

        $response
            ->assertRedirect(route('admin.partners.edit', $program->slug))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('partner_programs', [
            'slug' => 'cloud-alliance-program',
            'title' => 'Cloud Alliance Program Updated',
        ]);
    }

    public function test_admin_can_delete_partner(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $program = PartnerProgram::query()->create([
            'slug' => 'temporary-partner',
            'category' => 'channel',
            'title' => 'Temporary Partner',
            'partner_type' => 'Channel Partner',
            'region' => 'Global',
            'engagement_model' => 'Resell',
            'tagline' => 'Temporary partner program.',
            'summary' => 'Temporary summary.',
            'description' => 'Temporary description.',
            'benefits' => ['Benefit one'],
            'requirements' => ['Requirement one'],
            'enablement' => ['Enablement one'],
            'badge' => 'Temp',
            'icon' => 'spark',
            'sort_order' => 99,
            'is_active' => true,
            'is_featured' => false,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.partners.destroy', $program->slug))
            ->assertRedirect(route('admin.partners.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('partner_programs', [
            'slug' => 'temporary-partner',
        ]);
    }

    public function test_created_partner_appears_on_public_partner_hub(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $this->actingAs($admin)->post(route('admin.partners.store'), [
            'slug' => 'public-visible-partner',
            'category' => 'services',
            'title' => 'Public Visible Partner',
            'partner_type' => 'System Integrator',
            'region' => 'Global',
            'engagement_model' => 'Co-delivery',
            'tagline' => 'Visible on the public hub.',
            'summary' => 'Public summary.',
            'description' => 'Public description.',
            'is_active' => '1',
        ]);

        $this->get(route('partner-hub'))
            ->assertOk()
            ->assertSee('Public Visible Partner');
    }

    public function test_admin_role_user_can_manage_partners(): void
    {
        $admin = User::factory()->create(['email' => 'admin-partners@cyratech.com']);
        $admin->syncRoles(['admin']);

        $this->actingAs($admin)->get(route('admin.partners.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.partners.create'))->assertOk();
    }
}
