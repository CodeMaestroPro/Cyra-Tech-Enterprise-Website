<?php

namespace App\Services;

use App\Models\AboutPage;
use App\Repositories\AboutPageRepository;
use Illuminate\Support\Facades\Route;

class AboutService extends BaseService
{
    public function __construct(
        private readonly AboutPageRepository $aboutPageRepository,
    ) {
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getPage(string $slug): ?array
    {
        $page = $this->aboutPageRepository->findActiveBySlug($slug);

        if ($page === null) {
            return null;
        }

        return $this->formatPage($page);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNavigation(?string $currentSlug = null): array
    {
        return $this->aboutPageRepository
            ->getActivePages()
            ->map(fn (AboutPage $page) => [
                'slug' => $page->slug,
                'label' => $page->nav_label,
                'route' => $page->route_name,
                'url' => Route::has($page->route_name) ? route($page->route_name) : '#',
                'active' => $page->slug === ($currentSlug ?? 'overview'),
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function getCatalog(): array
    {
        $pages = $this->aboutPageRepository
            ->getActivePages()
            ->map(fn (AboutPage $page) => $this->formatPage($page))
            ->values()
            ->all();

        return [
            'navigation' => $this->getNavigation(),
            'pages' => $pages,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatPage(AboutPage $page): array
    {
        $seo = $page->seo ?? [];

        return [
            'slug' => $page->slug,
            'route' => $page->route_name,
            'nav_label' => $page->nav_label,
            'eyebrow' => $page->eyebrow,
            'title' => $page->title,
            'description' => $page->description,
            'blocks' => $page->content ?? [],
            'seo' => [
                'title' => $seo['title'] ?? $page->title.' | '.config('cyra.name'),
                'description' => $seo['description'] ?? $page->description,
                'keywords' => $seo['keywords'] ?? [],
            ],
        ];
    }
}
