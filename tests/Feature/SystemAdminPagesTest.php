<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemAdminPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guests_cannot_access_system_admin_pages(): void
    {
        $this->get(route('admin.security.index'))->assertRedirect(route('login'));
        $this->get(route('admin.logs.index'))->assertRedirect(route('login'));
        $this->get(route('admin.enterprise-settings.index'))->assertRedirect(route('login'));
        $this->get(route('admin.calendar.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_security_page(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $this->actingAs($admin)->get(route('admin.security.index'))
            ->assertOk()
            ->assertViewIs('admin.security.index')
            ->assertSee('Security')
            ->assertSee('Security Controls')
            ->assertSee('Session Authentication')
            ->assertSee('ISO 27001');
    }

    public function test_admin_can_view_logs_page(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $this->actingAs($admin)->get(route('admin.logs.index'))
            ->assertOk()
            ->assertViewIs('admin.logs.index')
            ->assertSee('Logs')
            ->assertSee('Audit Log')
            ->assertSee('LOG-2026-0142');
    }

    public function test_admin_can_view_enterprise_settings_page(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $this->actingAs($admin)->get(route('admin.enterprise-settings.index'))
            ->assertOk()
            ->assertViewIs('admin.enterprise-settings.index')
            ->assertSee('Enterprise Settings')
            ->assertSee('Platform Identity')
            ->assertSee(config('cyra.name'));
    }

    public function test_admin_can_view_calendar_page(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $this->actingAs($admin)->get(route('admin.calendar.index'))
            ->assertOk()
            ->assertViewIs('admin.calendar.index')
            ->assertSee('Calendar')
            ->assertSee('Project Review Meeting')
            ->assertSee('Innovation Summit Prep Session');
    }

    public function test_viewer_can_view_system_pages_with_dashboard_access(): void
    {
        $viewer = User::factory()->create(['email' => 'viewer-sys@cyratech.com']);
        $viewer->syncRoles(['viewer']);

        $this->actingAs($viewer)->get(route('admin.security.index'))->assertOk();
        $this->actingAs($viewer)->get(route('admin.logs.index'))->assertOk();
        $this->actingAs($viewer)->get(route('admin.enterprise-settings.index'))->assertOk();
        $this->actingAs($viewer)->get(route('admin.calendar.index'))->assertOk();
    }
}
