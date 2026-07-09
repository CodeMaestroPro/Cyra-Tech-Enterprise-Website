<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DesignSystemApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_design_system_tokens_endpoint_returns_palette(): void
    {
        $response = $this->getJson(route('api.design-system.tokens'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.stack', 'Blade + JavaScript + Tailwind CSS 4')
            ->assertJsonPath('data.colors.primary', '#0052ff')
            ->assertJsonStructure([
                'success',
                'data' => [
                    'version',
                    'stack',
                    'colors',
                    'typography',
                    'radii',
                ],
            ]);
    }

    public function test_design_system_catalog_endpoint_returns_components(): void
    {
        $response = $this->getJson(route('api.design-system.catalog'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'tokens',
                    'components',
                    'principles',
                ],
            ]);
    }
}
