<?php

namespace App\Services;

use App\Models\CmsPage;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
class TestingOptimizationService extends BaseService
{
    public function __construct(
        private readonly PlatformService $platformService,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getDashboard(): array
    {
        $modules = $this->platformService->getModules();
        $health = $this->platformService->getHealth();
        $testMetrics = $this->buildTestMetrics();
        $performanceChecks = $this->buildPerformanceChecks();
        $seoChecklist = $this->buildSeoChecklist();

        return [
            'summary' => [
                'health_score' => $this->calculateHealthScore($health, $testMetrics, $performanceChecks, $seoChecklist),
                'modules_completed' => collect($modules)->where('status', 'completed')->count(),
                'modules_total' => count($modules),
                'feature_tests' => $testMetrics['total_tests'],
                'feature_test_files' => $testMetrics['total_files'],
                'seo_score' => $this->calculateSeoScore($seoChecklist),
                'platform_status' => $health['status'],
            ],
            'health_checks' => $this->buildHealthChecks($health),
            'module_qa' => $this->buildModuleQa($modules, $testMetrics),
            'test_suites' => $testMetrics['suites'],
            'performance_checks' => $performanceChecks,
            'seo_checklist' => $seoChecklist,
            'recommendations' => $this->buildRecommendations($performanceChecks, $seoChecklist, $modules),
            'optimization_actions' => config('cyra.testing_optimization.actions', []),
            'insights' => config('cyra.testing_optimization.insights', []),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function runAction(string $action, User $user): array
    {
        $configured = collect(config('cyra.testing_optimization.actions', []))
            ->firstWhere('slug', $action);

        if ($configured === null) {
            return [
                'success' => false,
                'message' => 'Unknown optimization action.',
            ];
        }

        $command = $configured['command'] ?? null;

        if ($command === null) {
            return [
                'success' => false,
                'message' => 'Action command is not configured.',
            ];
        }

        try {
            Artisan::call($command);
        } catch (\Throwable $exception) {
            return [
                'success' => false,
                'message' => 'Optimization action failed: '.$exception->getMessage(),
            ];
        }

        return [
            'success' => true,
            'action' => $action,
            'label' => $configured['label'],
            'message' => $configured['success_message'] ?? 'Optimization action completed successfully.',
            'output' => trim(Artisan::output()),
            'triggered_by' => $user->name,
            'completed_at' => now()->toIso8601String(),
        ];
    }

    /**
     * @param  array<string, mixed>  $health
     * @return list<array<string, mixed>>
     */
    private function buildHealthChecks(array $health): array
    {
        $checks = config('cyra.testing_optimization.health_checks', []);

        return collect($checks)->map(function (array $check) use ($health) {
            $status = match ($check['slug']) {
                'application' => ($health['checks']['application'] ?? false) ? 'pass' : 'fail',
                'database' => ($health['checks']['database'] ?? false) ? 'pass' : 'fail',
                'storage' => is_writable(storage_path()) ? 'pass' : 'fail',
                'cache' => is_writable(base_path('bootstrap/cache')) ? 'pass' : 'fail',
                'assets' => File::exists(public_path('build/manifest.json')) ? 'pass' : 'warn',
                default => 'pass',
            };

            return [
                ...$check,
                'status' => $status,
                'status_label' => ucfirst($status),
            ];
        })->values()->all();
    }

    /**
     * @param  list<array<string, mixed>>  $modules
     * @param  array<string, mixed>  $testMetrics
     * @return list<array<string, mixed>>
     */
    private function buildModuleQa(array $modules, array $testMetrics): array
    {
        $suiteMap = collect($testMetrics['suites'])->keyBy('slug');

        return collect($modules)->map(function (array $module) use ($suiteMap) {
            $suite = $suiteMap->get($module['slug']);
            $hasTests = ($suite['test_count'] ?? 0) > 0;

            return [
                'id' => $module['id'],
                'slug' => $module['slug'],
                'name' => $module['name'],
                'status' => $module['status'],
                'status_label' => ucfirst($module['status']),
                'test_files' => $suite['file_count'] ?? 0,
                'test_count' => $suite['test_count'] ?? 0,
                'qa_status' => $module['status'] === 'completed' && $hasTests ? 'verified' : ($module['status'] === 'completed' ? 'complete' : 'pending'),
            ];
        })->values()->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function buildTestMetrics(): array
    {
        $suites = config('cyra.testing_optimization.test_suites', []);
        $testPath = base_path('tests/Feature');

        if (! File::isDirectory($testPath)) {
            return [
                'total_files' => 0,
                'total_tests' => 0,
                'suites' => [],
            ];
        }

        $files = collect(File::files($testPath))
            ->map(fn ($file) => $file->getFilename())
            ->values();

        $totalTests = 0;
        $mappedSuites = collect($suites)->map(function (array $suite) use ($files, $testPath, &$totalTests) {
            $matched = $files->filter(function (string $filename) use ($suite) {
                foreach ($suite['patterns'] ?? [] as $pattern) {
                    if (str_contains(strtolower($filename), strtolower($pattern))) {
                        return true;
                    }
                }

                return false;
            });

            $testCount = $this->countTestsInFiles(
                $matched->map(fn (string $filename) => $testPath.DIRECTORY_SEPARATOR.$filename)->all(),
            );

            $totalTests += $testCount;

            return [
                'slug' => $suite['slug'],
                'label' => $suite['label'],
                'file_count' => $matched->count(),
                'test_count' => $testCount,
                'files' => $matched->values()->all(),
            ];
        })->values()->all();

        return [
            'total_files' => $files->count(),
            'total_tests' => $totalTests,
            'suites' => $mappedSuites,
        ];
    }

    /**
     * @param  list<string>  $files
     */
    private function countTestsInFiles(array $files): int
    {
        $count = 0;

        foreach ($files as $file) {
            if (! File::exists($file)) {
                continue;
            }

            preg_match_all('/function test_[a-z0-9_]+\(/i', File::get($file), $matches);
            $count += count($matches[0] ?? []);
        }

        return $count;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildPerformanceChecks(): array
    {
        return collect(config('cyra.testing_optimization.performance_checks', []))
            ->map(function (array $check) {
                $status = match ($check['slug']) {
                    'config_cache' => File::exists(base_path('bootstrap/cache/config.php')) ? 'pass' : 'warn',
                    'route_cache' => File::exists(base_path('bootstrap/cache/routes-v7.php')) ? 'pass' : 'warn',
                    'view_cache' => count(File::glob(storage_path('framework/views/*.php')) ?: []) > 0 ? 'pass' : 'warn',
                    'compiled_assets' => File::exists(public_path('build/manifest.json')) ? 'pass' : 'fail',
                    'api_health' => Route::has('api.health') ? 'pass' : 'fail',
                    default => 'pass',
                };

                return [
                    ...$check,
                    'status' => $status,
                    'status_label' => ucfirst($status),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildSeoChecklist(): array
    {
        $publishedPages = CmsPage::query()->where('status', 'published')->count();
        $legalPages = CmsPage::query()
            ->where('status', 'published')
            ->whereIn('slug', ['privacy-policy', 'terms-of-service', 'cookie-policy'])
            ->count();

        return collect(config('cyra.testing_optimization.seo_checks', []))
            ->map(function (array $check) use ($publishedPages, $legalPages) {
                $status = match ($check['slug']) {
                    'published_cms_pages' => $publishedPages >= (int) ($check['minimum'] ?? 1) ? 'pass' : 'warn',
                    'legal_pages' => $legalPages >= 3 ? 'pass' : 'warn',
                    'homepage_route' => Route::has('home') ? 'pass' : 'fail',
                    'contact_route' => Route::has('contact') ? 'pass' : 'fail',
                    'sitemap_ready' => $publishedPages > 0 ? 'pass' : 'warn',
                    default => 'pass',
                };

                return [
                    ...$check,
                    'status' => $status,
                    'status_label' => ucfirst($status),
                    'current_value' => match ($check['slug']) {
                        'published_cms_pages' => $publishedPages,
                        'legal_pages' => $legalPages,
                        default => null,
                    },
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  list<array<string, mixed>>  $performanceChecks
     * @param  list<array<string, mixed>>  $seoChecklist
     * @param  list<array<string, mixed>>  $modules
     * @return list<array<string, mixed>>
     */
    private function buildRecommendations(array $performanceChecks, array $seoChecklist, array $modules): array
    {
        $recommendations = collect(config('cyra.testing_optimization.recommendations', []));

        $pendingModules = collect($modules)->where('status', '!=', 'completed')->count();
        if ($pendingModules > 0) {
            $recommendations->prepend([
                'title' => 'Complete remaining platform modules',
                'description' => "{$pendingModules} module(s) remain pending in the roadmap.",
                'priority' => 'high',
            ]);
        }

        if (collect($performanceChecks)->contains(fn (array $check) => $check['slug'] === 'config_cache' && $check['status'] === 'warn')) {
            $recommendations->push([
                'title' => 'Cache configuration for production',
                'description' => 'Run config cache optimization before deployment to improve bootstrap performance.',
                'priority' => 'medium',
            ]);
        }

        if (collect($seoChecklist)->contains(fn (array $check) => $check['status'] === 'warn')) {
            $recommendations->push([
                'title' => 'Review SEO checklist items',
                'description' => 'One or more SEO readiness checks need attention before launch.',
                'priority' => 'medium',
            ]);
        }

        return $recommendations->take(6)->values()->all();
    }

    /**
     * @param  array<string, mixed>  $health
     * @param  array<string, mixed>  $testMetrics
     * @param  list<array<string, mixed>>  $performanceChecks
     * @param  list<array<string, mixed>>  $seoChecklist
     */
    private function calculateHealthScore(array $health, array $testMetrics, array $performanceChecks, array $seoChecklist): int
    {
        $score = 0;

        if ($health['checks']['application'] ?? false) {
            $score += 20;
        }

        if ($health['checks']['database'] ?? false) {
            $score += 20;
        }

        if (($testMetrics['total_tests'] ?? 0) >= 150) {
            $score += 25;
        } elseif (($testMetrics['total_tests'] ?? 0) >= 100) {
            $score += 15;
        }

        $perfPass = collect($performanceChecks)->where('status', 'pass')->count();
        $score += (int) round(($perfPass / max(count($performanceChecks), 1)) * 20);

        $seoPass = collect($seoChecklist)->where('status', 'pass')->count();
        $score += (int) round(($seoPass / max(count($seoChecklist), 1)) * 15);

        return min(100, $score);
    }

    /**
     * @param  list<array<string, mixed>>  $seoChecklist
     */
    private function calculateSeoScore(array $seoChecklist): int
    {
        if ($seoChecklist === []) {
            return 0;
        }

        $pass = collect($seoChecklist)->where('status', 'pass')->count();

        return (int) round(($pass / count($seoChecklist)) * 100);
    }
}
