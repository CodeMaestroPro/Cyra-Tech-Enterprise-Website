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
    public function getAboutPage(): ?array
    {
        $pages = $this->aboutPageRepository->getActivePages();

        if ($pages->isEmpty()) {
            return null;
        }

        $overview = $pages->firstWhere('slug', 'overview') ?? $pages->first();
        $seo = $overview->seo ?? [];
        $lastIndex = $pages->count() - 1;

        $sections = $pages
            ->values()
            ->map(function (AboutPage $page, int $index) use ($lastIndex) {
                $blocks = collect($page->content ?? [])
                    ->filter(function (array $block) use ($index, $lastIndex) {
                        if (($block['type'] ?? null) !== 'cta') {
                            return true;
                        }

                        return $index === $lastIndex;
                    })
                    ->values()
                    ->all();

                return [
                    'id' => $page->slug,
                    'nav_label' => $page->nav_label,
                    'eyebrow' => $page->eyebrow,
                    'title' => $page->title,
                    'description' => $page->description,
                    'show_heading' => $page->slug !== 'overview',
                    'blocks' => $blocks,
                ];
            })
            ->all();

        return [
            'eyebrow' => $overview->eyebrow,
            'title' => $overview->title,
            'description' => $overview->description,
            'sections' => $sections,
            'seo' => [
                'title' => $seo['title'] ?? $overview->title.' | '.config('cyra.name'),
                'description' => $seo['description'] ?? $overview->description,
                'keywords' => $seo['keywords'] ?? [],
            ],
        ];
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
                'route' => 'about',
                'url' => route('about').'#'.$page->slug,
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
            'page' => $this->getAboutPage(),
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
