<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_community_page_renders_catalog_and_values(): void
    {
        $response = $this->get(route('community'));

        $response
            ->assertOk()
            ->assertViewIs('community.index')
            ->assertSee('Join builders, leaders, and innovators shaping what\'s next')
            ->assertSee('Cyra Connect Forum')
            ->assertSee('Cyra Innovation Summit')
            ->assertSee('A community built on collaboration and excellence');
    }

    public function test_community_detail_page_renders_program(): void
    {
        $response = $this->get(route('community.show', 'cyra-connect-forum'));

        $response
            ->assertOk()
            ->assertViewIs('community.show')
            ->assertSee('Cyra Connect Forum')
            ->assertSee('Executive peer networking')
            ->assertSee('Monthly — third Thursday')
            ->assertSee('Join the Community');
    }

    public function test_unknown_program_returns_not_found(): void
    {
        $response = $this->get(route('community.show', 'unknown-program'));

        $response->assertNotFound();
    }
}
