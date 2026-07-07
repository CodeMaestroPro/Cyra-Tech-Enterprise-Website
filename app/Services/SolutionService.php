<?php

namespace App\Services;

use App\Models\SolutionOffering;
use App\Repositories\SolutionOfferingRepository;

class SolutionService extends BaseService
{
    public function __construct(
        private readonly SolutionOfferingRepository $solutionOfferingRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getSolutions(): array
    {
        $offerings = $this->solutionOfferingRepository
            ->getActiveOfferings()
            ->map(fn (SolutionOffering $offering) => $this->formatOffering($offering))
            ->values()
            ->all();

        return [
            'seo' => $this->getSeoMeta(),
            'hero' => config('cyra.solutions.hero', []),
            'categories' => config('cyra.solutions.categories', []),
            'offerings' => $offerings,
            'featured' => collect($offerings)->where('is_featured', true)->values()->all(),
            'process' => config('cyra.solutions.process', []),
            'cta' => config('cyra.solutions.cta', []),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getOffering(string $slug): ?array
    {
        $offering = $this->solutionOfferingRepository->findActiveBySlug($slug);

        if ($offering === null) {
            return null;
        }

        return $this->formatOffering($offering);
    }

    /**
     * @return array<string, mixed>
     */
    public function getSeoMeta(): array
    {
        $seo = config('cyra.solutions.seo', []);

        return [
            'title' => $seo['title'] ?? 'Solutions | '.config('cyra.name'),
            'description' => $seo['description'] ?? 'Explore Cyra-Tech enterprise solutions for digital transformation, cloud, AI, security, and more.',
            'keywords' => $seo['keywords'] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatOffering(SolutionOffering $offering): array
    {
        $categoryLabel = collect(config('cyra.solutions.categories', []))
            ->firstWhere('slug', $offering->category)['label'] ?? ucfirst($offering->category);

        return [
            'slug' => $offering->slug,
            'category' => $offering->category,
            'category_label' => $categoryLabel,
            'title' => $offering->title,
            'summary' => $offering->summary,
            'description' => $offering->description,
            'capabilities' => $offering->capabilities ?? [],
            'outcomes' => $offering->outcomes ?? [],
            'icon' => $offering->icon,
            'is_featured' => $offering->is_featured,
        ];
    }
}
