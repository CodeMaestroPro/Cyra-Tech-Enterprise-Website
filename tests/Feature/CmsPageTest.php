<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_published_cms_page_renders_public_view(): void
    {
        $response = $this->get(route('pages.show', 'privacy-policy'));

        $response
            ->assertOk()
            ->assertViewIs('pages.show')
            ->assertSee('Privacy Policy')
            ->assertSee('Cyra-Tech respects your privacy');
    }

    public function test_draft_cms_page_is_not_publicly_accessible(): void
    {
        \App\Models\CmsPage::query()
            ->where('slug', 'privacy-policy')
            ->update(['status' => 'draft', 'published_at' => null]);

        $this->get(route('pages.show', 'privacy-policy'))->assertNotFound();
    }

    public function test_unknown_cms_page_returns_not_found(): void
    {
        $this->get(route('pages.show', 'missing-page'))->assertNotFound();
    }
}
