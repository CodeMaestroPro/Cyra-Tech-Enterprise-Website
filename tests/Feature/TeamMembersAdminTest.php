<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamMembersAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_team_members(): void
    {
        $this->get(route('admin.team-members.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_team_members_catalog(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.team-members.index'));

        $response
            ->assertOk()
            ->assertViewIs('admin.catalog.index')
            ->assertSee('Team Members')
            ->assertSee('David Okafor');
    }

    public function test_admin_can_create_team_member(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.team-members.store'), [
                'name' => 'Test Team Member',
                'slug' => 'test-team-member',
                'title' => 'Solutions Consultant',
                'department' => 'Delivery',
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.team-members.edit', 'test-team-member'));

        $this->assertDatabaseHas('team_members', [
            'slug' => 'test-team-member',
            'name' => 'Test Team Member',
        ]);
    }

    public function test_manager_can_view_team_members(): void
    {
        $manager = User::factory()->create(['email' => 'manager-tm@cyratech.com']);
        $manager->syncRoles(['manager']);

        $this->actingAs($manager)->get(route('admin.team-members.index'))->assertOk();
    }

    public function test_viewer_without_modules_permission_cannot_access_team_members(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-tm@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)->get(route('admin.team-members.index'))->assertForbidden();
    }
}
