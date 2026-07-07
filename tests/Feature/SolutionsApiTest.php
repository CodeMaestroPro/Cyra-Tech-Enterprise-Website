<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SolutionsApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_solutions_api_returns_catalog(): void
    {
        $response = $this->getJson(route('api.solutions.index'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'seo',
                    'hero',
                    'categories',
                    'offerings',
                    'featured',
                    'process',
                    'cta',
                ],
            ])
            ->assertJsonPath('data.offerings.0.slug', 'digital-transformation');
    }

    public function test_solutions_api_returns_single_offering(): void
    {
        $response = $this->getJson(route('api.solutions.show', ['slug' => 'ai-intelligence']));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'AI & Intelligence');
    }

    public function test_solutions_api_returns_not_found_for_invalid_slug(): void
    {
        $response = $this->getJson(route('api.solutions.show', ['slug' => 'missing']));

        $response
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }
}
