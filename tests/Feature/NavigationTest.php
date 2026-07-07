<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_home_page_renders_global_header_and_footer(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertSee('CYRA')
            ->assertSee('Built on Vision. Driven by Intelligence.')
            ->assertSee('Explore Solutions')
            ->assertSee('Stay ahead with Cyra-Tech')
            ->assertSee('All rights reserved.');
    }

    public function test_login_page_does_not_render_public_navigation(): void
    {
        $response = $this->get(route('login'));

        $response
            ->assertOk()
            ->assertDontSee('Primary navigation', false)
            ->assertDontSee('Stay ahead with Cyra-Tech');
    }

    public function test_industries_page_renders_full_industries_module(): void
    {
        $response = $this->get(route('industries'));

        $response
            ->assertOk()
            ->assertViewIs('industries.index')
            ->assertSee('Financial Services');
    }

    public function test_portfolio_page_renders_full_portfolio_module(): void
    {
        $response = $this->get(route('portfolio'));

        $response
            ->assertOk()
            ->assertViewIs('portfolio.index')
            ->assertSee('NovaBank Digital Core');
    }

    public function test_innovation_lab_page_renders_full_innovation_lab_module(): void
    {
        $response = $this->get(route('innovation-lab'));

        $response
            ->assertOk()
            ->assertViewIs('innovation-lab.index')
            ->assertSee('AI Copilot Studio');
    }

    public function test_community_page_renders_full_community_module(): void
    {
        $response = $this->get(route('community'));

        $response
            ->assertOk()
            ->assertViewIs('community.index')
            ->assertSee('Cyra Connect Forum');
    }

    public function test_insights_page_renders_full_insights_module(): void
    {
        $response = $this->get(route('insights'));

        $response
            ->assertOk()
            ->assertViewIs('insights.index')
            ->assertSee('The Executive Guide to AI Readiness');
    }

    public function test_careers_page_renders_full_careers_module(): void
    {
        $response = $this->get(route('careers'));

        $response
            ->assertOk()
            ->assertViewIs('careers.index')
            ->assertSee('Senior Cloud Architect');
    }

    public function test_preview_page_renders_for_public_routes(): void
    {
        $response = $this->get(route('contact'));

        $response
            ->assertOk()
            ->assertViewIs('pages.preview')
            ->assertSee('Contact')
            ->assertSee('Module 04 (Global Navigation)');
    }

    public function test_products_page_renders_full_products_module(): void
    {
        $response = $this->get(route('products'));

        $response
            ->assertOk()
            ->assertViewIs('products.index')
            ->assertSee('Cyra Command');
    }

    public function test_solutions_page_renders_full_solutions_module(): void
    {
        $response = $this->get(route('solutions'));

        $response
            ->assertOk()
            ->assertViewIs('solutions.index')
            ->assertSee('Digital Transformation');
    }

    public function test_about_page_renders_full_about_module(): void
    {
        $response = $this->get(route('about'));

        $response
            ->assertOk()
            ->assertViewIs('about.show')
            ->assertSee('Engineering intelligent enterprise futures');
    }

    public function test_admin_dashboard_renders_command_center_sidebar_groups(): void
    {
        $admin = \App\Models\User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response
            ->assertOk()
            ->assertSee('Command Center')
            ->assertSee('Executive')
            ->assertSee('Digital Headquarters')
            ->assertSee('Component Library');
    }
}
