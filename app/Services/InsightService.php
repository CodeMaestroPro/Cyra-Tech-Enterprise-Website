<?php

namespace App\Services;

use App\Models\InsightArticle;
use App\Repositories\InsightArticleRepository;

class InsightService extends BaseService
{
    public function __construct(
        private readonly InsightArticleRepository $insightArticleRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getInsights(): array
    {
        $articles = $this->insightArticleRepository
            ->getActiveArticles()
            ->map(fn (InsightArticle $article) => $this->formatArticle($article))
            ->values()
            ->all();

        return [
            'seo' => $this->getSeoMeta(),
            'hero' => config('cyra.insights.hero', []),
            'categories' => config('cyra.insights.categories', []),
            'articles' => $articles,
            'featured' => collect($articles)->where('is_featured', true)->values()->all(),
            'editorial' => config('cyra.insights.editorial', []),
            'cta' => config('cyra.insights.cta', []),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getArticle(string $slug): ?array
    {
        $article = $this->insightArticleRepository->findActiveBySlug($slug);

        if ($article === null) {
            return null;
        }

        return $this->formatArticle($article);
    }

    /**
     * @return array<string, mixed>
     */
    public function getAdminWorkspace(): array
    {
        $articles = $this->insightArticleRepository
            ->getAllArticles()
            ->map(fn (InsightArticle $article) => $this->formatAdminArticle($article))
            ->values()
            ->all();

        return [
            'description' => 'Manage thought leadership articles published on the public Insights hub.',
            'summary' => [
                'total' => count($articles),
                'active' => collect($articles)->where('is_active', true)->count(),
                'featured' => collect($articles)->where('is_featured', true)->count(),
            ],
            'categories' => $this->getCategoryOptions(),
            'articles' => $articles,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAdminArticle(string $slug): ?array
    {
        $article = $this->insightArticleRepository->findBySlug($slug);

        if ($article === null) {
            return null;
        }

        return $this->formatAdminArticle($article, detailed: true);
    }

    /**
     * @return array<string, mixed>
     */
    public function getFormOptions(): array
    {
        return [
            'categories' => $this->getCategoryOptions(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createArticle(array $data): array
    {
        $article = $this->insightArticleRepository->createArticle([
            'slug' => $data['slug'],
            'category' => $data['category'],
            'title' => $data['title'],
            'tagline' => $data['tagline'],
            'summary' => $data['summary'],
            'description' => $data['description'],
            'author' => $data['author'],
            'read_time' => $data['read_time'],
            'topics' => $this->parseListField($data['topics'] ?? null),
            'takeaways' => $this->parseListField($data['takeaways'] ?? null),
            'published_label' => $data['published_label'] ?? null,
            'badge' => $data['badge'] ?? null,
            'icon' => $data['icon'] ?? 'spark',
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? true),
            'is_featured' => (bool) ($data['is_featured'] ?? false),
        ]);

        return $this->formatAdminArticle($article, detailed: true);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateArticle(string $slug, array $data): ?array
    {
        $article = $this->insightArticleRepository->findBySlug($slug);

        if ($article === null) {
            return null;
        }

        $article = $this->insightArticleRepository->updateArticle($article, [
            'category' => $data['category'],
            'title' => $data['title'],
            'tagline' => $data['tagline'],
            'summary' => $data['summary'],
            'description' => $data['description'],
            'author' => $data['author'],
            'read_time' => $data['read_time'],
            'topics' => $this->parseListField($data['topics'] ?? null),
            'takeaways' => $this->parseListField($data['takeaways'] ?? null),
            'published_label' => $data['published_label'] ?? null,
            'badge' => $data['badge'] ?? null,
            'icon' => $data['icon'] ?? 'spark',
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
            'is_featured' => (bool) ($data['is_featured'] ?? false),
        ]);

        return $this->formatAdminArticle($article, detailed: true);
    }

    public function deleteArticle(string $slug): bool
    {
        $article = $this->insightArticleRepository->findBySlug($slug);

        if ($article === null) {
            return false;
        }

        $this->insightArticleRepository->deleteArticle($article);

        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSeoMeta(): array
    {
        $seo = config('cyra.insights.seo', []);

        return [
            'title' => $seo['title'] ?? 'Insights | '.config('cyra.name'),
            'description' => $seo['description'] ?? 'Executive perspectives on AI, cloud, security, and enterprise transformation from Cyra-Tech thought leaders.',
            'keywords' => $seo['keywords'] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatArticle(InsightArticle $article): array
    {
        $categoryLabel = collect(config('cyra.insights.categories', []))
            ->firstWhere('slug', $article->category)['label'] ?? ucfirst(str_replace('-', ' ', $article->category));

        return [
            'slug' => $article->slug,
            'category' => $article->category,
            'category_label' => $categoryLabel,
            'title' => $article->title,
            'tagline' => $article->tagline,
            'summary' => $article->summary,
            'description' => $article->description,
            'author' => $article->author,
            'read_time' => $article->read_time,
            'topics' => $article->topics ?? [],
            'takeaways' => $article->takeaways ?? [],
            'published_label' => $article->published_label,
            'badge' => $article->badge,
            'icon' => $article->icon,
            'is_featured' => $article->is_featured,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatAdminArticle(InsightArticle $article, bool $detailed = false): array
    {
        $formatted = [
            ...$this->formatArticle($article),
            'sort_order' => $article->sort_order,
            'is_active' => $article->is_active,
            'edit_url' => route('admin.insights.edit', $article->slug),
            'public_url' => route('insights.show', $article->slug),
        ];

        if ($detailed) {
            $formatted['topics_text'] = $this->formatListField($article->topics);
            $formatted['takeaways_text'] = $this->formatListField($article->takeaways);
        }

        return $formatted;
    }

    /**
     * @return list<array<string, string>>
     */
    private function getCategoryOptions(): array
    {
        return collect(config('cyra.insights.categories', []))
            ->reject(fn (array $category) => ($category['slug'] ?? '') === 'all')
            ->values()
            ->all();
    }

    /**
     * @param  list<string>|null  $items
     */
    private function formatListField(?array $items): string
    {
        return collect($items ?? [])
            ->filter()
            ->implode("\n");
    }

    private function parseListField(?string $value): array
    {
        if ($value === null || trim($value) === '') {
            return [];
        }

        return collect(preg_split('/\R/', $value) ?: [])
            ->map(fn (string $line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
