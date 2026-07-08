<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectManagementAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_projects_portfolio(): void
    {
        $this->get(route('admin.projects.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_projects_portfolio(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.projects.index'));

        $response
            ->assertOk()
            ->assertViewIs('admin.projects.index')
            ->assertSee('Project Management')
            ->assertSee('MediCore System')
            ->assertSee('Avg. Progress');
    }

    public function test_admin_can_view_task_board(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.projects.tasks'));

        $response
            ->assertOk()
            ->assertViewIs('admin.projects.tasks')
            ->assertSee('Project Tasks')
            ->assertSee('Finalize integration architecture');
    }

    public function test_manager_can_view_projects_portfolio(): void
    {
        $manager = User::factory()->create(['email' => 'manager-projects@cyratech.com']);
        $manager->syncRoles(['manager']);

        $this->actingAs($manager)->get(route('admin.projects.index'))->assertOk();
    }

    public function test_viewer_cannot_access_projects_portfolio(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-projects@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)->get(route('admin.projects.index'))->assertForbidden();
    }

    public function test_admin_can_create_project(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->post(route('admin.projects.store'), [
            'name' => 'Test Delivery Program',
            'client_name' => 'Test Client',
            'status' => 'planning',
            'phase' => 'discovery',
            'priority' => 'medium',
            'progress' => 10,
            'budget' => 2500000,
            'description' => 'Test project created from admin form.',
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Delivery Program',
            'client_name' => 'Test Client',
            'status' => 'planning',
        ]);
    }

    public function test_admin_can_add_task_to_project(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->post(route('admin.projects.tasks.store', 'PRJ-SEED-001'), [
            'title' => 'Security audit checklist',
            'status' => 'pending',
            'priority' => 'high',
            'due_date' => now()->addDays(10)->toDateString(),
        ]);

        $response
            ->assertRedirect(route('admin.projects.edit', 'PRJ-SEED-001'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('project_tasks', [
            'title' => 'Security audit checklist',
            'status' => 'pending',
        ]);
    }
}
