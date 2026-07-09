<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersRolesAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_users_roles(): void
    {
        $this->get(route('admin.users-roles.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_users_roles(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.users-roles.index'));

        $response
            ->assertOk()
            ->assertViewIs('admin.users-roles.index')
            ->assertSee('Users & Roles')
            ->assertSee('Platform Users')
            ->assertSee('Roles & Permissions')
            ->assertSee('Super Administrator')
            ->assertSee('Permission Groups')
            ->assertSee('Create User')
            ->assertSee(config('cyra.admin.email'));
    }

    public function test_manager_without_roles_permission_cannot_access_users_roles(): void
    {
        $manager = User::factory()->create(['email' => 'manager-ur@cyratech.com']);
        $manager->syncRoles(['manager']);

        $this->actingAs($manager)->get(route('admin.users-roles.index'))->assertForbidden();
    }

    public function test_viewer_without_roles_permission_cannot_access_users_roles(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-ur@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)->get(route('admin.users-roles.index'))->assertForbidden();
    }

    public function test_super_admin_can_create_user_with_admin_role(): void
    {
        $superAdmin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($superAdmin)->post(route('admin.users-roles.store'), [
            'name' => 'Platform Admin',
            'email' => 'platform-admin@cyratech.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_active' => '1',
            'roles' => ['admin'],
        ]);

        $createdUser = User::query()->where('email', 'platform-admin@cyratech.com')->firstOrFail();

        $response
            ->assertRedirect(route('admin.users-roles.edit', $createdUser))
            ->assertSessionHas('success');

        $this->assertTrue($createdUser->hasRole('admin'));
        $this->assertTrue($createdUser->is_active);
    }

    public function test_super_admin_can_update_user_roles(): void
    {
        $superAdmin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $target = User::factory()->create([
            'email' => 'role-target@cyratech.com',
            'is_active' => true,
        ]);
        $target->syncRoles(['viewer']);

        $response = $this->actingAs($superAdmin)->put(route('admin.users-roles.update', $target), [
            'name' => $target->name,
            'email' => $target->email,
            'is_active' => '1',
            'roles' => ['manager'],
        ]);

        $response
            ->assertRedirect(route('admin.users-roles.edit', $target))
            ->assertSessionHas('success');

        $target->refresh();
        $this->assertTrue($target->hasRole('manager'));
        $this->assertFalse($target->hasRole('viewer'));
    }

    public function test_manager_cannot_create_users(): void
    {
        $manager = User::factory()->create(['email' => 'manager-create@cyratech.com']);
        $manager->syncRoles(['manager']);

        $this->actingAs($manager)
            ->get(route('admin.users-roles.create'))
            ->assertForbidden();

        $this->actingAs($manager)
            ->post(route('admin.users-roles.store'), [
                'name' => 'Blocked User',
                'email' => 'blocked@cyratech.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertForbidden();
    }

    public function test_super_admin_cannot_delete_own_account(): void
    {
        $superAdmin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $this->actingAs($superAdmin)
            ->delete(route('admin.users-roles.destroy', $superAdmin))
            ->assertForbidden();
    }

    public function test_super_admin_can_delete_other_users(): void
    {
        $superAdmin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $target = User::factory()->create(['email' => 'delete-me@cyratech.com']);
        $target->syncRoles(['viewer']);

        $this->actingAs($superAdmin)
            ->delete(route('admin.users-roles.destroy', $target))
            ->assertRedirect(route('admin.users-roles.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['email' => 'delete-me@cyratech.com']);
    }
}
