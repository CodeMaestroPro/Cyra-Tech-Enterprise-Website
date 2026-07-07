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
}
