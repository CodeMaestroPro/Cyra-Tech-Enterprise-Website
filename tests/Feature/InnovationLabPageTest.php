<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InnovationLabPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_innovation_lab_page_renders_catalog_and_methodology(): void
    {
        $response = $this->get(route('innovation-lab'));

        $response
            ->assertOk()
            ->assertViewIs('innovation-lab.index')
            ->assertSee('Prototype the future before your market demands it')
            ->assertSee('AI Copilot Studio')
            ->assertSee('Emerging Tech PoC Lab')
            ->assertSee('From hypothesis to production-ready innovation');
    }

    public function test_innovation_lab_detail_page_renders_initiative(): void
    {
        $response = $this->get(route('innovation-lab.show', 'ai-copilot-studio'));

        $response
            ->assertOk()
            ->assertViewIs('innovation-lab.show')
            ->assertSee('AI Copilot Studio')
            ->assertSee('Responsible AI guardrails')
            ->assertSee('Production readiness roadmap')
            ->assertSee('Book a Lab Session');
    }

    public function test_unknown_initiative_returns_not_found(): void
    {
        $response = $this->get(route('innovation-lab.show', 'unknown-initiative'));

        $response->assertNotFound();
    }
}
