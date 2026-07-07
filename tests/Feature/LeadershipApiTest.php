<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadershipApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_leadership_api_returns_team_and_governance(): void
    {
        $response = $this->getJson(route('api.leadership.index'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'seo',
                    'hero',
                    'governance',
                    'cta',
                    'executives',
                    'profiles',
                ],
            ])
            ->assertJsonPath('data.executives.0.slug', 'collins-pever');
    }

    public function test_leadership_profile_api_returns_single_leader(): void
    {
        $response = $this->getJson(route('api.leadership.show', ['slug' => 'elena-vasquez']));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Elena Vasquez')
            ->assertJsonPath('data.title', 'Chief Innovation Officer');
    }

    public function test_leadership_profile_api_returns_not_found_for_invalid_slug(): void
    {
        $response = $this->getJson(route('api.leadership.show', ['slug' => 'missing-leader']));

        $response
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }
}
