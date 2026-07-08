<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OptimizationApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_optimization_dashboard_api_requires_authentication(): void
    {
        $this->getJson(route('api.optimization.dashboard'))->assertUnauthorized();
    }

    public function test_optimization_dashboard_api_returns_metrics_for_admin(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->getJson(route('api.optimization.dashboard'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'summary' => [
                        'health_score',
                        'modules_completed',
                        'modules_total',
                        'feature_tests',
                        'feature_test_files',
                        'seo_score',
                        'platform_status',
                    ],
                    'health_checks',
                    'module_qa',
                    'test_suites',
                    'performance_checks',
                    'seo_checklist',
                    'recommendations',
                    'optimization_actions',
                    'insights',
                ],
            ])
            ->assertJsonPath('data.summary.modules_total', 25)
            ->assertJsonPath('data.summary.modules_completed', 25);
    }

    public function test_optimization_action_api_runs_clear_cache(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->postJson(route('api.optimization.actions'), [
            'action' => 'clear-cache',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.action', 'clear-cache');
    }

    public function test_optimization_action_api_rejects_invalid_action(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $this->actingAs($admin)->postJson(route('api.optimization.actions'), [
            'action' => 'invalid-action',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['action']);
    }
}
