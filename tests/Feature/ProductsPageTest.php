<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_products_page_renders_catalog_and_ecosystem(): void
    {
        $response = $this->get(route('products'));

        $response
            ->assertOk()
            ->assertViewIs('products.index')
            ->assertSee('Platform products built for enterprise velocity')
            ->assertSee('Cyra Command')
            ->assertSee('Cyra Pulse')
            ->assertSee('Cyra Shield')
            ->assertSee('Built to work better together');
    }

    public function test_product_detail_page_renders_offering(): void
    {
        $response = $this->get(route('products.show', 'cyra-command'));

        $response
            ->assertOk()
            ->assertViewIs('products.show')
            ->assertSee('Cyra Command')
            ->assertSee('Executive briefings')
            ->assertSee('Book a Demo');
    }

    public function test_unknown_product_returns_not_found(): void
    {
        $response = $this->get(route('products.show', 'unknown-product'));

        $response->assertNotFound();
    }
}
