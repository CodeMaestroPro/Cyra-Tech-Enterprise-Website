<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_homepage_api_returns_sections_and_seo(): void
    {
        $response = $this->getJson(route('api.homepage.show'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'seo' => ['title', 'description', 'keywords'],
                    'sections',
                ],
            ])
            ->assertJsonPath('data.sections.0.slug', 'hero')
            ->assertJsonPath('data.sections.0.type', 'hero');
    }
}
