<?php

namespace App\Services;

use App\Models\NavigationItem;
use App\Repositories\NavigationItemRepository;

class NavigationAdminService extends BaseService
{
    public function __construct(
        private readonly NavigationItemRepository $navigationItemRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getAdminWorkspace(): array
    {
        $items = $this->navigationItemRepository
            ->getAllAdminItems()
            ->map(fn (NavigationItem $item) => $this->formatItem($item))
            ->values()
            ->all();

        return [
            'description' => 'Manage admin sidebar navigation labels, routes, ordering, and availability.',
            'summary' => [
                'total' => count($items),
                'active' => collect($items)->where('is_active', true)->count(),
                'available' => collect($items)->where('is_available', true)->count(),
            ],
            'groups' => collect($items)->groupBy('group_label')->all(),
            'items' => $items,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAdminItem(NavigationItem $item): ?array
    {
        return $this->formatItem($item, detailed: true);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>|null
     */
    public function updateItem(NavigationItem $item, array $data): ?array
    {
        $item = $this->navigationItemRepository->updateItem($item, [
            'label' => $data['label'],
            'route_name' => $data['route_name'] ?: null,
            'url' => $data['url'] ?: null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'permission' => $data['permission'] ?: null,
            'is_active' => (bool) ($data['is_active'] ?? false),
            'is_available' => (bool) ($data['is_available'] ?? false),
            'opens_in_new_tab' => (bool) ($data['opens_in_new_tab'] ?? false),
        ]);

        return $this->formatItem($item, detailed: true);
    }

    /**
     * @return array<string, mixed>
     */
    private function formatItem(NavigationItem $item, bool $detailed = false): array
    {
        $formatted = [
            'id' => $item->id,
            'group_label' => $item->group_label,
            'label' => $item->label,
            'route_name' => $item->route_name,
            'url' => $item->url,
            'sort_order' => $item->sort_order,
            'permission' => $item->permission,
            'is_active' => $item->is_active,
            'is_available' => $item->is_available,
            'opens_in_new_tab' => $item->opens_in_new_tab,
            'edit_url' => route('admin.navigation.edit', $item),
        ];

        if ($detailed) {
            $formatted['style'] = $item->style;
            $formatted['location'] = $item->location;
        }

        return $formatted;
    }
}
