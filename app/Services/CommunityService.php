<?php

namespace App\Services;

use App\Models\CommunityProgram;
use App\Repositories\CommunityProgramRepository;

class CommunityService extends BaseService
{
    public function __construct(
        private readonly CommunityProgramRepository $communityProgramRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getCommunity(): array
    {
        $programs = $this->communityProgramRepository
            ->getActivePrograms()
            ->map(fn (CommunityProgram $program) => $this->formatProgram($program))
            ->values()
            ->all();

        return [
            'seo' => $this->getSeoMeta(),
            'hero' => config('cyra.community.hero', []),
            'categories' => config('cyra.community.categories', []),
            'programs' => $programs,
            'featured' => collect($programs)->where('is_featured', true)->values()->all(),
            'values' => config('cyra.community.values', []),
            'cta' => config('cyra.community.cta', []),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getProgram(string $slug): ?array
    {
        $program = $this->communityProgramRepository->findActiveBySlug($slug);

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
        $seo = config('cyra.community.seo', []);

        return [
            'title' => $seo['title'] ?? 'Community | '.config('cyra.name'),
            'description' => $seo['description'] ?? 'Join the Cyra-Tech global community of practitioners, partners, and enterprise leaders.',
            'keywords' => $seo['keywords'] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatProgram(CommunityProgram $program): array
    {
        $categoryLabel = collect(config('cyra.community.categories', []))
            ->firstWhere('slug', $program->category)['label'] ?? ucfirst(str_replace('-', ' ', $program->category));

        return [
            'slug' => $program->slug,
            'category' => $program->category,
            'category_label' => $categoryLabel,
            'title' => $program->title,
            'tagline' => $program->tagline,
            'summary' => $program->summary,
            'description' => $program->description,
            'benefits' => $program->benefits ?? [],
            'activities' => $program->activities ?? [],
            'membership' => $program->membership,
            'schedule' => $program->schedule,
            'badge' => $program->badge,
            'icon' => $program->icon,
            'is_featured' => $program->is_featured,
        ];
    }
}
