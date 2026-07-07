<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_portfolio_page_renders_catalog_and_impact(): void
    {
        $response = $this->get(route('portfolio'));

        $response
            ->assertOk()
            ->assertViewIs('portfolio.index')
            ->assertSee('Proven outcomes across complex enterprise programs')
            ->assertSee('NovaBank Digital Core')
            ->assertSee('Helix Health Network')
            ->assertSee('Astra Logistics Command')
            ->assertSee('Programs built for measurable enterprise impact');
    }

    public function test_portfolio_detail_page_renders_project(): void
    {
        $response = $this->get(route('portfolio.show', 'novabank-digital-core'));

        $response
            ->assertOk()
            ->assertViewIs('portfolio.show')
            ->assertSee('NovaBank Digital Core')
            ->assertSee('40% faster time-to-market for new products')
            ->assertSee('Digital Transformation')
            ->assertSee('Start a Conversation');
    }

    public function test_unknown_project_returns_not_found(): void
    {
        $response = $this->get(route('portfolio.show', 'unknown-project'));

        $response->assertNotFound();
    }
}
