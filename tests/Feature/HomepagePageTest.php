<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepagePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_homepage_renders_hero_and_key_sections(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertViewIs('home.index')
            ->assertViewHas('sections')
            ->assertViewHas('seo')
            ->assertSee('INNOVATING TODAY.')
            ->assertSee('EMPOWERING TOMORROW.')
            ->assertSee('images/homepage/hero-visual.png', false)
            ->assertSee('Explore Solutions')
            ->assertSee('Intelligent Solutions. Real Impact.')
            ->assertSee('Cyra HRMS')
            ->assertSee('Building a Better Future Through Technology')
            ->assertSee('images/homepage/our-commitment.png', false)
            ->assertSee('Community Impact')
            ->assertSee('application/ld+json', false);
    }

    public function test_initialization_page_is_available_at_platform_route(): void
    {
        $response = $this->get(route('platform.initialization'));

        $response
            ->assertOk()
            ->assertViewIs('initialization.index')
            ->assertSee('Module 01 Complete');
    }
}
