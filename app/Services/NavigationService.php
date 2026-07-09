<?php

namespace App\Services;

use App\Models\NavigationItem;
use App\Models\User;
use App\Repositories\CmsPageRepository;
use App\Repositories\NavigationItemRepository;
use Illuminate\Support\Facades\Route;

class NavigationService extends BaseService
{
    /** @var array{groups: array<string, string>, items: array<string, string>}|null */
    private static ?array $adminIconLookup = null;

    public function __construct(
        private readonly NavigationItemRepository $navigationItemRepository,
        private readonly CmsPageRepository $cmsPageRepository,
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
                    ->map(fn (NavigationItem $item) => $this->resolveItem($item, (string) $label))
                    ->values()
                    ->all();

                return [
                    'label' => (string) $label,
                    'icon' => $this->adminIconLookup()['groups'][(string) $label] ?? 'folder',
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
     * @return list<array{label: string, url: string, group: string, opens_in_new_tab: bool}>
     */
    public function getPublicSearchIndex(): array
    {
        $entries = [];

        foreach ($this->navigationItemRepository->getActiveByLocation('public_header') as $item) {
            if ($entry = $this->toSearchEntry($item, 'Main Navigation')) {
                $entries[] = $entry;
            }
        }

        foreach ($this->navigationItemRepository->getActiveByLocation('public_actions') as $item) {
            if ($entry = $this->toSearchEntry($item, 'Quick Actions')) {
                $entries[] = $entry;
            }
        }

        foreach ($this->navigationItemRepository->getActiveByLocation('footer_column') as $item) {
            if ($entry = $this->toSearchEntry($item, (string) ($item->group_label ?? 'Footer'))) {
                $entries[] = $entry;
            }
        }

        foreach ($this->navigationItemRepository->getActiveByLocation('footer_legal') as $item) {
            if ($entry = $this->toSearchEntry($item, 'Legal')) {
                $entries[] = $entry;
            }
        }

        return collect($entries)
            ->unique(fn (array $entry): string => $entry['url'])
            ->values()
            ->all();
    }

    /**
     * @return list<array{label: string, url: string, group: string}>
     */
    public function getAdminSearchIndex(?User $user = null): array
    {
        $user ??= auth()->user();

        return collect($this->getAdminSidebar($user))
            ->flatMap(function (array $group): array {
                return collect($group['items'] ?? [])
                    ->filter(fn (array $item): bool => (bool) ($item['available'] ?? false))
                    ->filter(fn (array $item): bool => $this->isResolvedItemReachable($item))
                    ->map(fn (array $item): array => [
                        'label' => $item['label'],
                        'url' => $item['url'],
                        'group' => $group['label'] ?? 'Command Center',
                    ])
                    ->all();
            })
            ->values()
            ->all();
    }

    /**
     * @return array{label: string, url: string, group: string, opens_in_new_tab: bool}|null
     */
    private function toSearchEntry(NavigationItem $item, string $group): ?array
    {
        if (! $this->isNavigationItemReachable($item)) {
            return null;
        }

        $resolved = $this->resolveItem($item, $item->location === 'admin_sidebar' ? $item->group_label : null);

        if (! $this->isResolvedItemReachable($resolved)) {
            return null;
        }

        return [
            'label' => $resolved['label'],
            'url' => $resolved['url'],
            'group' => $group,
            'opens_in_new_tab' => (bool) ($resolved['opens_in_new_tab'] ?? false),
        ];
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function isResolvedItemReachable(array $item): bool
    {
        if (! ($item['available'] ?? true)) {
            return false;
        }

        $url = (string) ($item['url'] ?? '');

        if ($url === '' || $url === '#' || str_starts_with($url, '#')) {
            return false;
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return true;
        }

        return str_starts_with($url, '/');
    }

    private function isNavigationItemReachable(NavigationItem $item): bool
    {
        if (! $item->is_active || ! $item->is_available) {
            return false;
        }

        if ($item->route_name !== null) {
            if (! Route::has($item->route_name)) {
                return false;
            }

            if ($item->route_name === 'pages.show') {
                $slug = $item->route_params['slug'] ?? null;

                if (! is_string($slug) || $slug === '') {
                    return false;
                }

                return $this->cmsPageRepository->findPublishedBySlug($slug) !== null;
            }

            return true;
        }

        $url = (string) ($item->url ?? '');

        if ($url === '' || $url === '#' || str_starts_with($url, '#')) {
            return false;
        }

        return str_starts_with($url, 'http://')
            || str_starts_with($url, 'https://')
            || str_starts_with($url, '/');
    }

    /**
     * @return array<string, mixed>
     */
    private function resolveItem(NavigationItem $item, ?string $adminGroupLabel = null): array
    {
        $url = $item->url ?? '#';
        $active = false;
        $routeName = $item->route_name;

        if ($item->location === 'admin_sidebar') {
            $routeName = $this->resolveAdminSidebarRouteName($item);
        }

        if ($routeName !== null && Route::has($routeName)) {
            $url = route($routeName, $item->route_params ?? []);
            $active = request()->routeIs($routeName);
        }

        $available = $item->is_available;

        if ($item->location === 'admin_sidebar' && $routeName === null) {
            $available = false;
        }

        $resolved = [
            'label' => $item->label,
            'url' => $url,
            'active' => $active,
            'style' => $item->style,
            'opens_in_new_tab' => false,
            'available' => $available,
        ];

        if ($item->location === 'admin_sidebar' && $adminGroupLabel !== null) {
            $key = $adminGroupLabel.'::'.$item->label;
            $resolved['icon'] = $this->adminIconLookup()['items'][$key] ?? 'default';
        }

        return $resolved;
    }

    /**
     * @return array{groups: array<string, string>, items: array<string, string>}
     */
    private function adminIconLookup(): array
    {
        if (self::$adminIconLookup !== null) {
            return self::$adminIconLookup;
        }

        $lookup = [
            'groups' => [],
            'items' => [],
        ];

        foreach (config('cyra.navigation.admin.groups', []) as $group) {
            $groupLabel = (string) ($group['label'] ?? '');
            $lookup['groups'][$groupLabel] = (string) ($group['icon'] ?? 'folder');

            foreach ($group['items'] ?? [] as $item) {
                $itemLabel = (string) ($item['label'] ?? '');
                $lookup['items'][$groupLabel.'::'.$itemLabel] = (string) ($item['icon'] ?? 'default');
            }
        }

        self::$adminIconLookup = $lookup;

        return self::$adminIconLookup;
    }

    private function resolveAdminSidebarRouteName(NavigationItem $item): ?string
    {
        $configRoute = $this->adminRouteFromConfig((string) $item->group_label, $item->label);

        if ($configRoute !== null && Route::has($configRoute) && str_starts_with($configRoute, 'admin.')) {
            return $configRoute;
        }

        $routeName = $item->route_name;

        if ($routeName !== null && Route::has($routeName) && str_starts_with($routeName, 'admin.')) {
            return $routeName;
        }

        return null;
    }

    private function adminRouteFromConfig(string $groupLabel, string $itemLabel): ?string
    {
        foreach (config('cyra.navigation.admin.groups', []) as $group) {
            if ((string) ($group['label'] ?? '') !== $groupLabel) {
                continue;
            }

            foreach ($group['items'] ?? [] as $item) {
                if ((string) ($item['label'] ?? '') === $itemLabel) {
                    $route = $item['route'] ?? null;

                    return is_string($route) ? $route : null;
                }
            }
        }

        return null;
    }
}
