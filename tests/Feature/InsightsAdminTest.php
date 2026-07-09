<?php

namespace Tests\Feature;

use App\Models\InsightArticle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InsightsAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guests_cannot_access_insights_admin(): void
    {
        $this->get(route('admin.insights.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_insights_index(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.insights.index'));

        $response
            ->assertOk()
            ->assertViewIs('admin.insights.index')
            ->assertSee('Insights')
            ->assertSee('The Executive Guide to AI Readiness')
            ->assertSee('Add Article');
    }

    public function test_admin_can_create_insight_article(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $response = $this->actingAs($admin)->post(route('admin.insights.store'), [
            'slug' => 'future-of-enterprise-ai',
            'category' => 'ai',
            'title' => 'The Future of Enterprise AI',
            'tagline' => 'Where AI programs are heading next.',
            'summary' => 'A look at enterprise AI trends.',
            'description' => 'Enterprise AI is evolving rapidly across governance, platforms, and delivery models.',
            'author' => 'Elena Vasquez',
            'read_time' => '5 min read',
            'topics' => "AI governance\nPlatform strategy",
            'takeaways' => "Invest in data quality\nMeasure business outcomes",
            'published_label' => 'July 2026',
            'badge' => 'Brief',
            'icon' => 'ai',
            'sort_order' => 20,
            'is_active' => '1',
            'is_featured' => '1',
        ]);

        $response
            ->assertRedirect(route('admin.insights.edit', 'future-of-enterprise-ai'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('insight_articles', [
            'slug' => 'future-of-enterprise-ai',
            'title' => 'The Future of Enterprise AI',
            'category' => 'ai',
            'is_active' => true,
            'is_featured' => true,
        ]);
    }

    public function test_admin_can_update_insight_article(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();
        $article = InsightArticle::query()->where('slug', 'executive-guide-ai-readiness')->firstOrFail();

        $response = $this->actingAs($admin)->put(route('admin.insights.update', $article->slug), [
            'category' => 'ai',
            'title' => 'The Executive Guide to AI Readiness Updated',
            'tagline' => $article->tagline,
            'summary' => $article->summary,
            'description' => $article->description,
            'author' => $article->author,
            'read_time' => $article->read_time,
            'topics' => implode("\n", $article->topics ?? []),
            'takeaways' => implode("\n", $article->takeaways ?? []),
            'published_label' => $article->published_label,
            'badge' => $article->badge,
            'icon' => $article->icon,
            'sort_order' => $article->sort_order,
            'is_active' => '1',
            'is_featured' => '1',
        ]);

        $response
            ->assertRedirect(route('admin.insights.edit', $article->slug))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('insight_articles', [
            'slug' => 'executive-guide-ai-readiness',
            'title' => 'The Executive Guide to AI Readiness Updated',
        ]);
    }

    public function test_admin_can_delete_insight_article(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $article = InsightArticle::query()->create([
            'slug' => 'temporary-insight',
            'category' => 'data',
            'title' => 'Temporary Insight',
            'tagline' => 'Temporary tagline.',
            'summary' => 'Temporary summary.',
            'description' => 'Temporary description.',
            'author' => 'Test Author',
            'read_time' => '3 min read',
            'topics' => ['Topic one'],
            'takeaways' => ['Takeaway one'],
            'published_label' => 'July 2026',
            'badge' => 'Temp',
            'icon' => 'spark',
            'sort_order' => 99,
            'is_active' => true,
            'is_featured' => false,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.insights.destroy', $article->slug))
            ->assertRedirect(route('admin.insights.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('insight_articles', [
            'slug' => 'temporary-insight',
        ]);
    }

    public function test_created_insight_appears_on_public_insights_hub(): void
    {
        $admin = User::query()->where('email', config('cyra.admin.email'))->firstOrFail();

        $this->actingAs($admin)->post(route('admin.insights.store'), [
            'slug' => 'public-insight-article',
            'category' => 'transformation',
            'title' => 'Public Insight Article',
            'tagline' => 'Visible on the public hub.',
            'summary' => 'Public summary.',
            'description' => 'Public description for insights hub.',
            'author' => 'Cyra-Tech Editorial',
            'read_time' => '4 min read',
            'is_active' => '1',
        ]);

        $this->get(route('insights'))
            ->assertOk()
            ->assertSee('Public Insight Article');
    }
}
