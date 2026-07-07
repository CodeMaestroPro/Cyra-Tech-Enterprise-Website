<?php

namespace App\Services;

use App\Models\IndustryVertical;
use App\Repositories\IndustryVerticalRepository;

class IndustryService extends BaseService
{
    public function __construct(
        private readonly IndustryVerticalRepository $industryVerticalRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getIndustries(): array
    {
        $verticals = $this->industryVerticalRepository
            ->getActiveVerticals()
            ->map(fn (IndustryVertical $vertical) => $this->formatVertical($vertical))
            ->values()
            ->all();

        return [
            'seo' => $this->getSeoMeta(),
            'hero' => config('cyra.industries.hero', []),
            'categories' => config('cyra.industries.categories', []),
            'verticals' => $verticals,
            'featured' => collect($verticals)->where('is_featured', true)->values()->all(),
            'expertise' => config('cyra.industries.expertise', []),
            'cta' => config('cyra.industries.cta', []),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getIndustry(string $slug): ?array
    {
        $vertical = $this->industryVerticalRepository->findActiveBySlug($slug);

        if ($vertical === null) {
            return null;
        }

        return $this->formatVertical($vertical);
    }

    /**
     * @return array<string, mixed>
     */
    public function getSeoMeta(): array
    {
        $seo = config('cyra.industries.seo', []);

        return [
            'title' => $seo['title'] ?? 'Industries | '.config('cyra.name'),
            'description' => $seo['description'] ?? 'Explore Cyra-Tech industry expertise across regulated, public-sector, commercial, and industrial verticals.',
            'keywords' => $seo['keywords'] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatVertical(IndustryVertical $vertical): array
    {
        $categoryLabel = collect(config('cyra.industries.categories', []))
            ->firstWhere('slug', $vertical->category)['label'] ?? ucfirst(str_replace('-', ' ', $vertical->category));

        return [
            'slug' => $vertical->slug,
            'category' => $vertical->category,
            'category_label' => $categoryLabel,
            'title' => $vertical->title,
            'tagline' => $vertical->tagline,
            'summary' => $vertical->summary,
            'description' => $vertical->description,
            'challenges' => $vertical->challenges ?? [],
            'capabilities' => $vertical->capabilities ?? [],
            'compliance' => $vertical->compliance ?? [],
            'icon' => $vertical->icon,
            'is_featured' => $vertical->is_featured,
        ];
    }
}
