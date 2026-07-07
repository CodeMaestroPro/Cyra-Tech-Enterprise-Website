<?php

namespace App\Services;

use App\Models\InnovationInitiative;
use App\Repositories\InnovationInitiativeRepository;

class InnovationLabService extends BaseService
{
    public function __construct(
        private readonly InnovationInitiativeRepository $innovationInitiativeRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getInnovationLab(): array
    {
        $initiatives = $this->innovationInitiativeRepository
            ->getActiveInitiatives()
            ->map(fn (InnovationInitiative $initiative) => $this->formatInitiative($initiative))
            ->values()
            ->all();

        return [
            'seo' => $this->getSeoMeta(),
            'hero' => config('cyra.innovation_lab.hero', []),
            'categories' => config('cyra.innovation_lab.categories', []),
            'initiatives' => $initiatives,
            'featured' => collect($initiatives)->where('is_featured', true)->values()->all(),
            'methodology' => config('cyra.innovation_lab.methodology', []),
            'cta' => config('cyra.innovation_lab.cta', []),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getInitiative(string $slug): ?array
    {
        $initiative = $this->innovationInitiativeRepository->findActiveBySlug($slug);

        if ($initiative === null) {
            return null;
        }

        return $this->formatInitiative($initiative);
    }

    /**
     * @return array<string, mixed>
     */
    public function getSeoMeta(): array
    {
        $seo = config('cyra.innovation_lab.seo', []);

        return [
            'title' => $seo['title'] ?? 'Innovation Lab | '.config('cyra.name'),
            'description' => $seo['description'] ?? 'Explore Cyra-Tech Innovation Lab programs for AI copilots, emerging technology proof-of-concepts, and venture design sprints.',
            'keywords' => $seo['keywords'] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatInitiative(InnovationInitiative $initiative): array
    {
        $categoryLabel = collect(config('cyra.innovation_lab.categories', []))
            ->firstWhere('slug', $initiative->category)['label'] ?? ucfirst(str_replace('-', ' ', $initiative->category));

        return [
            'slug' => $initiative->slug,
            'category' => $initiative->category,
            'category_label' => $categoryLabel,
            'title' => $initiative->title,
            'tagline' => $initiative->tagline,
            'summary' => $initiative->summary,
            'description' => $initiative->description,
            'focus_areas' => $initiative->focus_areas ?? [],
            'deliverables' => $initiative->deliverables ?? [],
            'timeline' => $initiative->timeline,
            'badge' => $initiative->badge,
            'icon' => $initiative->icon,
            'is_featured' => $initiative->is_featured,
        ];
    }
}
