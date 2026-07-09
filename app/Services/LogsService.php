<?php

namespace App\Services;

class LogsService extends BaseService
{
    /**
     * @return array<string, mixed>
     */
    public function getWorkspace(): array
    {
        $configured = config('cyra.logs_workspace', []);
        $severityMeta = $configured['severity_meta'] ?? [];
        $typeMeta = $configured['type_meta'] ?? [];

        $entries = collect(config('cyra.platform_logs.entries', []))
            ->map(fn (array $entry) => $this->formatEntry($entry, $severityMeta, $typeMeta))
            ->sortByDesc('sort_key')
            ->values()
            ->all();

        $activityFeed = collect(config('cyra.command_center.recent_activities', []))
            ->map(fn (array $activity) => [
                'title' => $activity['title'],
                'actor' => $activity['actor'],
                'time' => $activity['time'],
                'type' => $activity['type'] ?? 'system',
                'type_label' => $typeMeta[$activity['type'] ?? 'system']['label'] ?? ucfirst($activity['type'] ?? 'system'),
                'icon' => $typeMeta[$activity['type'] ?? 'system']['icon'] ?? 'logs',
                'severity' => 'info',
                'severity_label' => 'Info',
                'severity_variant' => 'primary',
            ])
            ->values()
            ->all();

        return [
            'summary' => [
                'total_entries' => count($entries),
                'info_count' => collect($entries)->where('severity', 'info')->count(),
                'warning_count' => collect($entries)->where('severity', 'warning')->count(),
                'critical_count' => collect($entries)->where('severity', 'critical')->count(),
                'activity_feed_count' => count($activityFeed),
            ],
            'description' => $configured['summary'] ?? 'Review platform audit logs and recent system activity.',
            'entries' => $entries,
            'activity_feed' => $activityFeed,
            'severity_breakdown' => $this->buildSeverityBreakdown($entries, $severityMeta),
            'type_breakdown' => $this->buildTypeBreakdown($entries, $typeMeta),
            'quick_links' => $this->buildQuickLinks($configured['quick_links'] ?? []),
            'workspace_notes' => $configured['workspace_notes'] ?? [],
        ];
    }

    /**
     * @param  array<string, mixed>  $entry
     * @param  array<string, array<string, string>>  $severityMeta
     * @param  array<string, array<string, string>>  $typeMeta
     * @return array<string, mixed>
     */
    private function formatEntry(array $entry, array $severityMeta, array $typeMeta): array
    {
        $severity = $entry['severity'] ?? 'info';
        $type = $entry['type'] ?? 'system';
        $severityInfo = $severityMeta[$severity] ?? ['label' => ucfirst($severity), 'variant' => 'primary'];
        $typeInfo = $typeMeta[$type] ?? ['label' => ucfirst($type), 'icon' => 'logs'];

        return [
            'reference' => $entry['reference'] ?? '',
            'action' => $entry['action'] ?? '',
            'actor' => $entry['actor'] ?? 'System',
            'type' => $type,
            'type_label' => $typeInfo['label'],
            'icon' => $typeInfo['icon'] ?? 'logs',
            'severity' => $severity,
            'severity_label' => $severityInfo['label'],
            'severity_variant' => $severityInfo['variant'] ?? 'primary',
            'occurred_at' => $entry['occurred_at'] ?? '',
            'occurred_ago' => $entry['occurred_ago'] ?? '',
            'details' => $entry['details'] ?? '',
            'sort_key' => $entry['sort_order'] ?? 0,
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $entries
     * @param  array<string, array<string, string>>  $severityMeta
     * @return list<array<string, mixed>>
     */
    private function buildSeverityBreakdown(array $entries, array $severityMeta): array
    {
        return collect(['info', 'warning', 'critical'])
            ->map(function (string $severity) use ($entries, $severityMeta) {
                $meta = $severityMeta[$severity] ?? ['label' => ucfirst($severity), 'icon' => 'logs'];

                return [
                    'severity' => $severity,
                    'label' => $meta['label'],
                    'icon' => $meta['icon'] ?? 'logs',
                    'variant' => $meta['variant'] ?? 'primary',
                    'count' => collect($entries)->where('severity', $severity)->count(),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  list<array<string, mixed>>  $entries
     * @param  array<string, array<string, string>>  $typeMeta
     * @return list<array<string, mixed>>
     */
    private function buildTypeBreakdown(array $entries, array $typeMeta): array
    {
        return collect($entries)
            ->groupBy('type')
            ->map(function ($group, string $type) use ($typeMeta) {
                $meta = $typeMeta[$type] ?? ['label' => ucfirst($type), 'icon' => 'logs'];

                return [
                    'type' => $type,
                    'label' => $meta['label'],
                    'icon' => $meta['icon'] ?? 'logs',
                    'count' => $group->count(),
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
