<?php

namespace App\Services;

class MarketingService extends BaseService
{
    public function __construct(
        private readonly AnalyticsService $analyticsService,
        private readonly CrmService $crmService,
        private readonly MediaLibraryService $mediaLibraryService,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getWorkspace(): array
    {
        $configured = config('cyra.marketing_workspace', []);
        $campaignConfig = config('cyra.marketing.campaigns', []);
        $channelMeta = $configured['channel_meta'] ?? [];
        $statusMeta = $configured['status_meta'] ?? [];
        $rangeDays = (int) config('cyra.command_center.analytics_range_days', 30);

        $analytics = $this->analyticsService->getDashboard($rangeDays);
        $crm = $this->crmService->getPipeline();
        $marketingMedia = $this->mediaLibraryService->getAdminCatalog('marketing');
        $overview = $analytics['overview'] ?? [];
        $crmSummary = $crm['summary'] ?? [];

        $campaigns = collect($campaignConfig)
            ->map(fn (array $campaign) => $this->formatCampaign($campaign, $channelMeta, $statusMeta))
            ->values()
            ->all();

        $activeCampaigns = collect($campaigns)->where('status', 'active')->count();

        return [
            'summary' => [
                'range_label' => $configured['range_label'] ?? "Last {$rangeDays} Days",
                'active_campaigns' => $activeCampaigns,
                'total_campaigns' => count($campaigns),
                'page_views' => number_format((int) ($overview['page_views'] ?? 0)),
                'conversion_rate' => ($overview['conversion_rate'] ?? 0).'%',
                'marketing_leads' => (int) ($crmSummary['total'] ?? 0),
                'form_submissions' => number_format((int) ($overview['form_submissions'] ?? 0)),
                'marketing_assets' => (int) ($marketingMedia['summary']['active'] ?? 0),
            ],
            'description' => $configured['summary'] ?? 'Orchestrate campaigns, content, and growth programs.',
            'campaigns' => $campaigns,
            'channel_breakdown' => $this->buildChannelBreakdown($campaigns),
            'status_breakdown' => $this->buildStatusBreakdown($campaigns, $statusMeta),
            'performance' => [
                'overview' => $overview,
                'traffic_trend' => $analytics['traffic_trend'] ?? [],
                'top_pages' => array_slice($analytics['top_pages'] ?? [], 0, 5),
                'lead_sources' => array_slice($analytics['lead_sources'] ?? [], 0, 5),
            ],
            'content_channels' => $this->buildContentChannels($configured['content_channels'] ?? []),
            'quick_links' => $this->buildQuickLinks($configured['quick_links'] ?? []),
            'workspace_notes' => $configured['workspace_notes'] ?? [],
        ];
    }

    /**
     * @param  array<string, mixed>  $campaign
     * @param  array<string, array<string, string>>  $channelMeta
     * @param  array<string, array<string, string>>  $statusMeta
     * @return array<string, mixed>
     */
    private function formatCampaign(array $campaign, array $channelMeta, array $statusMeta): array
    {
        $channel = $campaign['channel'] ?? 'content';
        $status = $campaign['status'] ?? 'draft';
        $channelInfo = $channelMeta[$channel] ?? ['label' => ucfirst($channel), 'icon' => 'megaphone'];
        $statusInfo = $statusMeta[$status] ?? ['label' => ucfirst($status), 'variant' => 'default', 'icon' => 'megaphone'];
        $metrics = $campaign['metrics'] ?? [];

        return [
            'slug' => $campaign['slug'] ?? '',
            'name' => $campaign['name'] ?? '',
            'channel' => $channel,
            'channel_label' => $channelInfo['label'] ?? ucfirst($channel),
            'channel_icon' => $channelInfo['icon'] ?? 'megaphone',
            'status' => $status,
            'status_label' => $statusInfo['label'] ?? ucfirst($status),
            'status_variant' => $statusInfo['variant'] ?? 'default',
            'objective' => $campaign['objective'] ?? '',
            'audience' => $campaign['audience'] ?? '',
            'budget' => $campaign['budget'] ?? null,
            'period' => $campaign['period'] ?? '',
            'owner' => $campaign['owner'] ?? 'Growth Team',
            'metrics' => [
                'impressions' => number_format((int) ($metrics['impressions'] ?? 0)),
                'clicks' => number_format((int) ($metrics['clicks'] ?? 0)),
                'leads' => number_format((int) ($metrics['leads'] ?? 0)),
            ],
            'raw_metrics' => $metrics,
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $campaigns
     * @return list<array<string, mixed>>
     */
    private function buildChannelBreakdown(array $campaigns): array
    {
        $channelMeta = config('cyra.marketing_workspace.channel_meta', []);

        return collect($campaigns)
            ->groupBy('channel')
            ->map(function ($group, string $channel) use ($channelMeta) {
                $meta = $channelMeta[$channel] ?? ['label' => ucfirst($channel), 'icon' => 'megaphone'];

                return [
                    'channel' => $channel,
                    'label' => $meta['label'] ?? ucfirst($channel),
                    'icon' => $meta['icon'] ?? 'megaphone',
                    'count' => $group->count(),
                    'active_count' => $group->where('status', 'active')->count(),
                    'total_leads' => $group->sum(fn (array $c) => (int) ($c['raw_metrics']['leads'] ?? 0)),
                ];
            })
            ->sortByDesc('count')
            ->values()
            ->all();
    }

    /**
     * @param  list<array<string, mixed>>  $campaigns
     * @param  array<string, array<string, string>>  $statusMeta
     * @return list<array<string, mixed>>
     */
    private function buildStatusBreakdown(array $campaigns, array $statusMeta): array
    {
        $order = ['active', 'scheduled', 'draft', 'paused', 'completed'];

        return collect($order)
            ->map(function (string $status) use ($campaigns, $statusMeta) {
                $meta = $statusMeta[$status] ?? ['label' => ucfirst($status), 'icon' => 'megaphone'];

                return [
                    'status' => $status,
                    'label' => $meta['label'],
                    'icon' => $meta['icon'] ?? 'megaphone',
                    'variant' => $meta['variant'] ?? 'default',
                    'count' => collect($campaigns)->where('status', $status)->count(),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  list<array<string, mixed>>  $channels
     * @return list<array<string, mixed>>
     */
    private function buildContentChannels(array $channels): array
    {
        return collect($channels)
            ->map(function (array $channel) {
                $route = $channel['route'] ?? null;

                return [
                    'label' => $channel['label'] ?? '',
                    'description' => $channel['description'] ?? '',
                    'icon' => $channel['icon'] ?? 'document',
                    'href' => $route ? route($route) : ($channel['url'] ?? '#'),
                    'external' => $channel['external'] ?? false,
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
}
