<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_pages_api_returns_public_catalog(): void
    {
        $response = $this->getJson(route('api.pages.index'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'seo',
                    'templates',
                    'pages',
                ],
            ])
            ->assertJsonPath('data.pages.0.slug', 'privacy-policy');
    }

    public function test_pages_api_returns_single_published_page(): void
    {
        $response = $this->getJson(route('api.pages.show', 'terms-of-service'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.slug', 'terms-of-service')
            ->assertJsonPath('data.title', 'Terms of Service');
    }

    public function test_pages_api_returns_not_found_for_missing_page(): void
    {
        $this->getJson(route('api.pages.show', 'missing-page'))
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }

    public function test_admin_cms_api_requires_authentication(): void
    {
        $this->getJson(route('api.cms.pages.index'))->assertUnauthorized();
    }

    public function test_admin_cms_api_returns_catalog_for_admin_user(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->getJson(route('api.cms.pages.index'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'filters',
                    'templates',
                    'pages',
                    'summary',
                ],
            ])
            ->assertJsonPath('data.summary.total', 6);
    }

    public function test_admin_cms_api_returns_single_page(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->getJson(route('api.cms.pages.show', 'cookie-policy'));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.slug', 'cookie-policy')
            ->assertJsonPath('data.body', fn ($value) => is_string($value) && $value !== '');
    }
}
