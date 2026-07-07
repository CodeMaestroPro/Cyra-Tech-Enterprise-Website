<?php

namespace App\Services;

use App\Models\PartnerProgram;
use App\Repositories\PartnerProgramRepository;

class PartnerHubService extends BaseService
{
    public function __construct(
        private readonly PartnerProgramRepository $partnerProgramRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getPartnerHub(): array
    {
        $programs = $this->partnerProgramRepository
            ->getActivePrograms()
            ->map(fn (PartnerProgram $program) => $this->formatProgram($program))
            ->values()
            ->all();

        return [
            'seo' => $this->getSeoMeta(),
            'hero' => config('cyra.partner_hub.hero', []),
            'categories' => config('cyra.partner_hub.categories', []),
            'programs' => $programs,
            'featured' => collect($programs)->where('is_featured', true)->values()->all(),
            'ecosystem' => config('cyra.partner_hub.ecosystem', []),
            'cta' => config('cyra.partner_hub.cta', []),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getProgram(string $slug): ?array
    {
        $program = $this->partnerProgramRepository->findActiveBySlug($slug);

        if ($program === null) {
            return null;
        }

        return $this->formatProgram($program);
    }

    /**
     * @return array<string, mixed>
     */
    public function getSeoMeta(): array
    {
        $seo = config('cyra.partner_hub.seo', []);

        return [
            'title' => $seo['title'] ?? 'Partner Hub | '.config('cyra.name'),
            'description' => $seo['description'] ?? 'Explore Cyra-Tech partner programs for cloud alliances, technology integrations, consulting co-sell, and global delivery.',
            'keywords' => $seo['keywords'] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatProgram(PartnerProgram $program): array
    {
        $categoryLabel = collect(config('cyra.partner_hub.categories', []))
            ->firstWhere('slug', $program->category)['label'] ?? ucfirst(str_replace('-', ' ', $program->category));

        return [
            'slug' => $program->slug,
            'category' => $program->category,
            'category_label' => $categoryLabel,
            'title' => $program->title,
            'partner_type' => $program->partner_type,
            'region' => $program->region,
            'engagement_model' => $program->engagement_model,
            'tagline' => $program->tagline,
            'summary' => $program->summary,
            'description' => $program->description,
            'benefits' => $program->benefits ?? [],
            'requirements' => $program->requirements ?? [],
            'enablement' => $program->enablement ?? [],
            'badge' => $program->badge,
            'icon' => $program->icon,
            'is_featured' => $program->is_featured,
        ];
    }
}
