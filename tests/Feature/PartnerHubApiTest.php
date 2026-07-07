<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerHubApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_partner_hub_api_returns_catalog(): void
    {
        $response = $this->getJson(route('api.partner-hub.index'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'seo',
                    'hero',
                    'categories',
                    'programs',
                    'featured',
                    'ecosystem',
                    'cta',
                ],
            ])
            ->assertJsonPath('data.programs.0.slug', 'cloud-alliance-program');
    }

    public function test_partner_hub_api_returns_single_program(): void
    {
        $response = $this->getJson(route('api.partner-hub.show', ['slug' => 'technology-isv-partners']));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Technology ISV Partners');
    }

    public function test_partner_hub_api_returns_not_found_for_invalid_slug(): void
    {
        $response = $this->getJson(route('api.partner-hub.show', ['slug' => 'missing']));

        $response
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }
}
