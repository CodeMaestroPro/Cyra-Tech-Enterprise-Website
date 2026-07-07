<?php

namespace App\Services;

use App\Models\PortfolioProject;
use App\Repositories\PortfolioProjectRepository;

class PortfolioService extends BaseService
{
    public function __construct(
        private readonly PortfolioProjectRepository $portfolioProjectRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getPortfolio(): array
    {
        $projects = $this->portfolioProjectRepository
            ->getActiveProjects()
            ->map(fn (PortfolioProject $project) => $this->formatProject($project))
            ->values()
            ->all();

        return [
            'seo' => $this->getSeoMeta(),
            'hero' => config('cyra.portfolio.hero', []),
            'categories' => config('cyra.portfolio.categories', []),
            'projects' => $projects,
            'featured' => collect($projects)->where('is_featured', true)->values()->all(),
            'impact' => config('cyra.portfolio.impact', []),
            'cta' => config('cyra.portfolio.cta', []),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getProject(string $slug): ?array
    {
        $project = $this->portfolioProjectRepository->findActiveBySlug($slug);

        if ($project === null) {
            return null;
        }

        return $this->formatProject($project);
    }

    /**
     * @return array<string, mixed>
     */
    public function getSeoMeta(): array
    {
        $seo = config('cyra.portfolio.seo', []);

        return [
            'title' => $seo['title'] ?? 'Portfolio | '.config('cyra.name'),
            'description' => $seo['description'] ?? 'Explore Cyra-Tech case studies and proven outcomes across enterprise transformation programs.',
            'keywords' => $seo['keywords'] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatProject(PortfolioProject $project): array
    {
        $categoryLabel = collect(config('cyra.portfolio.categories', []))
            ->firstWhere('slug', $project->category)['label'] ?? ucfirst(str_replace('-', ' ', $project->category));

        return [
            'slug' => $project->slug,
            'category' => $project->category,
            'category_label' => $categoryLabel,
            'title' => $project->title,
            'client_name' => $project->client_name,
            'tagline' => $project->tagline,
            'summary' => $project->summary,
            'description' => $project->description,
            'services' => $project->services ?? [],
            'outcomes' => $project->outcomes ?? [],
            'metrics' => $project->metrics ?? [],
            'duration' => $project->duration,
            'icon' => $project->icon,
            'is_featured' => $project->is_featured,
        ];
    }
}
