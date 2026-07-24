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
            ->assertSee('Services that turn ideas into working software')
            ->assertSee('Custom Software Development')
            ->assertSee('Web Application Development')
            ->assertSee('Mobile Application Development')
            ->assertSee('AI')
            ->assertSee('How we take ideas to working software')
            ->assertSee('data-solution-filter', false);
    }

    public function test_solution_detail_page_renders_offering(): void
    {
        $response = $this->get(route('solutions.show', 'ui-ux-design'));

        $response
            ->assertOk()
            ->assertViewIs('solutions.show')
            ->assertSee('UI/UX Design')
            ->assertSee('Wireframes and interactive prototypes')
            ->assertSee('Start a Project');
    }

    public function test_unknown_solution_returns_not_found(): void
    {
        $response = $this->get(route('solutions.show', 'unknown-solution'));

        $response->assertNotFound();
    }
}
