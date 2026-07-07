<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InsightsPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_insights_page_renders_catalog_and_editorial(): void
    {
        $response = $this->get(route('insights'));

        $response
            ->assertOk()
            ->assertViewIs('insights.index')
            ->assertSee('Executive perspectives on technology and transformation')
            ->assertSee('The Executive Guide to AI Readiness')
            ->assertSee('Cloud FinOps for Enterprise Scale')
            ->assertSee('Insights grounded in delivery experience');
    }

    public function test_insights_detail_page_renders_article(): void
    {
        $response = $this->get(route('insights.show', 'executive-guide-ai-readiness'));

        $response
            ->assertOk()
            ->assertViewIs('insights.show')
            ->assertSee('The Executive Guide to AI Readiness')
            ->assertSee('Elena Vasquez')
            ->assertSee('Establish AI governance before scaling')
            ->assertSee('Subscribe to Insights');
    }

    public function test_unknown_article_returns_not_found(): void
    {
        $response = $this->get(route('insights.show', 'unknown-article'));

        $response->assertNotFound();
    }
}
