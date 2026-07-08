<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_admin_can_access_command_center_dashboard(): void
    {
        $user = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response
            ->assertOk()
            ->assertViewIs('admin.dashboard.index')
            ->assertSee('Command Center')
            ->assertSee('AI Executive Brief')
            ->assertSee('Company Pulse')
            ->assertSee('Recent Activities')
            ->assertSee($user->name);
    }

    public function test_manager_can_access_command_center_dashboard(): void
    {
        $manager = User::factory()->create(['email' => 'manager-dashboard@cyratech.com']);
        $manager->syncRoles(['manager']);

        $this->actingAs($manager)->get(route('admin.dashboard'))->assertOk();
    }
}
