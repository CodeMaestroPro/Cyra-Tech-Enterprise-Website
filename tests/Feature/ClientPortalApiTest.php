<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientPortalApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_client_portal_api_returns_landing_configuration(): void
    {
        $response = $this->getJson(route('api.client-portal.show'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'seo',
                    'hero',
                    'features',
                    'security',
                    'cta',
                ],
            ])
            ->assertJsonPath('data.hero.title', 'Your engagement command center');
    }

    public function test_client_portal_dashboard_api_requires_authentication(): void
    {
        $response = $this->getJson(route('api.client-portal.dashboard'));

        $response->assertUnauthorized();
    }

    public function test_client_portal_dashboard_api_returns_account_engagements(): void
    {
        $client = \App\Models\User::query()->where('email', config('cyra.client_user.email'))->firstOrFail();

        $response = $this->actingAs($client)->getJson(route('api.client-portal.dashboard'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.account.slug', 'novabank')
            ->assertJsonPath('data.engagements.0.slug', 'digital-core-modernization');
    }

    public function test_client_portal_engagement_api_returns_single_engagement(): void
    {
        $client = \App\Models\User::query()->where('email', config('cyra.client_user.email'))->firstOrFail();

        $response = $this->actingAs($client)->getJson(route('api.client-portal.engagements.show', ['slug' => 'digital-core-modernization']));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Digital Core Modernization');
    }

    public function test_client_portal_engagement_api_returns_not_found_for_other_account(): void
    {
        $client = \App\Models\User::query()->where('email', config('cyra.client_user.email'))->firstOrFail();

        $response = $this->actingAs($client)->getJson(route('api.client-portal.engagements.show', ['slug' => 'connected-care-platform']));

        $response
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }
}
