<?php

namespace App\Services;

use App\Models\CareerOpening;
use App\Repositories\CareerOpeningRepository;

class CareerService extends BaseService
{
    public function __construct(
        private readonly CareerOpeningRepository $careerOpeningRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getCareers(): array
    {
        $openings = $this->careerOpeningRepository
            ->getActiveOpenings()
            ->map(fn (CareerOpening $opening) => $this->formatOpening($opening))
            ->values()
            ->all();

        return [
            'seo' => $this->getSeoMeta(),
            'hero' => config('cyra.careers.hero', []),
            'categories' => config('cyra.careers.categories', []),
            'openings' => $openings,
            'featured' => collect($openings)->where('is_featured', true)->values()->all(),
            'culture' => config('cyra.careers.culture', []),
            'cta' => config('cyra.careers.cta', []),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getOpening(string $slug): ?array
    {
        $opening = $this->careerOpeningRepository->findActiveBySlug($slug);

        if ($opening === null) {
            return null;
        }

        return $this->formatOpening($opening);
    }

    /**
     * @return array<string, mixed>
     */
    public function getSeoMeta(): array
    {
        $seo = config('cyra.careers.seo', []);

        return [
            'title' => $seo['title'] ?? 'Careers | '.config('cyra.name'),
            'description' => $seo['description'] ?? 'Explore open roles at Cyra-Tech and join a team building enterprise technology for global organizations.',
            'keywords' => $seo['keywords'] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatOpening(CareerOpening $opening): array
    {
        $categoryLabel = collect(config('cyra.careers.categories', []))
            ->firstWhere('slug', $opening->category)['label'] ?? ucfirst(str_replace('-', ' ', $opening->category));

        return [
            'slug' => $opening->slug,
            'category' => $opening->category,
            'category_label' => $categoryLabel,
            'title' => $opening->title,
            'department' => $opening->department,
            'location' => $opening->location,
            'work_type' => $opening->work_type,
            'tagline' => $opening->tagline,
            'summary' => $opening->summary,
            'description' => $opening->description,
            'responsibilities' => $opening->responsibilities ?? [],
            'requirements' => $opening->requirements ?? [],
            'experience_level' => $opening->experience_level,
            'badge' => $opening->badge,
            'icon' => $opening->icon,
            'is_featured' => $opening->is_featured,
        ];
    }
}
