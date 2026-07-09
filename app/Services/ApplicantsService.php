<?php

namespace App\Services;

use App\Models\CareerApplicant;
use App\Repositories\CareerApplicantRepository;
use App\Repositories\CareerOpeningRepository;

class ApplicantsService extends BaseService
{
    public function __construct(
        private readonly CareerApplicantRepository $careerApplicantRepository,
        private readonly CareerOpeningRepository $careerOpeningRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getWorkspace(): array
    {
        $configured = config('cyra.applicants_workspace', []);
        $statusMeta = $configured['status_meta'] ?? [];
        $sourceMeta = $configured['source_meta'] ?? [];
        $applicants = $this->careerApplicantRepository->getAllApplicants();
        $openingTitles = $this->careerOpeningRepository
            ->getActiveOpenings()
            ->pluck('title', 'slug');

        $mappedApplicants = $applicants
            ->map(fn (CareerApplicant $applicant) => $this->formatApplicant($applicant, $statusMeta, $sourceMeta, $openingTitles))
            ->values()
            ->all();

        $activePipeline = collect($mappedApplicants)
            ->whereNotIn('status', ['hired', 'rejected'])
            ->count();

        return [
            'summary' => [
                'total_applicants' => $applicants->count(),
                'active_pipeline' => $activePipeline,
                'new_applications' => $applicants->where('status', 'new')->count(),
                'in_interview' => $applicants->where('status', 'interview')->count(),
                'offers_outstanding' => $applicants->where('status', 'offer')->count(),
                'hired' => $applicants->where('status', 'hired')->count(),
            ],
            'description' => $configured['summary'] ?? 'Review and track career applications.',
            'applicants' => $mappedApplicants,
            'pipeline' => $this->buildPipelineStages($applicants, $statusMeta),
            'role_breakdown' => $this->buildRoleBreakdown($applicants, $openingTitles),
            'recent_applications' => array_slice($mappedApplicants, 0, 5),
            'quick_links' => $this->buildQuickLinks($configured['quick_links'] ?? []),
            'workspace_notes' => $configured['workspace_notes'] ?? [],
        ];
    }

    /**
     * @param  array<string, array<string, string>>  $statusMeta
     * @param  array<string, string>  $sourceMeta
     * @param  \Illuminate\Support\Collection<string, string>  $openingTitles
     * @return array<string, mixed>
     */
    private function formatApplicant(
        CareerApplicant $applicant,
        array $statusMeta,
        array $sourceMeta,
        $openingTitles,
    ): array {
        $status = $statusMeta[$applicant->status] ?? ['label' => ucfirst($applicant->status), 'variant' => 'default', 'icon' => 'clipboard'];
        $nameParts = preg_split('/\s+/', trim($applicant->name)) ?: [];
        $initials = collect($nameParts)
            ->take(2)
            ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
            ->implode('');

        return [
            'id' => $applicant->id,
            'reference' => $applicant->reference,
            'name' => $applicant->name,
            'email' => $applicant->email,
            'phone' => $applicant->phone,
            'location' => $applicant->location,
            'opening_slug' => $applicant->opening_slug,
            'role_title' => $openingTitles[$applicant->opening_slug] ?? ucfirst(str_replace('-', ' ', $applicant->opening_slug)),
            'linkedin_url' => $applicant->linkedin_url,
            'portfolio_url' => $applicant->portfolio_url,
            'cover_letter' => $applicant->cover_letter,
            'resume_filename' => $applicant->resume_filename,
            'status' => $applicant->status,
            'status_label' => $status['label'],
            'status_variant' => $status['variant'] ?? 'default',
            'status_icon' => $status['icon'] ?? 'clipboard',
            'source' => $applicant->source,
            'source_label' => $sourceMeta[$applicant->source] ?? ucfirst(str_replace('-', ' ', $applicant->source)),
            'notes' => $applicant->notes,
            'initials' => $initials,
            'applied_at' => $applicant->applied_at?->format('M j, Y'),
            'applied_ago' => $applicant->applied_at?->diffForHumans(),
            'edit_url' => route('admin.applicants.edit', $applicant->reference),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAdminApplicant(string $reference): ?array
    {
        $applicant = $this->careerApplicantRepository->findByReference($reference);

        if ($applicant === null) {
            return null;
        }

        $configured = config('cyra.applicants_workspace', []);
        $statusMeta = $configured['status_meta'] ?? [];
        $sourceMeta = $configured['source_meta'] ?? [];
        $openingTitles = $this->careerOpeningRepository
            ->getActiveOpenings()
            ->pluck('title', 'slug');

        return $this->formatApplicant($applicant, $statusMeta, $sourceMeta, $openingTitles);
    }

    /**
     * @return list<string>
     */
    public function getStatusOptions(): array
    {
        return ['new', 'screening', 'interview', 'offer', 'hired', 'rejected'];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateApplicant(string $reference, array $data): ?array
    {
        $applicant = $this->careerApplicantRepository->findByReference($reference);

        if ($applicant === null) {
            return null;
        }

        $applicant = $this->careerApplicantRepository->updateApplicant($applicant, [
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);

        return $this->getAdminApplicant($applicant->reference);
    }

    public function deleteApplicant(string $reference): bool
    {
        $applicant = $this->careerApplicantRepository->findByReference($reference);

        if ($applicant === null) {
            return false;
        }

        $this->careerApplicantRepository->deleteApplicant($applicant);

        return true;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Collection<int, CareerApplicant>  $applicants
     * @param  array<string, array<string, string>>  $statusMeta
     * @return list<array<string, mixed>>
     */
    private function buildPipelineStages($applicants, array $statusMeta): array
    {
        $order = ['new', 'screening', 'interview', 'offer', 'hired', 'rejected'];

        return collect($order)
            ->map(function (string $status) use ($applicants, $statusMeta) {
                $meta = $statusMeta[$status] ?? ['label' => ucfirst($status), 'icon' => 'clipboard'];

                return [
                    'status' => $status,
                    'label' => $meta['label'],
                    'icon' => $meta['icon'] ?? 'clipboard',
                    'variant' => $meta['variant'] ?? 'default',
                    'count' => $applicants->where('status', $status)->count(),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Collection<int, CareerApplicant>  $applicants
     * @param  \Illuminate\Support\Collection<string, string>  $openingTitles
     * @return list<array<string, mixed>>
     */
    private function buildRoleBreakdown($applicants, $openingTitles): array
    {
        return $applicants
            ->groupBy('opening_slug')
            ->map(function ($group, string $slug) use ($openingTitles) {
                return [
                    'opening_slug' => $slug,
                    'role_title' => $openingTitles[$slug] ?? ucfirst(str_replace('-', ' ', $slug)),
                    'count' => $group->count(),
                    'active_count' => $group->whereNotIn('status', ['hired', 'rejected'])->count(),
                ];
            })
            ->sortByDesc('count')
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
}
