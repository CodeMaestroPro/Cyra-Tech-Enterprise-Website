<?php

namespace App\Services;

class EnterpriseSettingsService extends BaseService
{
    public function __construct(
        private readonly PlatformService $platformService,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getWorkspace(): array
    {
        $configured = config('cyra.enterprise_settings_workspace', []);
        $platform = $this->platformService->getStatus();
        $settingGroups = config('cyra.enterprise_settings.groups', []);

        $groups = collect($settingGroups)
            ->map(function (array $group) use ($platform) {
                return [
                    'label' => $group['label'] ?? '',
                    'description' => $group['description'] ?? '',
                    'icon' => $group['icon'] ?? 'cog',
                    'settings' => collect($group['settings'] ?? [])
                        ->map(fn (array $setting) => $this->resolveSetting($setting, $platform))
                        ->values()
                        ->all(),
                ];
            })
            ->values()
            ->all();

        return [
            'summary' => [
                'platform_version' => $platform['version'] ?? config('cyra.version'),
                'environment' => $platform['environment'] ?? app()->environment(),
                'modules_completed' => $platform['modules']['completed'] ?? 0,
                'modules_total' => $platform['modules']['total'] ?? 0,
                'setting_groups' => count($groups),
                'database_connected' => ($platform['database']['connected'] ?? false) ? 'Yes' : 'No',
            ],
            'description' => $configured['summary'] ?? 'Review enterprise platform configuration and identity settings.',
            'groups' => $groups,
            'stack' => $platform['stack'] ?? [],
            'quick_links' => $this->buildQuickLinks($configured['quick_links'] ?? []),
            'workspace_notes' => $configured['workspace_notes'] ?? [],
        ];
    }

    /**
     * @param  array<string, mixed>  $setting
     * @param  array<string, mixed>  $platform
     * @return array<string, mixed>
     */
    private function resolveSetting(array $setting, array $platform): array
    {
        $key = $setting['key'] ?? '';
        $value = match ($key) {
            'cyra.name' => config('cyra.name'),
            'cyra.tagline' => config('cyra.tagline'),
            'cyra.version' => config('cyra.version'),
            'cyra.brand.logo' => config('cyra.brand.logo'),
            'cyra.brand.logo_alt' => config('cyra.brand.logo_alt'),
            'cyra.api.prefix' => config('cyra.api.prefix'),
            'cyra.api.rate_limit' => (string) config('cyra.api.rate_limit'),
            'app.environment' => $platform['environment'] ?? app()->environment(),
            'database.driver' => $platform['database']['driver'] ?? config('database.default'),
            'database.connected' => ($platform['database']['connected'] ?? false) ? 'Connected' : 'Disconnected',
            'cyra.admin.email' => config('cyra.admin.email'),
            'cyra.admin.role' => config('cyra.admin.role'),
            'modules.progress' => ($platform['modules']['progress'] ?? 0).'%',
            default => $setting['value'] ?? '—',
        };

        return [
            'label' => $setting['label'] ?? $key,
            'key' => $key,
            'value' => $value,
            'editable' => $setting['editable'] ?? false,
        ];
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
