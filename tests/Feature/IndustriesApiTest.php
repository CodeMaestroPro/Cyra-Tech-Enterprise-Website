<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndustriesApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_industries_api_returns_catalog(): void
    {
        $response = $this->getJson(route('api.industries.index'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'seo',
                    'hero',
                    'categories',
                    'verticals',
                    'featured',
                    'expertise',
                    'cta',
                ],
            ])
            ->assertJsonPath('data.verticals.0.slug', 'financial-services');
    }

    public function test_industries_api_returns_single_vertical(): void
    {
        $response = $this->getJson(route('api.industries.show', ['slug' => 'healthcare']));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Healthcare');
    }

    public function test_industries_api_returns_not_found_for_invalid_slug(): void
    {
        $response = $this->getJson(route('api.industries.show', ['slug' => 'missing']));

        $response
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }
}
