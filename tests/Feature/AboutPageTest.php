<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_about_overview_page_renders_company_story(): void
    {
        $response = $this->get(route('about'));

        $response
            ->assertOk()
            ->assertViewIs('about.show')
            ->assertSee('Powerful, user-focused software for modern organizations')
            ->assertSee('Who we are')
            ->assertSee('Our goal at CYRA-TECH')
            ->assertSee('The principles behind every solution we deliver')
            ->assertSee('From idea to scalable product')
            ->assertSee('A technology partner focused on real outcomes')
            ->assertDontSee('aria-label="About section navigation"', false);
    }

    public function test_legacy_about_routes_redirect_to_single_page_sections(): void
    {
        $this->get(route('about.vision-mission'))
            ->assertRedirect('/about#vision-mission');

        $this->get(route('about.values'))
            ->assertRedirect('/about#values');

        $this->get(route('about.history'))
            ->assertRedirect('/about#history');

        $this->get(route('about.why-choose-us'))
            ->assertRedirect('/about#why-choose-us');
    }

    public function test_unknown_about_page_returns_not_found(): void
    {
        $response = $this->get('/about/unknown-page');

        $response->assertNotFound();
    }
}
