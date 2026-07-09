<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\NavigationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_public_search_index_only_contains_reachable_pages(): void
    {
        $index = app(NavigationService::class)->getPublicSearchIndex();

        $this->assertNotEmpty($index);

        foreach ($index as $entry) {
            $this->assertNotSame('#', $entry['url']);
            $this->assertNotSame('', $entry['url']);

            $response = $this->get($entry['url']);

            $response->assertOk();
        }
    }

    public function test_admin_search_index_only_contains_reachable_pages_for_admin(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();
        $index = app(NavigationService::class)->getAdminSearchIndex($admin);

        $this->assertNotEmpty($index);

        foreach ($index as $entry) {
            $this->assertNotSame('#', $entry['url']);
            $this->assertNotSame('', $entry['url']);

            $response = $this->actingAs($admin)->get($entry['url']);

            $response->assertOk();
        }
    }

    public function test_unpublished_cms_pages_are_excluded_from_public_search_index(): void
    {
        \App\Models\CmsPage::query()
            ->where('slug', 'privacy-policy')
            ->update(['status' => 'draft', 'published_at' => null]);

        $index = app(NavigationService::class)->getPublicSearchIndex();
        $labels = collect($index)->pluck('label');

        $this->assertFalse($labels->contains('Privacy Policy'));
    }
}
