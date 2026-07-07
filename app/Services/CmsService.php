<?php

namespace App\Services;

use App\Models\CmsPage;
use App\Models\User;
use App\Repositories\CmsPageRepository;
use Illuminate\Support\Str;

class CmsService extends BaseService
{
    public function __construct(
        private readonly CmsPageRepository $cmsPageRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getPublicCatalog(): array
    {
        $pages = $this->cmsPageRepository
            ->getPublishedPages()
            ->map(fn (CmsPage $page) => $this->formatPage($page))
            ->values()
            ->all();

        return [
            'seo' => $this->getSeoMeta(),
            'templates' => config('cyra.cms.templates', []),
            'pages' => $pages,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getPublicPage(string $slug): ?array
    {
        $page = $this->cmsPageRepository->findPublishedBySlug($slug);

        if ($page === null) {
            return null;
        }

        return $this->formatPage($page);
    }

    /**
     * @return array<string, mixed>
     */
    public function getAdminCatalog(?string $status = null): array
    {
        $pages = $this->cmsPageRepository
            ->getAllPages($status)
            ->map(fn (CmsPage $page) => $this->formatAdminPage($page))
            ->values()
            ->all();

        return [
            'filters' => config('cyra.cms.statuses', []),
            'templates' => config('cyra.cms.templates', []),
            'pages' => $pages,
            'summary' => [
                'total' => count($pages),
                'published' => collect($pages)->where('status', 'published')->count(),
                'draft' => collect($pages)->where('status', 'draft')->count(),
            ],
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAdminPage(string $slug): ?array
    {
        $page = $this->cmsPageRepository->findBySlug($slug);

        if ($page === null) {
            return null;
        }

        return $this->formatAdminPage($page);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createPage(array $data, User $user): array
    {
        $page = $this->cmsPageRepository->createPage([
            'slug' => $data['slug'],
            'title' => $data['title'],
            'template' => $data['template'],
            'status' => $data['status'] ?? 'draft',
            'eyebrow' => $data['eyebrow'] ?? null,
            'excerpt' => $data['excerpt'] ?? null,
            'description' => $data['description'] ?? null,
            'content' => $this->buildContentBlocks($data),
            'seo' => $this->buildSeoMeta($data),
            'author_id' => $user->id,
            'published_at' => ($data['status'] ?? 'draft') === 'published' ? now() : null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => true,
        ]);

        return $this->formatAdminPage($page);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updatePage(string $slug, array $data): ?array
    {
        $page = $this->cmsPageRepository->findBySlug($slug);

        if ($page === null) {
            return null;
        }

        $status = $data['status'] ?? $page->status;

        $page = $this->cmsPageRepository->updatePage($page, [
            'title' => $data['title'],
            'template' => $data['template'],
            'status' => $status,
            'eyebrow' => $data['eyebrow'] ?? null,
            'excerpt' => $data['excerpt'] ?? null,
            'description' => $data['description'] ?? null,
            'content' => $this->buildContentBlocks($data, $page->content ?? []),
            'seo' => $this->buildSeoMeta($data, $page->seo ?? []),
            'published_at' => $status === 'published' ? ($page->published_at ?? now()) : null,
            'sort_order' => $data['sort_order'] ?? $page->sort_order,
        ]);

        return $this->formatAdminPage($page);
    }

    public function publishPage(string $slug): ?array
    {
        $page = $this->cmsPageRepository->findBySlug($slug);

        if ($page === null) {
            return null;
        }

        $page = $this->cmsPageRepository->updatePage($page, [
            'status' => 'published',
            'published_at' => now(),
        ]);

        return $this->formatAdminPage($page);
    }

    public function unpublishPage(string $slug): ?array
    {
        $page = $this->cmsPageRepository->findBySlug($slug);

        if ($page === null) {
            return null;
        }

        $page = $this->cmsPageRepository->updatePage($page, [
            'status' => 'draft',
            'published_at' => null,
        ]);

        return $this->formatAdminPage($page);
    }

    /**
     * @return array<string, mixed>
     */
    public function getSeoMeta(): array
    {
        $seo = config('cyra.cms.seo', []);

        return [
            'title' => $seo['title'] ?? 'Content | '.config('cyra.name'),
            'description' => $seo['description'] ?? 'Published content pages managed through the Cyra-Tech CMS.',
            'keywords' => $seo['keywords'] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatPage(CmsPage $page): array
    {
        return [
            'slug' => $page->slug,
            'title' => $page->title,
            'template' => $page->template,
            'template_label' => $this->templateLabel($page->template),
            'status' => $page->status,
            'eyebrow' => $page->eyebrow,
            'excerpt' => $page->excerpt,
            'description' => $page->description,
            'blocks' => $page->content ?? [],
            'seo' => $page->seo ?? [],
            'published_at' => $page->published_at?->toIso8601String(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatAdminPage(CmsPage $page): array
    {
        return [
            ...$this->formatPage($page),
            'body' => $this->extractBody($page->content ?? []),
            'sort_order' => $page->sort_order,
            'is_active' => $page->is_active,
            'author' => $page->author?->name,
            'updated_at' => $page->updated_at?->toIso8601String(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<int, mixed>  $existingContent
     * @return array<int, mixed>
     */
    private function buildContentBlocks(array $data, array $existingContent = []): array
    {
        if (! empty($data['content_blocks'])) {
            return $data['content_blocks'];
        }

        if (empty($data['body'])) {
            return $existingContent;
        }

        $paragraphs = collect(preg_split("/\r\n|\r|\n/", trim($data['body'])))
            ->map(fn (string $paragraph) => trim($paragraph))
            ->filter()
            ->values()
            ->all();

        return [
            [
                'type' => 'prose',
                'paragraphs' => $paragraphs,
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $existingSeo
     * @return array<string, mixed>
     */
    private function buildSeoMeta(array $data, array $existingSeo = []): array
    {
        return [
            'title' => $data['seo_title'] ?? $existingSeo['title'] ?? ($data['title'].' | '.config('cyra.name')),
            'description' => $data['seo_description'] ?? $existingSeo['description'] ?? ($data['description'] ?? $data['excerpt'] ?? ''),
            'keywords' => $existingSeo['keywords'] ?? [],
        ];
    }

    /**
     * @param  array<int, mixed>  $blocks
     */
    private function extractBody(array $blocks): string
    {
        $prose = collect($blocks)->firstWhere('type', 'prose');

        if (! is_array($prose) || empty($prose['paragraphs'])) {
            return '';
        }

        return implode("\n\n", $prose['paragraphs']);
    }

    private function templateLabel(string $template): string
    {
        return collect(config('cyra.cms.templates', []))
            ->firstWhere('slug', $template)['label'] ?? ucfirst($template);
    }

    public static function slugify(string $title): string
    {
        return Str::slug($title);
    }
}
