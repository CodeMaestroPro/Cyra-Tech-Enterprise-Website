<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
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
            ->assertSee('Newsletter Subscriptions')
            ->assertSee('No newsletter subscribers yet')
            ->assertSee($user->name)
            ->assertSee('data-admin-command-center-search', false)
            ->assertSee('data-cyra-theme-toggle', false)
            ->assertSee('admin-command-center-index', false);
    }

    public function test_manager_can_access_command_center_dashboard(): void
    {
        $manager = User::factory()->create(['email' => 'manager-dashboard@cyratech.com']);
        $manager->syncRoles(['manager']);

        $this->actingAs($manager)->get(route('admin.dashboard'))->assertOk();
    }

    public function test_dashboard_displays_recent_newsletter_subscribers(): void
    {
        $user = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        NewsletterSubscriber::query()->create([
            'email' => 'dashboard-subscriber@example.com',
            'status' => 'active',
            'source' => 'footer',
            'subscribed_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response
            ->assertOk()
            ->assertSee('Newsletter Subscriptions')
            ->assertSee('dashboard-subscriber@example.com')
            ->assertSee('Footer')
            ->assertSee('Total Active')
            ->assertSee('1', false);
    }
}
