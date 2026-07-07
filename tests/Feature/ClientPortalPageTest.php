<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientPortalPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_client_portal_landing_page_renders(): void
    {
        $response = $this->get(route('client-portal'));

        $response
            ->assertOk()
            ->assertViewIs('client-portal.index')
            ->assertSee('Your engagement command center')
            ->assertSee('Enterprise-grade security');
    }

    public function test_client_portal_dashboard_requires_authentication(): void
    {
        $response = $this->get(route('client-portal.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_client_user_can_view_dashboard_and_engagements(): void
    {
        $client = \App\Models\User::query()->where('email', config('cyra.client_user.email'))->firstOrFail();

        $response = $this->actingAs($client)->get(route('client-portal.dashboard'));

        $response
            ->assertOk()
            ->assertViewIs('client-portal.dashboard')
            ->assertSee('NovaBank')
            ->assertSee('Digital Core Modernization')
            ->assertSee('Fraud Analytics Copilot');
    }

    public function test_client_user_can_view_engagement_detail(): void
    {
        $client = \App\Models\User::query()->where('email', config('cyra.client_user.email'))->firstOrFail();

        $response = $this->actingAs($client)->get(route('client-portal.engagements.show', 'digital-core-modernization'));

        $response
            ->assertOk()
            ->assertViewIs('client-portal.show')
            ->assertSee('Digital Core Modernization')
            ->assertSee('Landing zone approved')
            ->assertSee('Architecture decision records');
    }

    public function test_client_user_cannot_view_other_account_engagement(): void
    {
        $client = \App\Models\User::query()->where('email', config('cyra.client_user.email'))->firstOrFail();

        $response = $this->actingAs($client)->get(route('client-portal.engagements.show', 'connected-care-platform'));

        $response->assertNotFound();
    }

    public function test_admin_user_cannot_access_client_portal_dashboard(): void
    {
        $admin = \App\Models\User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('client-portal.dashboard'));

        $response->assertForbidden();
    }
}
