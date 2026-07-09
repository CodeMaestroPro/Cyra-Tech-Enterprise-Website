<?php

namespace App\Services;

use App\Models\User;

class AiAssistantService extends BaseService
{
    public function __construct(
        private readonly AiKnowledgeService $aiKnowledgeService,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getWorkspace(?User $user = null): array
    {
        $user ??= auth()->user();
        $configured = config('cyra.ai_assistant', []);
        $insights = $this->buildInsights();

        return [
            'assistant' => [
                'name' => $configured['name'] ?? 'Cyra AI',
                'tagline' => $configured['tagline'] ?? '',
                'model' => $configured['model'] ?? [],
            ],
            'capabilities' => $configured['capabilities'] ?? [],
            'prompts' => $configured['prompts'] ?? [],
            'guardrails' => $configured['guardrails'] ?? [],
            'insights' => $insights,
            'greeting' => $this->buildGreeting($user),
            'initial_message' => $this->buildInitialMessage($user),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function respond(string $message, ?string $promptSlug = null): array
    {
        return $this->aiKnowledgeService->answer($message, $promptSlug);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildInsights(): array
    {
        $context = $this->aiKnowledgeService->context();
        $platform = $context['platform'];
        $overview = $context['analytics']['overview'] ?? [];
        $crm = $context['crm']['summary'] ?? [];
        $activeProjects = collect($context['projects']['projects'] ?? [])
            ->whereIn('status', ['in-progress', 'planning'])
            ->count();

        return [
            [
                'label' => 'Platform modules complete',
                'value' => $platform['modules']['completed'].'/'.$platform['modules']['total'],
                'icon' => 'server',
                'tone' => 'success',
            ],
            [
                'label' => 'Page views (30d)',
                'value' => number_format((int) ($overview['page_views'] ?? 0)),
                'icon' => 'eye',
                'tone' => 'accent',
            ],
            [
                'label' => 'CRM pipeline leads',
                'value' => (string) ($crm['total'] ?? 0),
                'icon' => 'crm',
                'tone' => 'primary',
            ],
            [
                'label' => 'Active delivery programs',
                'value' => (string) $activeProjects,
                'icon' => 'folder',
                'tone' => 'warning',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildInitialMessage(?User $user): array
    {
        $name = $user?->name ?? 'there';
        $context = $this->aiKnowledgeService->context();
        $moduleProgress = $context['platform']['modules']['completed'].'/'.$context['platform']['modules']['total'];

        return [
            'role' => 'assistant',
            'content' => "Good day, {$name}. I'm Cyra AI — your executive intelligence layer for the entire Cyra-Tech platform. I use live data from modules, CMS, CRM, projects, analytics, products, careers, partners, and more. Platform status: {$moduleProgress} modules complete. Ask about any part of the project, admin modules, or broader enterprise technology topics.",
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildGreeting(?User $user): array
    {
        $hour = (int) now()->format('G');
        $period = match (true) {
            $hour < 12 => 'Morning',
            $hour < 17 => 'Afternoon',
            default => 'Evening',
        };

        return [
            'period' => $period,
            'name' => $user?->name ?? 'Team Member',
            'role' => $user?->getPrimaryRoleName() ?? 'Team Member',
        ];
    }
}
