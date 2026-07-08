<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OptimizationAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_optimization_dashboard(): void
    {
        $this->get(route('admin.optimization.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_optimization_dashboard(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.optimization.index'));

        $response
            ->assertOk()
            ->assertViewIs('admin.optimization.index')
            ->assertSee('Testing & Optimization')
            ->assertSee('Health Score')
            ->assertSee('Module QA Matrix');
    }

    public function test_manager_can_view_optimization_dashboard(): void
    {
        $manager = User::factory()->create(['email' => 'manager-optimization@cyratech.com']);
        $manager->syncRoles(['manager']);

        $this->actingAs($manager)->get(route('admin.optimization.index'))->assertOk();
    }

    public function test_viewer_cannot_access_optimization_dashboard(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-optimization@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)->get(route('admin.optimization.index'))->assertForbidden();
    }

    public function test_admin_can_run_optimization_action(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->post(route('admin.optimization.actions'), [
            'action' => 'clear-cache',
        ]);

        $response
            ->assertRedirect(route('admin.optimization.index'))
            ->assertSessionHas('success');
    }
}
