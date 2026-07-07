<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CareersPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_careers_page_renders_catalog_and_culture(): void
    {
        $response = $this->get(route('careers'));

        $response
            ->assertOk()
            ->assertViewIs('careers.index')
            ->assertSee('Build the future of enterprise technology with us')
            ->assertSee('Senior Cloud Architect')
            ->assertSee('Lead Full-Stack Engineer')
            ->assertSee('A culture of excellence, impact, and growth');
    }

    public function test_careers_detail_page_renders_opening(): void
    {
        $response = $this->get(route('careers.show', 'senior-cloud-architect'));

        $response
            ->assertOk()
            ->assertViewIs('careers.show')
            ->assertSee('Senior Cloud Architect')
            ->assertSee('Design cloud landing zones and reference architectures')
            ->assertSee('Deep experience with AWS, Azure, or GCP')
            ->assertSee('Apply Now');
    }

    public function test_unknown_opening_returns_not_found(): void
    {
        $response = $this->get(route('careers.show', 'unknown-role'));

        $response->assertNotFound();
    }
}
