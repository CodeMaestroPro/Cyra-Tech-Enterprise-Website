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
            ->assertSee('Collins Pever')
            ->assertSee('Chief Executive Officer')
            ->assertSee('Dr. Amara Okonkwo')
            ->assertSee('Accountability built into how we operate')
            ->assertSee('View full profile');
    }

    public function test_leadership_page_includes_extended_leadership(): void
    {
        $response = $this->get(route('leadership'));

        $response
            ->assertOk()
            ->assertSee('Priya Sharma')
            ->assertSee('Chief Financial Officer');
    }
}
