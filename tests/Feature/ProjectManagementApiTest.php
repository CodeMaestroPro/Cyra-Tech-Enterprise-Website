<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectManagementApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_projects_api_requires_authentication(): void
    {
        $this->getJson(route('api.projects.index'))->assertUnauthorized();
    }

    public function test_projects_api_returns_portfolio_for_admin(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->getJson(route('api.projects.index'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'summary' => [
                        'total',
                        'in_progress',
                        'completed',
                        'on_hold',
                        'average_progress',
                        'open_tasks',
                    ],
                    'statuses',
                    'phases',
                    'priorities',
                    'task_statuses',
                    'projects',
                ],
            ])
            ->assertJsonPath('data.summary.total', fn ($value) => $value >= 5);
    }

    public function test_projects_show_api_returns_project_with_tasks(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->getJson(route('api.projects.show', 'PRJ-SEED-001'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.reference', 'PRJ-SEED-001')
            ->assertJsonPath('data.name', 'MediCore System')
            ->assertJsonStructure([
                'data' => [
                    'tasks',
                ],
            ]);
    }

    public function test_projects_tasks_api_returns_task_board(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->getJson(route('api.projects.tasks'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.summary.total', fn ($value) => $value >= 5);
    }

    public function test_projects_create_api_creates_project(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();
        $before = Project::query()->count();

        $response = $this->actingAs($admin)->postJson(route('api.projects.store'), [
            'name' => 'API Delivery Program',
            'client_name' => 'API Client',
            'status' => 'planning',
            'phase' => 'discovery',
            'priority' => 'high',
            'progress' => 5,
            'budget' => 4800000,
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'API Delivery Program');

        $this->assertSame($before + 1, Project::query()->count());
    }

    public function test_projects_task_api_creates_task(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();
        $before = ProjectTask::query()->count();

        $response = $this->actingAs($admin)->postJson(route('api.projects.tasks.store', 'PRJ-SEED-002'), [
            'title' => 'API Task Item',
            'status' => 'pending',
            'priority' => 'medium',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'API Task Item');

        $this->assertSame($before + 1, ProjectTask::query()->count());
    }
}
