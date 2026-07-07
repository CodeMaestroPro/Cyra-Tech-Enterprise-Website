<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatformApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_platform_status_endpoint_returns_configuration(): void
    {
        $response = $this->getJson(route('api.platform.status'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Cyra-Tech')
            ->assertJsonPath('data.stack.backend', 'Laravel 12')
            ->assertJsonPath('data.stack.frontend', 'JavaScript + Blade')
            ->assertJsonPath('data.modules.total', 25)
            ->assertJsonPath('data.modules.completed', 6);
    }

    public function test_platform_modules_endpoint_returns_all_modules(): void
    {
        $response = $this->getJson(route('api.platform.modules'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(25, 'data')
            ->assertJsonPath('data.0.slug', 'project-initialization')
            ->assertJsonPath('data.0.status', 'completed');
    }
}
