<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use Illuminate\Database\Seeder;

class NavigationSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPublicHeader();
        $this->seedPublicActions();
        $this->seedFooterColumns();
        $this->seedFooterSocial();
        $this->seedFooterLegal();
        $this->seedAdminSidebar();
    }

    private function seedPublicHeader(): void
    {
        foreach (config('cyra.navigation.public.header', []) as $item) {
            $this->upsertItem('public_header', $item);
        }
    }

    private function seedPublicActions(): void
    {
        foreach (config('cyra.navigation.public.actions', []) as $item) {
            $this->upsertItem('public_actions', $item);
        }
    }

    private function seedFooterColumns(): void
    {
        $sort = 1;

        foreach (config('cyra.navigation.public.footer_columns', []) as $column) {
            foreach ($column['links'] as $link) {
                NavigationItem::query()->updateOrCreate(
                    [
                        'location' => 'footer_column',
                        'group_label' => $column['title'],
                        'label' => $link['label'],
                    ],
                    [
                        'route_name' => $link['route'] ?? null,
                        'route_params' => $link['params'] ?? null,
                        'url' => $link['url'] ?? null,
                        'sort_order' => $sort++,
                        'is_active' => true,
                        'is_available' => $link['available'] ?? true,
                        'opens_in_new_tab' => $link['opens_in_new_tab'] ?? false,
                    ],
                );
            }
        }
    }

    private function seedFooterSocial(): void
    {
        $sort = 1;

        foreach (config('cyra.navigation.public.social', []) as $item) {
            NavigationItem::query()->updateOrCreate(
                [
                    'location' => 'footer_social',
                    'label' => $item['label'],
                ],
                [
                    'url' => $item['url'] ?? null,
                    'sort_order' => $sort++,
                    'is_active' => true,
                    'is_available' => true,
                    'opens_in_new_tab' => $item['opens_in_new_tab'] ?? true,
                ],
            );
        }
    }

    private function seedFooterLegal(): void
    {
        $sort = 1;

        foreach (config('cyra.navigation.public.legal', []) as $item) {
            $this->upsertItem('footer_legal', array_merge($item, ['sort' => $sort++]));
        }
    }

    private function seedAdminSidebar(): void
    {
        $sort = 1;

        foreach (config('cyra.navigation.admin.groups', []) as $group) {
            foreach ($group['items'] as $item) {
                NavigationItem::query()->updateOrCreate(
                    [
                        'location' => 'admin_sidebar',
                        'group_label' => $group['label'],
                        'label' => $item['label'],
                    ],
                    [
                        'route_name' => $item['route'] ?? null,
                        'route_params' => $item['params'] ?? null,
                        'url' => $item['url'] ?? null,
                        'sort_order' => $sort++,
                        'permission' => $item['permission'] ?? null,
                        'is_active' => true,
                        'is_available' => $item['available'] ?? ($item['route'] ?? null) !== null,
                        'opens_in_new_tab' => false,
                    ],
                );
            }
        }
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function upsertItem(string $location, array $item): void
    {
        NavigationItem::query()->updateOrCreate(
            [
                'location' => $location,
                'label' => $item['label'],
            ],
            [
                'route_name' => $item['route'] ?? null,
                'route_params' => $item['params'] ?? null,
                'url' => $item['url'] ?? null,
                'sort_order' => $item['sort'] ?? 0,
                'style' => $item['style'] ?? null,
                'permission' => $item['permission'] ?? null,
                'is_active' => true,
                'is_available' => $item['available'] ?? true,
                'opens_in_new_tab' => $item['opens_in_new_tab'] ?? false,
            ],
        );
    }
}
