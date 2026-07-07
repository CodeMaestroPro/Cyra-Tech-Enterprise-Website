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
            ->assertSee('End-to-end capabilities for modern enterprises')
            ->assertSee('Digital Transformation')
            ->assertSee('Cloud & Infrastructure')
            ->assertSee('How Cyra-Tech delivers enterprise solutions')
            ->assertSee('data-solution-filter', false);
    }

    public function test_solution_detail_page_renders_offering(): void
    {
        $response = $this->get(route('solutions.show', 'cybersecurity'));

        $response
            ->assertOk()
            ->assertViewIs('solutions.show')
            ->assertSee('Cybersecurity')
            ->assertSee('Zero-trust architecture')
            ->assertSee('Request Consultation');
    }

    public function test_unknown_solution_returns_not_found(): void
    {
        $response = $this->get(route('solutions.show', 'unknown-solution'));

        $response->assertNotFound();
    }
}
