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
    public function getAdminCatalog(?string $category = null): array
    {
        $collection = $this->partnerProgramRepository->getAllPrograms();

        if ($category !== null && $category !== 'all') {
            $collection = $collection->where('category', $category)->values();
        }

        $programs = $collection
            ->map(fn (PartnerProgram $program) => $this->formatAdminProgram($program))
            ->values()
            ->all();

        return [
            'summary' => [
                'total' => count($programs),
                'active' => collect($programs)->where('is_active', true)->count(),
                'inactive' => collect($programs)->where('is_active', false)->count(),
                'featured' => collect($programs)->where('is_featured', true)->count(),
            ],
            'categories' => $this->getCategoryOptions(),
            'programs' => $programs,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAdminProgram(string $slug): ?array
    {
        $program = $this->partnerProgramRepository->findBySlug($slug);

        if ($program === null) {
            return null;
        }

        return $this->formatAdminProgram($program, detailed: true);
    }

    /**
     * @return array<string, mixed>
     */
    public function getFormOptions(): array
    {
        return [
            'categories' => $this->getCategoryOptions(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createProgram(array $data): array
    {
        $program = $this->partnerProgramRepository->createProgram([
            'slug' => $data['slug'],
            'category' => $data['category'],
            'title' => $data['title'],
            'partner_type' => $data['partner_type'],
            'region' => $data['region'],
            'engagement_model' => $data['engagement_model'],
            'tagline' => $data['tagline'],
            'summary' => $data['summary'],
            'description' => $data['description'],
            'benefits' => $this->parseListField($data['benefits'] ?? null),
            'requirements' => $this->parseListField($data['requirements'] ?? null),
            'enablement' => $this->parseListField($data['enablement'] ?? null),
            'badge' => $data['badge'] ?? null,
            'icon' => $data['icon'] ?? 'spark',
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? true),
            'is_featured' => (bool) ($data['is_featured'] ?? false),
        ]);

        return $this->formatAdminProgram($program, detailed: true);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateProgram(string $slug, array $data): ?array
    {
        $program = $this->partnerProgramRepository->findBySlug($slug);

        if ($program === null) {
            return null;
        }

        $program = $this->partnerProgramRepository->updateProgram($program, [
            'category' => $data['category'],
            'title' => $data['title'],
            'partner_type' => $data['partner_type'],
            'region' => $data['region'],
            'engagement_model' => $data['engagement_model'],
            'tagline' => $data['tagline'],
            'summary' => $data['summary'],
            'description' => $data['description'],
            'benefits' => $this->parseListField($data['benefits'] ?? null),
            'requirements' => $this->parseListField($data['requirements'] ?? null),
            'enablement' => $this->parseListField($data['enablement'] ?? null),
            'badge' => $data['badge'] ?? null,
            'icon' => $data['icon'] ?? 'spark',
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
            'is_featured' => (bool) ($data['is_featured'] ?? false),
        ]);

        return $this->formatAdminProgram($program, detailed: true);
    }

    public function deleteProgram(string $slug): bool
    {
        $program = $this->partnerProgramRepository->findBySlug($slug);

        if ($program === null) {
            return false;
        }

        $this->partnerProgramRepository->deleteProgram($program);

        return true;
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

    /**
     * @return array<string, mixed>
     */
    private function formatAdminProgram(PartnerProgram $program, bool $detailed = false): array
    {
        $formatted = [
            ...$this->formatProgram($program),
            'id' => $program->id,
            'sort_order' => $program->sort_order,
            'is_active' => $program->is_active,
            'edit_url' => route('admin.partners.edit', $program->slug),
            'public_url' => route('partner-hub.show', $program->slug),
        ];

        if ($detailed) {
            $formatted['benefits_text'] = $this->formatListField($program->benefits);
            $formatted['requirements_text'] = $this->formatListField($program->requirements);
            $formatted['enablement_text'] = $this->formatListField($program->enablement);
        }

        return $formatted;
    }

    /**
     * @return list<array<string, string>>
     */
    private function getCategoryOptions(): array
    {
        return collect(config('cyra.partner_hub.categories', []))
            ->reject(fn (array $category) => ($category['slug'] ?? '') === 'all')
            ->values()
            ->all();
    }

    /**
     * @param  list<string>|null  $items
     */
    private function formatListField(?array $items): string
    {
        return collect($items ?? [])
            ->filter()
            ->implode("\n");
    }

    private function parseListField(?string $value): array
    {
        if ($value === null || trim($value) === '') {
            return [];
        }

        return collect(preg_split('/\R/', $value) ?: [])
            ->map(fn (string $line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
