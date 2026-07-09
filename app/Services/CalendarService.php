<?php

namespace App\Services;

class CalendarService extends BaseService
{
    /**
     * @return array<string, mixed>
     */
    public function getWorkspace(): array
    {
        $configured = config('cyra.calendar_workspace', []);
        $typeMeta = $configured['type_meta'] ?? [];

        $events = collect(config('cyra.command_center.upcoming_events', []))
            ->map(fn (array $event, int $index) => $this->formatItem([
                'title' => $event['title'],
                'datetime' => $event['datetime'],
                'type' => $event['type'] ?? 'event',
                'source' => 'Command Center',
                'sort_order' => 100 - $index,
            ], $typeMeta))
            ->values();

        $scheduled = collect(config('cyra.calendar.events', []))
            ->map(fn (array $event) => $this->formatItem($event, $typeMeta));

        $tasks = collect(config('cyra.command_center.tasks', []))
            ->map(fn (array $task, int $index) => $this->formatItem([
                'title' => $task['title'],
                'datetime' => $task['due'],
                'type' => 'task',
                'status' => $task['status'] ?? 'pending',
                'source' => 'Tasks',
                'sort_order' => 50 - $index,
            ], $typeMeta));

        $items = $events->merge($scheduled)->merge($tasks)->sortByDesc('sort_order')->values()->all();

        return [
            'summary' => [
                'total_items' => count($items),
                'meetings' => collect($items)->where('type', 'meeting')->count(),
                'events' => collect($items)->where('type', 'event')->count(),
                'tasks' => collect($items)->where('type', 'task')->count(),
                'calls' => collect($items)->where('type', 'call')->count(),
            ],
            'description' => $configured['summary'] ?? 'View upcoming meetings, events, and operational deadlines.',
            'items' => $items,
            'type_breakdown' => $this->buildTypeBreakdown($items, $typeMeta),
            'tasks' => collect($items)->where('type', 'task')->values()->all(),
            'upcoming' => array_slice($items, 0, 6),
            'quick_links' => $this->buildQuickLinks($configured['quick_links'] ?? []),
            'workspace_notes' => $configured['workspace_notes'] ?? [],
        ];
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  array<string, array<string, string>>  $typeMeta
     * @return array<string, mixed>
     */
    private function formatItem(array $item, array $typeMeta): array
    {
        $type = $item['type'] ?? 'event';
        $meta = $typeMeta[$type] ?? ['label' => ucfirst($type), 'icon' => 'calendar', 'variant' => 'primary'];
        $status = $item['status'] ?? null;

        return [
            'title' => $item['title'] ?? '',
            'datetime' => $item['datetime'] ?? '',
            'location' => $item['location'] ?? null,
            'type' => $type,
            'type_label' => $meta['label'],
            'type_icon' => $meta['icon'] ?? 'calendar',
            'type_variant' => $meta['variant'] ?? 'primary',
            'source' => $item['source'] ?? 'Calendar',
            'status' => $status,
            'status_label' => $status ? ucfirst(str_replace('_', ' ', $status)) : null,
            'sort_order' => $item['sort_order'] ?? 0,
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @param  array<string, array<string, string>>  $typeMeta
     * @return list<array<string, mixed>>
     */
    private function buildTypeBreakdown(array $items, array $typeMeta): array
    {
        return collect($items)
            ->groupBy('type')
            ->map(function ($group, string $type) use ($typeMeta) {
                $meta = $typeMeta[$type] ?? ['label' => ucfirst($type), 'icon' => 'calendar'];

                return [
                    'type' => $type,
                    'label' => $meta['label'],
                    'icon' => $meta['icon'] ?? 'calendar',
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
