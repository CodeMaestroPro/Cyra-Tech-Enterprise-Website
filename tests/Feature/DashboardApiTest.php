<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_command_center_api_requires_authentication(): void
    {
        $this->getJson(route('api.dashboard.show'))->assertUnauthorized();
    }

    public function test_command_center_api_returns_dashboard_payload_for_admin(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->getJson(route('api.dashboard.show'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'greeting',
                    'datetime',
                    'kpis',
                    'executive_brief',
                    'company_pulse',
                    'website_analytics',
                    'projects',
                    'quick_actions',
                    'upcoming_events',
                    'tasks',
                    'system_status',
                    'recent_activities',
                    'platform',
                ],
            ])
            ->assertJsonPath('data.greeting.name', $admin->name)
            ->assertJsonPath('data.company_pulse.overall_score', fn ($value) => $value > 0);
    }
}
