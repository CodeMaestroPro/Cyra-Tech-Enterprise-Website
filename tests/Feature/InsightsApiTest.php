<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InsightsApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_insights_api_returns_catalog(): void
    {
        $response = $this->getJson(route('api.insights.index'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'seo',
                    'hero',
                    'categories',
                    'articles',
                    'featured',
                    'editorial',
                    'cta',
                ],
            ])
            ->assertJsonPath('data.articles.0.slug', 'executive-guide-ai-readiness');
    }

    public function test_insights_api_returns_single_article(): void
    {
        $response = $this->getJson(route('api.insights.show', ['slug' => 'zero-trust-regulated-industries']));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Zero Trust in Regulated Industries');
    }

    public function test_insights_api_returns_not_found_for_invalid_slug(): void
    {
        $response = $this->getJson(route('api.insights.show', ['slug' => 'missing']));

        $response
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }
}
