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
            ->assertSee('Overview')
            ->assertSee('Vision & Mission');
    }

    public function test_about_subpages_render_expected_content(): void
    {
        $this->get(route('about.vision-mission'))
            ->assertOk()
            ->assertSee('Our Goal')
            ->assertSee('Mission');

        $this->get(route('about.values'))
            ->assertOk()
            ->assertSee('Client focus')
            ->assertSee('Innovation');

        $this->get(route('about.history'))
            ->assertOk()
            ->assertSee('Discover')
            ->assertSee('From idea to scalable product');

        $this->get(route('about.why-choose-us'))
            ->assertOk()
            ->assertSee('User-focused by design')
            ->assertSee('Contact Us');
    }

    public function test_unknown_about_page_returns_not_found(): void
    {
        $response = $this->get('/about/unknown-page');

        $response->assertNotFound();
    }
}
