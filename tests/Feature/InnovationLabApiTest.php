<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InnovationLabApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_innovation_lab_api_returns_catalog(): void
    {
        $response = $this->getJson(route('api.innovation-lab.index'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'seo',
                    'hero',
                    'categories',
                    'initiatives',
                    'featured',
                    'methodology',
                    'cta',
                ],
            ])
            ->assertJsonPath('data.initiatives.0.slug', 'ai-copilot-studio');
    }

    public function test_innovation_lab_api_returns_single_initiative(): void
    {
        $response = $this->getJson(route('api.innovation-lab.show', ['slug' => 'design-sprint-studio']));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Design Sprint Studio');
    }

    public function test_innovation_lab_api_returns_not_found_for_invalid_slug(): void
    {
        $response = $this->getJson(route('api.innovation-lab.show', ['slug' => 'missing']));

        $response
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }
}
