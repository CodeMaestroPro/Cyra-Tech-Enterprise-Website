<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class AiKnowledgeService extends BaseService
{
    /** @var array<string, mixed>|null */
    private ?array $context = null;

    public function __construct(
        private readonly DashboardService $dashboardService,
        private readonly AnalyticsService $analyticsService,
        private readonly CrmService $crmService,
        private readonly ProjectManagementService $projectManagementService,
        private readonly PlatformService $platformService,
        private readonly TestingOptimizationService $testingOptimizationService,
        private readonly CmsService $cmsService,
        private readonly MediaLibraryService $mediaLibraryService,
        private readonly ProductService $productService,
        private readonly SolutionService $solutionService,
        private readonly IndustryService $industryService,
        private readonly PortfolioService $portfolioService,
        private readonly CareerService $careerService,
        private readonly InnovationLabService $innovationLabService,
        private readonly PartnerHubService $partnerHubService,
        private readonly LeadershipService $leadershipService,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function answer(string $message, ?string $promptSlug = null): array
    {
        $intent = $this->detectIntent($message, $promptSlug);

        $content = match ($intent) {
            'company-health' => $this->companyHealthResponse(),
            'lead-pipeline' => $this->leadPipelineResponse(),
            'project-status' => $this->projectStatusResponse(),
            'website-performance' => $this->websitePerformanceResponse(),
            'strategic-priorities' => $this->strategicPrioritiesResponse(),
            'attention' => $this->attentionResponse(),
            'platform-overview' => $this->platformOverviewResponse(),
            'platform-modules' => $this->platformModulesResponse(),
            'cms-content' => $this->cmsContentResponse(),
            'media-library' => $this->mediaLibraryResponse(),
            'optimization-qa' => $this->optimizationQaResponse(),
            'products' => $this->productsResponse(),
            'solutions' => $this->solutionsResponse(),
            'industries' => $this->industriesResponse(),
            'portfolio' => $this->portfolioResponse(),
            'careers' => $this->careersResponse(),
            'innovation-lab' => $this->innovationLabResponse(),
            'partners' => $this->partnersResponse(),
            'leadership' => $this->leadershipResponse(),
            'users-rbac' => $this->usersRbacResponse(),
            'client-portal' => $this->clientPortalResponse(),
            'tasks-events' => $this->tasksEventsResponse(),
            'admin-modules' => $this->adminModulesResponse(),
            'help' => $this->helpResponse(),
            'external-topic' => $this->externalTopicResponse($message),
            default => $this->searchKnowledge($message),
        };

        return [
            'slug' => $intent,
            'content' => $content,
            'generated_at' => now()->format('h:i A T'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function context(): array
    {
        if ($this->context !== null) {
            return $this->context;
        }

        $platform = $this->platformService->getStatus();
        $analytics = $this->analyticsService->getDashboard(
            (int) config('cyra.command_center.analytics_range_days', 30),
        );
        $crm = $this->crmService->getPipeline();
        $projects = $this->projectManagementService->getPortfolio();
        $tasks = $this->projectManagementService->getTaskBoard();
        $optimization = $this->testingOptimizationService->getDashboard();
        $cms = $this->cmsService->getAdminCatalog();
        $media = $this->mediaLibraryService->getAdminCatalog();

        $this->context = [
            'platform' => $platform,
            'modules' => $this->platformService->getModules(),
            'analytics' => $analytics,
            'crm' => $crm,
            'projects' => $projects,
            'tasks' => $tasks,
            'optimization' => $optimization,
            'cms' => $cms,
            'media' => $media,
            'products' => $this->productService->getProducts(),
            'solutions' => $this->solutionService->getSolutions(),
            'industries' => $this->industryService->getIndustries(),
            'portfolio' => $this->portfolioService->getPortfolio(),
            'careers' => $this->careerService->getCareers(),
            'innovation' => $this->innovationLabService->getInnovationLab(),
            'partners' => $this->partnerHubService->getPartnerHub(),
            'leadership' => $this->leadershipService->getLeadership(),
            'users' => [
                'total' => User::query()->count(),
                'active' => User::query()->where('is_active', true)->count(),
            ],
            'roles' => config('cyra.roles', []),
        ];

        return $this->context;
    }

    private function detectIntent(string $message, ?string $promptSlug): string
    {
        if ($promptSlug !== null && $promptSlug !== '') {
            return $promptSlug;
        }

        $normalized = Str::lower(trim($message));

        foreach (config('cyra.ai_assistant.prompts', []) as $prompt) {
            $slug = (string) ($prompt['slug'] ?? '');
            $label = Str::lower((string) ($prompt['label'] ?? ''));

            if ($slug !== '' && (str_contains($normalized, str_replace('-', ' ', $slug)) || str_contains($normalized, $label))) {
                return $slug;
            }
        }

        $rules = [
            'help' => ['what can you', 'what do you know', 'help me', 'your capabilities', 'what questions'],
            'platform-overview' => ['tech stack', 'technology stack', 'what is cyra', 'about cyra', 'platform version', 'built with', 'laravel', 'tailwind', 'mysql', 'vite', 'who is cyra'],
            'platform-modules' => ['how many module', 'module status', 'module complete', '25 module', 'platform module', 'development roadmap', 'module list'],
            'cms-content' => ['cms', 'content page', 'legal page', 'privacy policy', 'published page', 'blog'],
            'media-library' => ['media library', 'media asset', 'uploaded file', 'image asset'],
            'optimization-qa' => ['optimization', 'qa matrix', 'health score', 'test coverage', 'feature test', 'seo score', 'platform health'],
            'products' => ['product offering', 'cyra command', 'cyracrm', 'our products', 'product suite'],
            'solutions' => ['solution offering', 'digital transformation', 'our services', 'service line'],
            'industries' => ['industry vertical', 'industries we serve', 'financial services', 'healthcare industry', 'regulated'],
            'portfolio' => ['case study', 'portfolio project', 'client success', 'novabank'],
            'careers' => ['career opening', 'job opening', 'hiring', 'open role', 'recruit'],
            'innovation-lab' => ['innovation lab', 'copilot studio', 'research initiative'],
            'partners' => ['partner hub', 'partnership program', 'co-sell', 'alliance'],
            'leadership' => ['leadership team', 'executive team', 'who leads', 'ceo', 'management team'],
            'users-rbac' => ['user role', 'rbac', 'permission', 'admin role', 'manager role', 'viewer role'],
            'client-portal' => ['client portal', 'client engagement', 'customer portal'],
            'admin-modules' => ['admin module', 'command center module', 'admin dashboard', 'admin page', 'where is analytics', 'where is crm'],
            'tasks-events' => ['my task', 'upcoming event', 'calendar event', 'due today'],
            'company-health' => ['company health', 'company pulse', 'kpi', 'organizational health'],
            'lead-pipeline' => ['lead pipeline', 'crm pipeline', 'sales pipeline', 'inbound inquiry'],
            'project-status' => ['project status', 'project delivery', 'delivery program', 'active project'],
            'website-performance' => ['website performance', 'page view', 'traffic trend', 'conversion rate', 'bounce rate'],
            'strategic-priorities' => ['strategic priority', 'strategic roadmap', 'quarter initiative', 'q3 2026', 'q4 2026'],
            'attention' => ['needs my attention', 'what should i focus', 'urgent item', 'priority today'],
        ];

        $bestIntent = 'knowledge-search';
        $bestScore = 0;

        foreach ($rules as $intent => $phrases) {
            foreach ($phrases as $phrase) {
                if (str_contains($normalized, $phrase)) {
                    $score = strlen($phrase);
                    if ($score > $bestScore) {
                        $bestScore = $score;
                        $bestIntent = $intent;
                    }
                }
            }
        }

        if ($bestScore > 0) {
            return $bestIntent;
        }

        foreach (config('cyra.ai_assistant.external_topics', []) as $topic) {
            foreach ($topic['keywords'] ?? [] as $keyword) {
                if (str_contains($normalized, Str::lower($keyword))) {
                    return 'external-topic';
                }
            }
        }

        return 'knowledge-search';
    }

    private function searchKnowledge(string $message): string
    {
        $normalized = Str::lower(trim($message));
        $bestScore = 0;
        $bestAnswer = null;

        foreach ($this->knowledgeCandidates() as $candidate) {
            $score = 0;

            foreach ($candidate['keywords'] as $keyword) {
                if (str_contains($normalized, Str::lower($keyword))) {
                    $score += strlen($keyword);
                }
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestAnswer = $candidate['answer'];
            }
        }

        if ($bestAnswer !== null && $bestScore >= 4) {
            return is_callable($bestAnswer) ? $bestAnswer() : $bestAnswer;
        }

        if ($this->looksLikeProjectQuestion($normalized)) {
            return $this->platformOverviewResponse()."\n\n".$this->helpResponse();
        }

        return $this->helpResponse($message);
    }

    /**
     * @return list<array{keywords: list<string>, answer: callable|string}>
     */
    private function knowledgeCandidates(): array
    {
        $ctx = $this->context();
        $candidates = [];

        foreach ($ctx['modules'] as $module) {
            $candidates[] = [
                'keywords' => [Str::lower($module['name']), Str::lower($module['slug']), 'module '.$module['id']],
                'answer' => fn () => "Module #{$module['id']}: {$module['name']} (slug: {$module['slug']}) is {$module['status']} on the Cyra-Tech platform roadmap.",
            ];
        }

        foreach ($ctx['products']['products'] ?? [] as $product) {
            $candidates[] = [
                'keywords' => [Str::lower($product['title']), Str::lower($product['slug'] ?? ''), Str::lower($product['tagline'] ?? '')],
                'answer' => fn () => "Product: {$product['title']} — ".($product['tagline'] ?? $product['summary'] ?? 'Part of the Cyra-Tech product suite.'),
            ];
        }

        foreach ($ctx['solutions']['offerings'] ?? [] as $offering) {
            $candidates[] = [
                'keywords' => [Str::lower($offering['title']), Str::lower($offering['slug'] ?? '')],
                'answer' => fn () => "Solution: {$offering['title']} — ".($offering['summary'] ?? 'Enterprise solution offering from Cyra-Tech.'),
            ];
        }

        foreach ($ctx['industries']['verticals'] ?? [] as $industry) {
            $candidates[] = [
                'keywords' => [Str::lower($industry['title']), Str::lower($industry['slug'] ?? '')],
                'answer' => fn () => "Industry: {$industry['title']} — ".($industry['summary'] ?? 'Cyra-Tech serves this vertical with tailored enterprise programs.'),
            ];
        }

        foreach ($ctx['portfolio']['projects'] ?? [] as $project) {
            $candidates[] = [
                'keywords' => [Str::lower($project['title']), Str::lower($project['client_name'] ?? ''), Str::lower($project['slug'] ?? '')],
                'answer' => fn () => "Portfolio: {$project['title']} for {$project['client_name']} — {$project['summary']}",
            ];
        }

        foreach ($ctx['projects']['projects'] ?? [] as $project) {
            $candidates[] = [
                'keywords' => [Str::lower($project['name']), Str::lower($project['client_name'] ?? '')],
                'answer' => fn () => "Delivery program: {$project['name']} ({$project['client_name']}) is {$project['progress']}% complete with status {$project['status_label']}.",
            ];
        }

        foreach ($ctx['careers']['openings'] ?? [] as $opening) {
            $candidates[] = [
                'keywords' => [Str::lower($opening['title']), Str::lower($opening['department'] ?? ''), 'career', 'job'],
                'answer' => fn () => "Careers: {$opening['title']} ({$opening['location']}) — {$opening['work_type']} role in {$opening['department']}.",
            ];
        }

        foreach ($ctx['leadership']['profiles'] ?? [] as $profile) {
            $candidates[] = [
                'keywords' => [Str::lower($profile['name']), Str::lower($profile['title'] ?? ''), Str::lower($profile['slug'] ?? '')],
                'answer' => fn () => "Leadership: {$profile['name']}, {$profile['title']} — ".Str::limit($profile['bio'] ?? '', 220),
            ];
        }

        foreach (config('cyra.ai_assistant.external_topics', []) as $topic) {
            $candidates[] = [
                'keywords' => $topic['keywords'] ?? [],
                'answer' => (string) ($topic['response'] ?? ''),
            ];
        }

        return $candidates;
    }

    private function looksLikeProjectQuestion(string $normalized): bool
    {
        return str_contains($normalized, 'cyra')
            || str_contains($normalized, 'project')
            || str_contains($normalized, 'platform')
            || str_contains($normalized, 'website')
            || str_contains($normalized, 'enterprise');
    }

    private function platformOverviewResponse(): string
    {
        $ctx = $this->context();
        $platform = $ctx['platform'];
        $stack = collect($platform['stack'] ?? [])->map(fn ($value, $key) => ucfirst($key).": {$value}")->implode("\n- ");

        return implode("\n", [
            config('cyra.name').' Enterprise Platform overview:',
            '- Tagline: '.config('cyra.tagline'),
            '- Version: v'.config('cyra.version'),
            '- Environment: '.$platform['environment'],
            '- Database: '.($platform['database']['status'] ?? 'unknown'),
            '- Module progress: '.$platform['modules']['completed'].'/'.$platform['modules']['total'].' ('.$platform['modules']['progress'].'%)',
            '- Active users: '.$ctx['users']['active'].' of '.$ctx['users']['total'],
            "\nTechnology stack:",
            '- '.$stack,
            "\nCyra-Tech is a modular Laravel enterprise website and admin Command Center covering public experience modules, CRM, analytics, CMS, media, projects, and optimization.",
        ]);
    }

    private function platformModulesResponse(): string
    {
        $modules = $this->context()['modules'];
        $completed = collect($modules)->where('status', 'completed')->count();
        $pending = collect($modules)->where('status', '!=', 'completed');

        $lines = collect([
            "Platform module roadmap ({$completed}/".count($modules).' completed):',
        ]);

        foreach ($modules as $module) {
            $lines->push('- #'.str_pad((string) $module['id'], 2, '0', STR_PAD_LEFT)." {$module['name']}: {$module['status']}");
        }

        if ($pending->isNotEmpty()) {
            $lines->push("\nPending modules:");
            foreach ($pending as $module) {
                $lines->push("- {$module['name']}");
            }
        } else {
            $lines->push("\nAll configured modules are marked completed. Next horizon work is tracked in Strategic Roadmap under Enterprise Scale.");
        }

        return $lines->implode("\n");
    }

    private function cmsContentResponse(): string
    {
        $cms = $this->context()['cms'];
        $summary = $cms['summary'] ?? [];
        $pages = collect($cms['pages'] ?? [])->take(8);

        $lines = collect([
            'CMS content snapshot:',
            '- Total pages: '.($summary['total'] ?? 0),
            '- Published: '.($summary['published'] ?? 0),
            '- Draft: '.($summary['draft'] ?? 0),
            '- Templates available: '.count($cms['templates'] ?? []),
        ]);

        if ($pages->isNotEmpty()) {
            $lines->push("\nRecent pages:");
            foreach ($pages as $page) {
                $lines->push("- {$page['title']} ({$page['status']})");
            }
        }

        $lines->push("\nManage content at Admin → Digital Headquarters → Pages.");

        return $lines->implode("\n");
    }

    private function mediaLibraryResponse(): string
    {
        $media = $this->context()['media'];
        $summary = $media['summary'] ?? [];

        return implode("\n", [
            'Media Library snapshot:',
            '- Total assets: '.($summary['total'] ?? 0),
            '- Active assets: '.($summary['active'] ?? 0),
            '- Categories: '.count($media['categories'] ?? []),
            '- Max upload size: '.(($media['max_upload_kb'] ?? 5120) / 1024).' MB',
            "\nManage uploads at Admin → Digital Headquarters → Media Library.",
        ]);
    }

    private function optimizationQaResponse(): string
    {
        $opt = $this->context()['optimization'];
        $summary = $opt['summary'] ?? [];

        $lines = collect([
            'Testing & Optimization snapshot:',
            '- Platform health score: '.($summary['health_score'] ?? 0).'%',
            '- Modules complete: '.($summary['modules_completed'] ?? 0).'/'.($summary['modules_total'] ?? 0),
            '- Feature tests: '.($summary['feature_tests'] ?? 0).' across '.($summary['feature_test_files'] ?? 0).' files',
            '- SEO readiness score: '.($summary['seo_score'] ?? 0).'%',
        ]);

        $failedChecks = collect($opt['health_checks'] ?? [])->where('status', '!=', 'pass')->take(3);
        if ($failedChecks->isNotEmpty()) {
            $lines->push("\nAttention items:");
            foreach ($failedChecks as $check) {
                $lines->push("- {$check['label']}: {$check['status_label']}");
            }
        }

        $lines->push("\nFull QA matrix: Admin → System → Testing & Optimization.");

        return $lines->implode("\n");
    }

    private function productsResponse(): string
    {
        $products = collect($this->context()['products']['products'] ?? []);

        $lines = collect(['Cyra-Tech product suite:']);
        foreach ($products as $product) {
            $lines->push("- {$product['title']}: ".($product['tagline'] ?? $product['summary'] ?? 'Enterprise product'));
        }

        $lines->push("\nPublic catalog: ".route('products'));

        return $lines->implode("\n");
    }

    private function solutionsResponse(): string
    {
        $offerings = collect($this->context()['solutions']['offerings'] ?? []);

        $lines = collect(['Cyra-Tech solution offerings:']);
        foreach ($offerings as $offering) {
            $lines->push("- {$offering['title']}: ".($offering['summary'] ?? 'Enterprise service'));
        }

        $lines->push("\nPublic catalog: ".route('solutions'));

        return $lines->implode("\n");
    }

    private function industriesResponse(): string
    {
        $industries = collect($this->context()['industries']['verticals'] ?? []);

        $lines = collect(['Industries served by Cyra-Tech:']);
        foreach ($industries as $industry) {
            $lines->push("- {$industry['title']}: ".Str::limit($industry['summary'] ?? '', 120));
        }

        return $lines->implode("\n");
    }

    private function portfolioResponse(): string
    {
        $projects = collect($this->context()['portfolio']['projects'] ?? [])->take(6);

        $lines = collect(['Featured portfolio engagements:']);
        foreach ($projects as $project) {
            $lines->push("- {$project['title']} ({$project['client_name']}): ".Str::limit($project['summary'] ?? '', 100));
        }

        return $lines->implode("\n");
    }

    private function careersResponse(): string
    {
        $openings = collect($this->context()['careers']['openings'] ?? []);

        $lines = collect([
            'Careers at Cyra-Tech:',
            '- Open roles: '.$openings->count(),
        ]);

        foreach ($openings->take(6) as $opening) {
            $lines->push("- {$opening['title']} — {$opening['location']} ({$opening['work_type']})");
        }

        $lines->push("\nPublic careers page: ".route('careers'));

        return $lines->implode("\n");
    }

    private function innovationLabResponse(): string
    {
        $innovation = $this->context()['innovation'];
        $initiatives = collect($innovation['initiatives'] ?? [])->take(5);

        $lines = collect(['Innovation Lab initiatives:']);
        foreach ($initiatives as $item) {
            $lines->push("- {$item['title']}: ".Str::limit($item['summary'] ?? '', 120));
        }

        return $lines->implode("\n");
    }

    private function partnersResponse(): string
    {
        $partners = $this->context()['partners'];
        $programs = collect($partners['programs'] ?? [])->take(5);

        $lines = collect(['Partner Hub programs:']);
        foreach ($programs as $program) {
            $lines->push("- {$program['title']}: ".Str::limit($program['summary'] ?? $program['tagline'] ?? '', 120));
        }

        return $lines->implode("\n");
    }

    private function leadershipResponse(): string
    {
        $profiles = collect($this->context()['leadership']['profiles'] ?? []);

        $lines = collect(['Cyra-Tech leadership team:']);
        foreach ($profiles as $profile) {
            $lines->push("- {$profile['name']}, {$profile['title']}");
        }

        return $lines->implode("\n");
    }

    private function usersRbacResponse(): string
    {
        $ctx = $this->context();
        $roles = collect($ctx['roles'] ?? []);

        $lines = collect([
            'Users & RBAC snapshot:',
            '- Total users: '.$ctx['users']['total'],
            '- Active users: '.$ctx['users']['active'],
            "\nConfigured roles:",
        ]);

        foreach ($roles as $slug => $role) {
            $permissions = $role['permissions'] ?? [];
            $permissionCount = in_array('*', $permissions, true) ? 'all' : (string) count($permissions);
            $lines->push("- {$role['name']} ({$slug}): {$permissionCount} permissions — ".($role['description'] ?? ''));
        }

        return $lines->implode("\n");
    }

    private function clientPortalResponse(): string
    {
        $engagements = collect(config('cyra.client_portal.engagements', []))->take(4);

        $lines = collect([
            'Client Portal module:',
            '- Provides authenticated client engagement dashboards, milestones, documents, and support channels.',
            '- Public entry: '.route('client-portal'),
        ]);

        if ($engagements->isNotEmpty()) {
            $lines->push("\nSample engagements:");
            foreach ($engagements as $engagement) {
                $lines->push("- {$engagement['title']} ({$engagement['client']})");
            }
        }

        return $lines->implode("\n");
    }

    private function adminModulesResponse(): string
    {
        $groups = config('cyra.navigation.admin.groups', []);
        $lines = collect(['Admin Command Center modules:']);

        foreach ($groups as $group) {
            $available = collect($group['items'] ?? [])
                ->filter(fn (array $item) => ($item['route'] ?? null) !== null || ($item['available'] ?? true))
                ->pluck('label')
                ->implode(', ');

            $lines->push("- {$group['label']}: {$available}");
        }

        return $lines->implode("\n");
    }

    private function tasksEventsResponse(): string
    {
        $tasks = collect(config('cyra.command_center.tasks', []));
        $events = collect(config('cyra.command_center.upcoming_events', []));
        $board = $this->context()['tasks'];

        $lines = collect(['Tasks & events snapshot:']);

        foreach ($tasks as $task) {
            $lines->push("- Task: {$task['title']} ({$task['status']}, {$task['due']})");
        }

        foreach ($events->take(4) as $event) {
            $lines->push("- Event: {$event['title']} — {$event['datetime']}");
        }

        $lines->push("\nLive task board: {$board['summary']['total']} active tasks, {$board['summary']['overdue']} overdue.");

        return $lines->implode("\n");
    }

    private function externalTopicResponse(string $message): string
    {
        $normalized = Str::lower(trim($message));
        $best = null;
        $bestScore = 0;

        foreach (config('cyra.ai_assistant.external_topics', []) as $topic) {
            $score = 0;
            foreach ($topic['keywords'] ?? [] as $keyword) {
                if (str_contains($normalized, Str::lower($keyword))) {
                    $score += strlen($keyword);
                }
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $topic;
            }
        }

        if ($best !== null) {
            return (string) ($best['response'] ?? '');
        }

        return $this->helpResponse($message);
    }

    private function helpResponse(?string $message = null): string
    {
        $topics = [
            'Platform & modules', 'CMS & media', 'Analytics & website traffic', 'CRM & leads',
            'Projects & tasks', 'Products, solutions & industries', 'Portfolio & careers',
            'Partners & innovation lab', 'Leadership & client portal', 'QA & optimization',
            'Strategic roadmap', 'Cloud, AI & enterprise transformation (general guidance)',
        ];

        $lines = collect([
            'I can answer questions about the entire Cyra-Tech Enterprise Platform using live admin and public data, plus general enterprise technology guidance.',
            "\nAsk me about:",
        ]);

        foreach ($topics as $topic) {
            $lines->push("- {$topic}");
        }

        if ($message !== null && trim($message) !== '') {
            $lines->push("\nI could not match a precise topic for: \"{$message}\".");
            $lines->push('Try being more specific — e.g. "How many CMS pages are published?", "List Cyra products", or "Summarize CRM pipeline".');
        }

        return $lines->implode("\n");
    }

    private function companyHealthResponse(): string
    {
        $dashboard = $this->dashboardService->getCommandCenter();
        $pulse = $dashboard['company_pulse']['overall_score'] ?? 0;
        $metrics = collect($dashboard['company_pulse']['metrics'] ?? []);
        $kpis = collect($dashboard['kpis'] ?? []);

        $lines = collect([
            "Overall company pulse is {$pulse}% — ".($pulse >= 85 ? 'strong organizational health.' : 'monitoring recommended across lower-scoring dimensions.'),
            "\nKPI snapshot:",
        ]);

        foreach ($kpis as $kpi) {
            $lines->push("- {$kpi['label']}: {$kpi['value']} ({$kpi['change']})");
        }

        $lines->push("\nCompany Pulse dimensions:");
        foreach ($metrics as $metric) {
            $lines->push("- {$metric['label']}: {$metric['score']}%");
        }

        return $lines->implode("\n");
    }

    private function leadPipelineResponse(): string
    {
        $crm = $this->context()['crm'];
        $summary = $crm['summary'] ?? [];
        $stages = collect($crm['stages'] ?? [])->filter(fn (array $stage) => ($stage['count'] ?? 0) > 0);
        $topLeads = collect($crm['stages'] ?? [])
            ->flatMap(fn (array $stage) => $stage['leads'] ?? [])
            ->take(5);

        $lines = collect([
            'CRM pipeline snapshot:',
            '- Total active leads: '.($summary['total'] ?? 0),
            '- Pipeline value: ₦'.number_format((float) ($summary['pipeline_value'] ?? 0)),
            '- Won deals: '.($summary['won'] ?? 0),
            '- High-priority leads: '.($summary['high_priority'] ?? 0),
            '- Inbound inquiries awaiting conversion: '.($summary['inbound_inquiries'] ?? 0),
        ]);

        if ($stages->isNotEmpty()) {
            $lines->push("\nStage breakdown:");
            foreach ($stages as $stage) {
                $lines->push("- {$stage['label']}: {$stage['count']} lead(s), value ₦".number_format((float) ($stage['value'] ?? 0)));
            }
        }

        if ($topLeads->isNotEmpty()) {
            $lines->push("\nSample leads:");
            foreach ($topLeads as $lead) {
                $lines->push("- {$lead['name']} ({$lead['company']}) — {$lead['pipeline_stage_label']}, priority {$lead['priority_label']}");
            }
        }

        return $lines->implode("\n");
    }

    private function projectStatusResponse(): string
    {
        $portfolio = $this->context()['projects'];
        $summary = $portfolio['summary'] ?? [];
        $projects = collect($portfolio['projects'] ?? []);
        $tasks = $this->context()['tasks']['summary'] ?? [];

        $lines = collect([
            'Delivery portfolio overview:',
            '- Total programs: '.($summary['total'] ?? 0),
            '- In progress: '.($summary['in_progress'] ?? 0),
            '- Completed: '.($summary['completed'] ?? 0),
            '- On hold: '.($summary['on_hold'] ?? 0),
            '- Average progress: '.($summary['average_progress'] ?? 0).'%',
            '- Open tasks across programs: '.($summary['open_tasks'] ?? 0),
            '- Task board: '.($tasks['in_progress'] ?? 0).' in progress, '.($tasks['overdue'] ?? 0).' overdue',
        ]);

        if ($projects->isNotEmpty()) {
            $lines->push("\nPrograms:");
            foreach ($projects as $project) {
                $lines->push("- {$project['name']} ({$project['client_name']}): {$project['progress']}% — {$project['status_label']}, phase {$project['phase_label']}");
            }
        }

        return $lines->implode("\n");
    }

    private function websitePerformanceResponse(): string
    {
        $analytics = $this->context()['analytics'];
        $overview = $analytics['overview'] ?? [];
        $topPages = collect($analytics['top_pages'] ?? [])->take(5);
        $topModules = collect($analytics['top_modules'] ?? [])->take(5);
        $snapshot = $analytics['platform_snapshot'] ?? [];

        $lines = collect([
            'Website analytics brief (last '.$analytics['range_days'].' days):',
            '- Page views: '.number_format((int) ($overview['page_views'] ?? 0)),
            '- Unique sessions: '.number_format((int) ($overview['unique_sessions'] ?? 0)),
            '- Module views: '.number_format((int) ($overview['module_views'] ?? 0)),
            '- Form submissions: '.number_format((int) ($overview['form_submissions'] ?? 0)),
            '- Conversion rate: '.($overview['conversion_rate'] ?? 0).'%',
            '- Contact inquiries: '.number_format((int) ($overview['contact_inquiries'] ?? 0)),
            '- Portal logins: '.number_format((int) ($overview['portal_logins'] ?? 0)),
        ]);

        if ($topPages->isNotEmpty()) {
            $lines->push("\nTop pages:");
            foreach ($topPages as $page) {
                $lines->push("- {$page['label']}: ".number_format((int) $page['total']).' views');
            }
        }

        if ($topModules->isNotEmpty()) {
            $lines->push("\nTop modules:");
            foreach ($topModules as $module) {
                $lines->push("- {$module['label']}: ".number_format((int) $module['total']).' views');
            }
        }

        $lines->push("\nPlatform snapshot: ".($snapshot['cms_pages'] ?? 0).' CMS pages, '.($snapshot['media_assets'] ?? 0).' media assets, '.($snapshot['active_users'] ?? 0).' active users.');

        return $lines->implode("\n");
    }

    private function strategicPrioritiesResponse(): string
    {
        $quarter = collect(config('cyra.strategic_roadmap.quarters', []))
            ->firstWhere('status', 'in_progress');
        $pillars = collect(config('cyra.strategic_roadmap.pillars', []))->take(4);
        $initiatives = collect($quarter['initiatives'] ?? [])->take(6);

        $lines = collect([
            'Strategic priorities for '.($quarter['label'] ?? 'the current quarter').' — '.($quarter['theme'] ?? 'Operational Intelligence').':',
        ]);

        foreach ($initiatives as $initiative) {
            $lines->push("- {$initiative['title']} ({$initiative['owner']}, {$initiative['progress']}% complete, {$initiative['status']})");
        }

        $lines->push("\nStrategic pillars:");
        foreach ($pillars as $pillar) {
            $lines->push("- {$pillar['title']}: {$pillar['progress']}% — {$pillar['description']}");
        }

        $lines->push("\nVision: ".config('cyra.strategic_roadmap.vision'));

        return $lines->implode("\n");
    }

    private function attentionResponse(): string
    {
        $crm = $this->context()['crm'];
        $tasks = collect(config('cyra.command_center.tasks', []))->where('status', 'pending');
        $opt = $this->context()['optimization'];
        $highPriority = (int) ($crm['summary']['high_priority'] ?? 0);
        $overdueTasks = (int) ($this->context()['tasks']['summary']['overdue'] ?? 0);

        $lines = collect(['Executive attention items for today:']);

        if ($highPriority > 0) {
            $lines->push("- {$highPriority} high-priority CRM lead(s) require follow-up.");
        }

        if ($overdueTasks > 0) {
            $lines->push("- {$overdueTasks} overdue project task(s) on the delivery board.");
        }

        foreach ($tasks as $task) {
            $lines->push("- Task: {$task['title']} ({$task['due']})");
        }

        if (($opt['summary']['health_score'] ?? 100) < 90) {
            $lines->push('- Platform health score is '.($opt['summary']['health_score'] ?? 0).'%. Review Testing & Optimization.');
        }

        $lines->push('- Review AI Executive Brief on the Command Center dashboard.');
        $lines->push('- Confirm quarterly initiative progress in Strategic Roadmap.');

        return $lines->implode("\n");
    }
}
