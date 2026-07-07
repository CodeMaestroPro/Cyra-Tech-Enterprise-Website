<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_super_admin_has_wildcard_permissions(): void
    {
        $user = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $this->assertTrue($user->hasRole('super-admin'));
        $this->assertTrue($user->hasPermission('users.delete'));
        $this->assertTrue($user->hasPermission('roles.manage'));
    }

    public function test_roles_and_permissions_are_seeded(): void
    {
        $this->assertDatabaseHas('roles', ['slug' => 'super-admin']);
        $this->assertDatabaseHas('roles', ['slug' => 'viewer']);
        $this->assertDatabaseHas('permissions', ['slug' => 'dashboard.access']);
        $this->assertDatabaseHas('permissions', ['slug' => 'users.view']);
    }

    public function test_viewer_cannot_access_roles_api(): void
    {
        $viewer = User::factory()->create([
            'email' => 'viewer@cyratech.com',
            'is_active' => true,
        ]);
        $viewer->syncRoles(['viewer']);

        $response = $this->actingAs($viewer)->getJson(route('api.auth.roles'));

        $response->assertForbidden();
    }
}
