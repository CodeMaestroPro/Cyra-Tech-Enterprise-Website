<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_about_catalog_api_returns_navigation_and_pages(): void
    {
        $response = $this->getJson(route('api.about.index'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'navigation',
                    'pages',
                ],
            ])
            ->assertJsonPath('data.pages.0.slug', 'overview');
    }

    public function test_about_page_api_returns_single_page_payload(): void
    {
        $response = $this->getJson(route('api.about.show', ['slug' => 'values']));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.slug', 'values')
            ->assertJsonPath('data.title', 'Principles that define how we work');
    }

    public function test_about_page_api_returns_not_found_for_invalid_slug(): void
    {
        $response = $this->getJson(route('api.about.show', ['slug' => 'missing']));

        $response
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }
}
