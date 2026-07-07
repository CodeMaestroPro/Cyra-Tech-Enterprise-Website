<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_auth_me_endpoint_requires_authentication(): void
    {
        $response = $this->getJson(route('api.auth.me'));

        $response->assertUnauthorized();
    }

    public function test_auth_me_endpoint_returns_user_profile(): void
    {
        $user = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($user)->getJson(route('api.auth.me'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.email', config('cyra.admin.email'))
            ->assertJsonPath('data.primary_role', 'Super Administrator');
    }

    public function test_auth_permissions_endpoint_returns_permission_list(): void
    {
        $user = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($user)->getJson(route('api.auth.permissions'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => ['permissions'],
            ]);
    }
}
