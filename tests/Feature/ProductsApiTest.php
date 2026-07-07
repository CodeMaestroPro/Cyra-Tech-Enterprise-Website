<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_products_api_returns_catalog(): void
    {
        $response = $this->getJson(route('api.products.index'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'seo',
                    'hero',
                    'categories',
                    'products',
                    'featured',
                    'ecosystem',
                    'cta',
                ],
            ])
            ->assertJsonPath('data.products.0.slug', 'cyra-command');
    }

    public function test_products_api_returns_single_product(): void
    {
        $response = $this->getJson(route('api.products.show', ['slug' => 'cyra-pulse']));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Cyra Pulse');
    }

    public function test_products_api_returns_not_found_for_invalid_slug(): void
    {
        $response = $this->getJson(route('api.products.show', ['slug' => 'missing']));

        $response
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }
}
