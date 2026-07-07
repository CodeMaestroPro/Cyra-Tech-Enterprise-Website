<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_login_page_is_accessible_to_guests(): void
    {
        $response = $this->get(route('login'));

        $response
            ->assertOk()
            ->assertViewIs('auth.login')
            ->assertSee('Sign in to your account');
    }

    public function test_authenticated_users_are_redirected_from_login_page(): void
    {
        $user = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($user)->get(route('login'));

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post(route('login.store'), [
            'email' => config('cyra.admin.email'),
            'password' => config('cyra.admin.password'),
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs(User::query()->where('email', config('cyra.admin.email'))->first());
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->from(route('login'))->post(route('login.store'), [
            'email' => config('cyra.admin.email'),
            'password' => 'wrong-password',
        ]);

        $response
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();
        $user->update(['is_active' => false]);

        $response = $this->from(route('login'))->post(route('login.store'), [
            'email' => config('cyra.admin.email'),
            'password' => config('cyra.admin.password'),
        ]);

        $response->assertRedirect(route('login'))->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
