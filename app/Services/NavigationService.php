<?php

namespace App\Services;

use App\Models\NavigationItem;
use App\Models\User;
use App\Repositories\NavigationItemRepository;
use Illuminate\Support\Facades\Route;

class NavigationService extends BaseService
{
    public function __construct(
        private readonly NavigationItemRepository $navigationItemRepository,
    ) {
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getPublicHeader(): array
    {
        return $this->navigationItemRepository
            ->getActiveByLocation('public_header')
            ->map(fn (NavigationItem $item) => $this->resolveItem($item))
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getPublicActions(): array
    {
        return $this->navigationItemRepository
            ->getActiveByLocation('public_actions')
            ->map(fn (NavigationItem $item) => $this->resolveItem($item))
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getFooterColumns(): array
    {
        return $this->navigationItemRepository
            ->getActiveByLocation('footer_column')
            ->groupBy('group_label')
            ->map(fn ($items, $title) => [
                'title' => (string) $title,
                'links' => $items
                    ->map(fn (NavigationItem $item) => $this->resolveItem($item))
                    ->values()
                    ->all(),
            ])
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getFooterSocial(): array
    {
        return $this->navigationItemRepository
            ->getActiveByLocation('footer_social')
            ->map(fn (NavigationItem $item) => $this->resolveItem($item))
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getFooterLegal(): array
    {
        return $this->navigationItemRepository
            ->getActiveByLocation('footer_legal')
            ->map(fn (NavigationItem $item) => $this->resolveItem($item))
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getAdminSidebar(?User $user = null): array
    {
        $user ??= auth()->user();

        return $this->navigationItemRepository
            ->getActiveAdminItems()
            ->groupBy('group_label')
            ->map(function ($items, $label) use ($user) {
                $resolvedItems = $items
                    ->filter(function (NavigationItem $item) use ($user) {
                        if ($item->permission === null) {
                            return true;
                        }

                        return $user?->hasPermission($item->permission) ?? false;
                    })
                    ->map(fn (NavigationItem $item) => $this->resolveItem($item))
                    ->values()
                    ->all();

                return [
                    'label' => (string) $label,
                    'items' => $resolvedItems,
                ];
            })
            ->filter(fn (array $group) => count($group['items']) > 0)
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function getPublicNavigation(): array
    {
        return [
            'brand' => config('cyra.navigation.brand', []),
            'header' => $this->getPublicHeader(),
            'actions' => $this->getPublicActions(),
            'footer' => [
                'columns' => $this->getFooterColumns(),
                'social' => $this->getFooterSocial(),
                'legal' => $this->getFooterLegal(),
                'newsletter' => config('cyra.navigation.public.newsletter', []),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getAdminNavigation(?User $user = null): array
    {
        return [
            'brand' => config('cyra.navigation.brand', []),
            'groups' => $this->getAdminSidebar($user),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function resolveItem(NavigationItem $item): array
    {
        $url = $item->url ?? '#';
        $active = false;

        if ($item->route_name !== null && Route::has($item->route_name)) {
            $url = route($item->route_name, $item->route_params ?? []);
            $active = request()->routeIs($item->route_name);
        }

        return [
            'label' => $item->label,
            'url' => $url,
            'active' => $active,
            'style' => $item->style,
            'opens_in_new_tab' => $item->opens_in_new_tab,
            'available' => $item->is_available,
        ];
    }
}
