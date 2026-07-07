<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_portfolio_api_returns_catalog(): void
    {
        $response = $this->getJson(route('api.portfolio.index'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'seo',
                    'hero',
                    'categories',
                    'projects',
                    'featured',
                    'impact',
                    'cta',
                ],
            ])
            ->assertJsonPath('data.projects.0.slug', 'novabank-digital-core');
    }

    public function test_portfolio_api_returns_single_project(): void
    {
        $response = $this->getJson(route('api.portfolio.show', ['slug' => 'helix-health-network']));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Helix Health Network');
    }

    public function test_portfolio_api_returns_not_found_for_invalid_slug(): void
    {
        $response = $this->getJson(route('api.portfolio.show', ['slug' => 'missing']));

        $response
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }
}
