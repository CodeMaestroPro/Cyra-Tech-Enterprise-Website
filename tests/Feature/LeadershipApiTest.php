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
            ->assertJsonPath('data.executives.0.slug', 'sir-alex-addingi')
            ->assertJsonPath('data.executives.1.slug', 'shirgba-joel-k')
            ->assertJsonPath('data.executives.2.slug', 'zaki-asen')
            ->assertJsonPath('data.executives.3.slug', 'pever-collins')
            ->assertJsonPath('data.executives.4.slug', 'terngu-philip')
            ->assertJsonPath('data.executives.5.slug', 'shie-paul');
    }

    public function test_leadership_profile_api_returns_single_leader(): void
    {
        $response = $this->getJson(route('api.leadership.show', ['slug' => 'shirgba-joel-k']));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Shirgba Joel K.')
            ->assertJsonPath('data.title', 'Strategic Partnership Board Member');
    }

    public function test_leadership_profile_api_returns_not_found_for_invalid_slug(): void
    {
        $response = $this->getJson(route('api.leadership.show', ['slug' => 'missing-leader']));

        $response
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }
}
