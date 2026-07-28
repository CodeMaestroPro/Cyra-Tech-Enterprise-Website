<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadershipPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_leadership_page_renders_executive_team(): void
    {
        $response = $this->get(route('leadership'));

        $response
            ->assertOk()
            ->assertViewIs('leadership.index')
            ->assertSee('Stewards of vision, accountability, and enterprise impact')
            ->assertSee('Sir Alex Addingi')
            ->assertSee('Executive Chairman')
            ->assertSee('Shirgba Joel K.')
            ->assertSee('Strategic Partnership Board Member')
            ->assertSee('Pever Collins')
            ->assertSee('CTO')
            ->assertSee('Terngu Philip')
            ->assertSee('COO')
            ->assertSee('Shie Paul')
            ->assertSee('Software Lead')
            ->assertSee('Accountability built into how we operate')
            ->assertSee('View full profile')
            ->assertDontSee('Dr. Amara Okonkwo')
            ->assertDontSee('Priya Sharma');
    }
}
