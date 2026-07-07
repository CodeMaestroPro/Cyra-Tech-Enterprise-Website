<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CareersApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_careers_api_returns_catalog(): void
    {
        $response = $this->getJson(route('api.careers.index'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'seo',
                    'hero',
                    'categories',
                    'openings',
                    'featured',
                    'culture',
                    'cta',
                ],
            ])
            ->assertJsonPath('data.openings.0.slug', 'senior-cloud-architect');
    }

    public function test_careers_api_returns_single_opening(): void
    {
        $response = $this->getJson(route('api.careers.show', ['slug' => 'ai-ml-engineer']));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'AI / ML Engineer');
    }

    public function test_careers_api_returns_not_found_for_invalid_slug(): void
    {
        $response = $this->getJson(route('api.careers.show', ['slug' => 'missing']));

        $response
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }
}
