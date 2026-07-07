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
            ->assertSee('Solutions')
            ->assertSee('Contact Us')
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

    public function test_preview_page_renders_for_public_routes(): void
    {
        $response = $this->get(route('about'));

        $response
            ->assertOk()
            ->assertViewIs('pages.preview')
            ->assertSee('About')
            ->assertSee('Module 04 (Global Navigation)');
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
