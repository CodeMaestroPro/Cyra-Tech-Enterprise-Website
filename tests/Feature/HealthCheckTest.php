<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_endpoint_returns_platform_health_payload(): void
    {
        $response = $this->getJson(route('api.health'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'ok')
            ->assertJsonPath('data.service', 'Cyra-Tech')
            ->assertJsonStructure([
                'success',
                'data' => [
                    'status',
                    'service',
                    'version',
                    'timestamp',
                    'checks' => [
                        'application',
                        'database',
                    ],
                ],
            ]);
    }
}
