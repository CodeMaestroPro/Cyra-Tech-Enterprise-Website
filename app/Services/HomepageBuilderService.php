<?php

namespace App\Services;

use App\Models\HomepageSection;
use App\Repositories\HomepageSectionRepository;

class HomepageBuilderService extends BaseService
{
    public function __construct(
        private readonly HomepageSectionRepository $homepageSectionRepository,
        private readonly HomepageService $homepageService,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getWorkspace(): array
    {
        $configured = config('cyra.homepage_builder', []);
        $typeMeta = $configured['section_type_meta'] ?? [];
        $sections = $this->homepageSectionRepository->getAllSections();

        $mappedSections = $sections
            ->map(fn (HomepageSection $section) => $this->formatSection($section, $typeMeta))
            ->values()
            ->all();

        $activeCount = $sections->where('is_active', true)->count();

        return [
            'summary' => [
                'total_sections' => $sections->count(),
                'active_sections' => $activeCount,
                'inactive_sections' => $sections->count() - $activeCount,
                'section_types' => $sections->pluck('type')->unique()->count(),
            ],
            'description' => $configured['summary'] ?? 'Manage and preview the public marketing homepage.',
            'seo' => $this->homepageService->getSeoMeta(),
            'preview_url' => route('home'),
            'sections' => $mappedSections,
            'section_types' => $this->buildSectionTypeBreakdown($sections, $typeMeta),
            'quick_links' => $this->buildQuickLinks($configured['quick_links'] ?? []),
            'assets' => $this->buildAssets($configured['assets'] ?? []),
            'workspace_notes' => $configured['workspace_notes'] ?? [],
        ];
    }

    /**
     * @param  array<string, array<string, string>>  $typeMeta
     * @return array<string, mixed>
     */
    private function formatSection(HomepageSection $section, array $typeMeta): array
    {
        $meta = $typeMeta[$section->type] ?? [];

        return [
            'id' => $section->id,
            'slug' => $section->slug,
            'type' => $section->type,
            'type_label' => $meta['label'] ?? ucfirst(str_replace('-', ' ', $section->type)),
            'type_icon' => $meta['icon'] ?? 'cube',
            'type_description' => $meta['description'] ?? '',
            'eyebrow' => $section->eyebrow,
            'title' => $section->title,
            'description' => $section->description,
            'sort_order' => $section->sort_order,
            'is_active' => $section->is_active,
            'content_summary' => $this->summarizeContent($section->content ?? []),
            'updated_at' => $section->updated_at?->diffForHumans(),
        ];
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Collection<int, HomepageSection>  $sections
     * @param  array<string, array<string, string>>  $typeMeta
     * @return list<array<string, mixed>>
     */
    private function buildSectionTypeBreakdown($sections, array $typeMeta): array
    {
        return $sections
            ->groupBy('type')
            ->map(function ($group, string $type) use ($typeMeta) {
                $meta = $typeMeta[$type] ?? [];

                return [
                    'type' => $type,
                    'label' => $meta['label'] ?? ucfirst(str_replace('-', ' ', $type)),
                    'icon' => $meta['icon'] ?? 'cube',
                    'count' => $group->count(),
                    'active_count' => $group->where('is_active', true)->count(),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  list<array<string, mixed>>  $links
     * @return list<array<string, mixed>>
     */
    private function buildQuickLinks(array $links): array
    {
        return collect($links)
            ->map(function (array $link) {
                $route = $link['route'] ?? null;

                return [
                    'label' => $link['label'] ?? '',
                    'icon' => $link['icon'] ?? 'link',
                    'description' => $link['description'] ?? '',
                    'href' => $route ? route($route) : ($link['url'] ?? '#'),
                    'external' => $link['external'] ?? false,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  list<array<string, mixed>>  $assets
     * @return list<array<string, mixed>>
     */
    private function buildAssets(array $assets): array
    {
        return collect($assets)
            ->map(function (array $asset) {
                $path = $asset['path'] ?? '';
                $publicPath = public_path($path);

                return [
                    'label' => $asset['label'] ?? basename($path),
                    'path' => $path,
                    'url' => $path !== '' ? asset($path) : null,
                    'exists' => $path !== '' && is_file($publicPath),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $content
     */
    private function summarizeContent(array $content): string
    {
        if ($content === []) {
            return 'No structured content';
        }

        if (isset($content['items']) && is_array($content['items'])) {
            $count = count($content['items']);

            return "{$count} ".($count === 1 ? 'item' : 'items');
        }

        if (isset($content['commitment'], $content['community'])) {
            return 'Commitment + community blocks';
        }

        $keys = array_keys($content);

        if (count($keys) <= 3) {
            return implode(', ', $keys);
        }

        return count($keys).' content fields';
    }
}
