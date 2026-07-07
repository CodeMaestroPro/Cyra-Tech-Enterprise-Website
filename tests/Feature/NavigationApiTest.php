<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_public_navigation_api_returns_header_and_footer(): void
    {
        $response = $this->getJson(route('api.navigation.public'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'brand',
                    'header',
                    'actions',
                    'footer' => [
                        'columns',
                        'social',
                        'legal',
                        'newsletter',
                    ],
                ],
            ])
            ->assertJsonPath('data.header.0.label', 'Home')
            ->assertJsonPath('data.actions.1.label', 'Contact Us');
    }

    public function test_admin_navigation_api_requires_authentication(): void
    {
        $response = $this->getJson(route('api.navigation.admin'));

        $response->assertUnauthorized();
    }

    public function test_admin_navigation_api_returns_grouped_sidebar_for_admin(): void
    {
        $admin = \App\Models\User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->getJson(route('api.navigation.admin'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'brand',
                    'groups',
                ],
            ])
            ->assertJsonPath('data.groups.0.label', 'Executive');
    }
}
