<?php

namespace App\Services;

use App\Models\ClientEngagement;
use App\Models\User;
use App\Repositories\ClientEngagementRepository;

class ClientPortalService extends BaseService
{
    public function __construct(
        private readonly ClientEngagementRepository $clientEngagementRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getPortal(): array
    {
        return [
            'seo' => $this->getSeoMeta(),
            'hero' => config('cyra.client_portal.hero', []),
            'features' => config('cyra.client_portal.features', []),
            'security' => config('cyra.client_portal.security', []),
            'cta' => config('cyra.client_portal.cta', []),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getDashboard(User $user): ?array
    {
        $account = $user->clientAccount;

        if ($account === null || ! $account->is_active) {
            return null;
        }

        $engagements = $this->clientEngagementRepository
            ->getActiveEngagementsForAccount($account->id)
            ->map(fn (ClientEngagement $engagement) => $this->formatEngagement($engagement))
            ->values()
            ->all();

        $activeCount = collect($engagements)->where('status', 'active')->count();
        $averageProgress = collect($engagements)->avg('progress') ?? 0;

        return [
            'account' => [
                'slug' => $account->slug,
                'name' => $account->name,
                'industry' => $account->industry,
                'region' => $account->region,
                'account_manager' => $account->account_manager,
                'support_email' => $account->support_email,
            ],
            'summary' => [
                'total_engagements' => count($engagements),
                'active_engagements' => $activeCount,
                'average_progress' => (int) round($averageProgress),
            ],
            'engagements' => $engagements,
            'support' => config('cyra.client_portal.dashboard_support', []),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getEngagement(User $user, string $slug): ?array
    {
        if ($user->client_account_id === null) {
            return null;
        }

        $engagement = $this->clientEngagementRepository
            ->findActiveBySlugForAccount($user->client_account_id, $slug);

        if ($engagement === null) {
            return null;
        }

        return $this->formatEngagement($engagement);
    }

    /**
     * @return array<string, mixed>
     */
    public function getSeoMeta(): array
    {
        $seo = config('cyra.client_portal.seo', []);

        return [
            'title' => $seo['title'] ?? 'Client Portal | '.config('cyra.name'),
            'description' => $seo['description'] ?? 'Secure client portal for Cyra-Tech engagement tracking, deliverables, and support.',
            'keywords' => $seo['keywords'] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatEngagement(ClientEngagement $engagement): array
    {
        return [
            'slug' => $engagement->slug,
            'portfolio_slug' => $engagement->portfolio_slug,
            'title' => $engagement->title,
            'status' => $engagement->status,
            'status_label' => ucfirst(str_replace('-', ' ', $engagement->status)),
            'phase' => $engagement->phase,
            'progress' => $engagement->progress,
            'tagline' => $engagement->tagline,
            'summary' => $engagement->summary,
            'description' => $engagement->description,
            'milestones' => $engagement->milestones ?? [],
            'deliverables' => $engagement->deliverables ?? [],
            'team' => $engagement->team ?? [],
        ];
    }
}
