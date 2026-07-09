<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_marketing(): void
    {
        $this->get(route('admin.marketing.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_marketing(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.marketing.index'));

        $response
            ->assertOk()
            ->assertViewIs('admin.marketing.index')
            ->assertSee('Marketing')
            ->assertSee('Campaign Portfolio')
            ->assertSee('Channel Mix')
            ->assertSee('Q3 Enterprise Launch')
            ->assertSee('Content Channels');
    }

    public function test_manager_can_view_marketing(): void
    {
        $manager = User::factory()->create(['email' => 'manager-mkt@cyratech.com']);
        $manager->syncRoles(['manager']);

        $this->actingAs($manager)->get(route('admin.marketing.index'))->assertOk();
    }

    public function test_viewer_can_view_marketing_with_dashboard_access(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-mkt@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)
            ->get(route('admin.marketing.index'))
            ->assertOk()
            ->assertSee('Marketing');
    }
}
