<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SolutionsPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_solutions_page_renders_catalog_and_process(): void
    {
        $response = $this->get(route('solutions'));

        $response
            ->assertOk()
            ->assertViewIs('solutions.index')
            ->assertSee('Practical software solutions for growing organizations')
            ->assertSee('Custom Software Development')
            ->assertSee('Operations & Inventory Systems')
            ->assertSee('How we take ideas to working software')
            ->assertSee('data-solution-filter', false);
    }

    public function test_solution_detail_page_renders_offering(): void
    {
        $response = $this->get(route('solutions.show', 'custom-software-development'));

        $response
            ->assertOk()
            ->assertViewIs('solutions.show')
            ->assertSee('Custom Software Development')
            ->assertSee('Web application development')
            ->assertSee('Start a Project');
    }

    public function test_unknown_solution_returns_not_found(): void
    {
        $response = $this->get(route('solutions.show', 'unknown-solution'));

        $response->assertNotFound();
    }
}
