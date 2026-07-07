<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InitializationPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_initialization_page_renders_with_platform_data(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertViewIs('initialization.index')
            ->assertViewHas('platform')
            ->assertViewHas('modules')
            ->assertSee('Cyra-Tech')
            ->assertSee('Module 01 Complete')
            ->assertSee('JavaScript + Blade')
            ->assertSee('Project Initialization');
    }
}
