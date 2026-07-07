<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndustriesPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_industries_page_renders_catalog_and_expertise(): void
    {
        $response = $this->get(route('industries'));

        $response
            ->assertOk()
            ->assertViewIs('industries.index')
            ->assertSee('Deep domain expertise across regulated and high-growth sectors')
            ->assertSee('Financial Services')
            ->assertSee('Healthcare')
            ->assertSee('Government')
            ->assertSee('Industry programs engineered for outcomes');
    }

    public function test_industry_detail_page_renders_vertical(): void
    {
        $response = $this->get(route('industries.show', 'financial-services'));

        $response
            ->assertOk()
            ->assertViewIs('industries.show')
            ->assertSee('Financial Services')
            ->assertSee('Real-time fraud detection')
            ->assertSee('PCI DSS')
            ->assertSee('Talk to an Advisor');
    }

    public function test_unknown_industry_returns_not_found(): void
    {
        $response = $this->get(route('industries.show', 'unknown-industry'));

        $response->assertNotFound();
    }
}
