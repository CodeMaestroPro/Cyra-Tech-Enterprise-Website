<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_community_api_returns_catalog(): void
    {
        $response = $this->getJson(route('api.community.index'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'seo',
                    'hero',
                    'categories',
                    'programs',
                    'featured',
                    'values',
                    'cta',
                ],
            ])
            ->assertJsonPath('data.programs.0.slug', 'cyra-connect-forum');
    }

    public function test_community_api_returns_single_program(): void
    {
        $response = $this->getJson(route('api.community.show', ['slug' => 'developer-guild']));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Developer Guild');
    }

    public function test_community_api_returns_not_found_for_invalid_slug(): void
    {
        $response = $this->getJson(route('api.community.show', ['slug' => 'missing']));

        $response
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }
}
