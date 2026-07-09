@extends('layouts.admin')

@section('title', 'Testing & Optimization')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Testing & Optimization'],
    ]" class="mb-6" />

    <x-ui.section-heading
        eyebrow="Module 25 Complete"
        title="Testing & Optimization"
        description="Platform QA readiness, health monitoring, performance checks, SEO validation, and optimization actions for the Cyra-Tech enterprise stack."
        class="cyra-section-heading"
    />

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @if (session('error'))
        <x-ui.alert variant="danger" class="mb-6">{{ session('error') }}</x-ui.alert>
    @endif

    <div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <x-ui.metric-card label="Health Score" :value="$dashboard['summary']['health_score'].'%'" accent="text-cyra-success" />
        <x-ui.metric-card label="Modules Complete" :value="$dashboard['summary']['modules_completed'].'/'.$dashboard['summary']['modules_total']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Feature Tests" :value="(string) $dashboard['summary']['feature_tests']" accent="text-cyra-primary" />
        <x-ui.metric-card label="Test Files" :value="(string) $dashboard['summary']['feature_test_files']" accent="text-cyra-purple" />
        <x-ui.metric-card label="SEO Score" :value="$dashboard['summary']['seo_score'].'%'" accent="text-cyra-warning" />
    </div>

    <div class="mb-6 grid gap-6 xl:grid-cols-2">
        <x-ui.card title="Platform Health Checks" description="Live infrastructure and application readiness signals.">
            <div class="space-y-3">
                @foreach ($dashboard['health_checks'] as $check)
                    <div class="flex items-center justify-between rounded-lg border border-cyra-border bg-cyra-navy/40 px-4 py-3">
                        <div>
                            <p class="font-medium text-cyra-text">{{ $check['label'] }}</p>
                            <p class="text-xs text-cyra-muted">{{ $check['description'] }}</p>
                        </div>
                        <x-ui.badge :variant="match ($check['status']) {
                            'pass' => 'success',
                            'warn' => 'warning',
                            default => 'danger',
                        }">
                            {{ $check['status_label'] }}
                        </x-ui.badge>
                    </div>
                @endforeach
            </div>
        </x-ui.card>

        <x-ui.card title="Performance Checks" description="Deployment optimization and runtime performance indicators.">
            <div class="space-y-3">
                @foreach ($dashboard['performance_checks'] as $check)
                    <div class="flex items-center justify-between rounded-lg border border-cyra-border bg-cyra-navy/40 px-4 py-3">
                        <div>
                            <p class="font-medium text-cyra-text">{{ $check['label'] }}</p>
                            <p class="text-xs text-cyra-muted">{{ $check['description'] }}</p>
                        </div>
                        <x-ui.badge :variant="match ($check['status']) {
                            'pass' => 'success',
                            'warn' => 'warning',
                            default => 'danger',
                        }">
                            {{ $check['status_label'] }}
                        </x-ui.badge>
                    </div>
                @endforeach
            </div>
        </x-ui.card>
    </div>

    <x-ui.card title="Module QA Matrix" description="Roadmap completion status mapped to feature test coverage." class="mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-cyra-border text-sm">
                <thead>
                    <tr class="text-left text-cyra-muted">
                        <th class="px-3 py-2 font-medium">Module</th>
                        <th class="px-3 py-2 font-medium">Status</th>
                        <th class="px-3 py-2 font-medium">Test Files</th>
                        <th class="px-3 py-2 font-medium">Tests</th>
                        <th class="px-3 py-2 font-medium">QA</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyra-border/60">
                    @foreach ($dashboard['module_qa'] as $module)
                        <tr>
                            <td class="px-3 py-3">
                                <p class="font-medium text-cyra-text">{{ $module['name'] }}</p>
                                <p class="text-xs text-cyra-muted">{{ $module['slug'] }}</p>
                            </td>
                            <td class="px-3 py-3">
                                <x-ui.badge :variant="$module['status'] === 'completed' ? 'success' : 'warning'">
                                    {{ $module['status_label'] }}
                                </x-ui.badge>
                            </td>
                            <td class="px-3 py-3">{{ $module['test_files'] }}</td>
                            <td class="px-3 py-3">{{ $module['test_count'] }}</td>
                            <td class="px-3 py-3">
                                <x-ui.badge :variant="match ($module['qa_status']) {
                                    'verified' => 'success',
                                    'complete' => 'primary',
                                    default => 'warning',
                                }">
                                    {{ ucfirst($module['qa_status']) }}
                                </x-ui.badge>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.card>

    <div class="mb-6 grid gap-6 xl:grid-cols-2">
        <x-ui.card title="SEO Readiness Checklist" description="Public content and discoverability validation.">
            <div class="space-y-3">
                @foreach ($dashboard['seo_checklist'] as $check)
                    <div class="flex items-center justify-between rounded-lg border border-cyra-border bg-cyra-navy/40 px-4 py-3">
                        <div>
                            <p class="font-medium text-cyra-text">{{ $check['label'] }}</p>
                            <p class="text-xs text-cyra-muted">{{ $check['description'] }}</p>
                            @if (($check['current_value'] ?? null) !== null)
                                <p class="mt-1 text-xs text-cyra-accent">Current: {{ $check['current_value'] }}</p>
                            @endif
                        </div>
                        <x-ui.badge :variant="match ($check['status']) {
                            'pass' => 'success',
                            'warn' => 'warning',
                            default => 'danger',
                        }">
                            {{ $check['status_label'] }}
                        </x-ui.badge>
                    </div>
                @endforeach
            </div>
        </x-ui.card>

        <x-ui.card title="Optimization Recommendations" description="Prioritized actions to improve platform readiness.">
            <div class="space-y-3">
                @forelse ($dashboard['recommendations'] as $recommendation)
                    <div class="rounded-lg border border-cyra-border bg-cyra-navy/40 px-4 py-3">
                        <div class="mb-1 flex items-center justify-between gap-2">
                            <p class="font-medium text-cyra-text">{{ $recommendation['title'] }}</p>
                            <x-ui.badge :variant="match ($recommendation['priority']) {
                                'high' => 'danger',
                                'medium' => 'warning',
                                default => 'primary',
                            }">
                                {{ ucfirst($recommendation['priority']) }}
                            </x-ui.badge>
                        </div>
                        <p class="text-xs text-cyra-muted">{{ $recommendation['description'] }}</p>
                    </div>
                @empty
                    <p class="text-sm text-cyra-muted">All optimization recommendations are currently addressed.</p>
                @endforelse
            </div>

            @if (count($dashboard['insights']) > 0)
                <div class="mt-6 border-t border-cyra-border pt-4">
                    <p class="mb-2 text-sm font-medium text-cyra-text">Platform Insights</p>
                    <ul class="space-y-2 text-xs text-cyra-muted">
                        @foreach ($dashboard['insights'] as $insight)
                            <li>{{ $insight }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </x-ui.card>
    </div>

    @if (auth()->user()?->hasPermission('optimization.manage'))
        <x-ui.card title="Optimization Actions" description="Run safe maintenance commands to optimize the Cyra-Tech platform.">
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($dashboard['optimization_actions'] as $action)
                    <form method="POST" action="{{ route('admin.optimization.actions') }}" class="rounded-lg border border-cyra-border bg-cyra-navy/40 p-4">
                        @csrf
                        <input type="hidden" name="action" value="{{ $action['slug'] }}">
                        <p class="font-medium text-cyra-text">{{ $action['label'] }}</p>
                        <p class="mt-1 text-xs text-cyra-muted">{{ $action['description'] }}</p>
                        <x-ui.button type="submit" variant="secondary" class="mt-4 text-xs">Run Action</x-ui.button>
                    </form>
                @endforeach
            </div>
        </x-ui.card>
    @endif
@endsection
