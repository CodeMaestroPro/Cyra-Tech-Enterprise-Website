<?php

namespace App\Services;

use App\Models\HomepageSection;
use App\Repositories\HomepageSectionRepository;

class HomepageService extends BaseService
{
    public function __construct(
        private readonly HomepageSectionRepository $homepageSectionRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getHomepage(): array
    {
        $sections = $this->homepageSectionRepository
            ->getActiveSections()
            ->map(fn (HomepageSection $section) => $this->formatSection($section))
            ->values()
            ->all();

        return [
            'seo' => $this->getSeoMeta(),
            'sections' => $sections,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getSeoMeta(): array
    {
        $seo = config('cyra.homepage.seo', []);

        return [
            'title' => $seo['title'] ?? config('cyra.name').' | Enterprise Technology Partner',
            'description' => $seo['description'] ?? config('cyra.tagline'),
            'keywords' => $seo['keywords'] ?? [],
            'og_image' => $seo['og_image'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatSection(HomepageSection $section): array
    {
        return [
            'slug' => $section->slug,
            'type' => $section->type,
            'eyebrow' => $section->eyebrow,
            'title' => $section->title,
            'description' => $section->description,
            'content' => $section->content ?? [],
        ];
    }
}
