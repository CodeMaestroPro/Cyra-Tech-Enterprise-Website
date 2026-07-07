<?php

namespace App\Services;

class DesignSystemService extends BaseService
{
    /**
     * @return array<string, mixed>
     */
    public function getTokens(): array
    {
        $designSystem = config('cyra.design_system', []);

        return [
            'version' => $designSystem['version'] ?? '1.0.0',
            'stack' => $designSystem['stack'] ?? 'Blade + JavaScript + Tailwind CSS 4',
            'colors' => $designSystem['colors'] ?? [],
            'typography' => $designSystem['typography'] ?? [],
            'radii' => $designSystem['radii'] ?? [],
        ];
    }

    /**
     * @return list<array<string, string>>
     */
    public function getComponents(): array
    {
        return collect(config('cyra.design_system.components', []))
            ->map(fn (string $component) => [
                'name' => $component,
                'blade' => "x-ui.{$component}",
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function getCatalog(): array
    {
        return [
            'tokens' => $this->getTokens(),
            'components' => $this->getComponents(),
            'principles' => [
                'Use Blade components for all UI — no React.',
                'Use Tailwind CSS 4 tokens from config/cyra.php and app.css.',
                'Use vanilla JavaScript only for progressive enhancement.',
                'Maintain WCAG-friendly focus states and aria attributes.',
            ],
        ];
    }
}
