<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicantsAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_applicants(): void
    {
        $this->get(route('admin.applicants.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_applicants(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.applicants.index'));

        $response
            ->assertOk()
            ->assertViewIs('admin.applicants.index')
            ->assertSee('Applicants')
            ->assertSee('Application Queue')
            ->assertSee('Hiring Pipeline')
            ->assertSee('Chiamaka Eze')
            ->assertSee('UX / Product Designer')
            ->assertSee('APP-2026-001');
    }

    public function test_manager_can_view_applicants(): void
    {
        $manager = User::factory()->create(['email' => 'manager-app@cyratech.com']);
        $manager->syncRoles(['manager']);

        $this->actingAs($manager)->get(route('admin.applicants.index'))->assertOk();
    }

    public function test_viewer_without_modules_permission_cannot_access_applicants(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-app@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)->get(route('admin.applicants.index'))->assertForbidden();
    }
}
