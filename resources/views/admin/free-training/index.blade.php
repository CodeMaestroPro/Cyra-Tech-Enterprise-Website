@extends('layouts.admin')

@section('title', 'Free Training')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Free Training'],
    ]" class="mb-6" />

    <x-ui.section-heading
        eyebrow="People"
        title="Free Training"
        description="{{ $workspace['description'] }}"
        class="cyra-section-heading"
    />

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @if (session('error'))
        <x-ui.alert variant="error" class="mb-6">{{ session('error') }}</x-ui.alert>
    @endif

    <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <x-ui.metric-card label="Total Enrolled" :value="(string) $workspace['summary']['total']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Software Dev" :value="(string) ($workspace['summary']['by_program']['software-development'] ?? 0)" accent="text-cyra-success" />
        <x-ui.metric-card label="AI Automation" :value="(string) ($workspace['summary']['by_program']['ai-automation'] ?? 0)" accent="text-cyra-success" />
        <x-ui.metric-card label="Data Analysis" :value="(string) ($workspace['summary']['by_program']['data-analysis'] ?? 0)" accent="text-cyra-success" />
        <x-ui.metric-card label="Cyber Security" :value="(string) ($workspace['summary']['by_program']['cyber-security'] ?? 0)" accent="text-cyra-success" />
    </div>

    <x-ui.card title="Free Training Intake" description="Everyone who onboarded for a free Cyra-Tech course.">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-cyra-border text-sm">
                <thead>
                    <tr class="text-left text-cyra-muted">
                        <th class="px-4 py-3 font-medium">Reference</th>
                        <th class="px-4 py-3 font-medium">Learner</th>
                        <th class="px-4 py-3 font-medium">Course</th>
                        <th class="px-4 py-3 font-medium">Skill</th>
                        <th class="px-4 py-3 font-medium">Submitted</th>
                        <th class="px-4 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyra-border/70">
                    @forelse ($workspace['registrations'] as $registration)
                        <tr>
                            <td class="px-4 py-4 font-mono text-xs text-cyra-accent">{{ $registration['reference'] }}</td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-cyra-text">{{ $registration['name'] }}</p>
                                <p class="text-xs text-cyra-muted">{{ $registration['email'] }}</p>
                                <p class="text-xs text-cyra-muted">{{ $registration['phone'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <x-ui.badge variant="primary">{{ $registration['program_label'] }}</x-ui.badge>
                                <p class="mt-1 text-xs text-cyra-muted">{{ $registration['location'] ?: '—' }}</p>
                            </td>
                            <td class="px-4 py-4 text-cyra-muted">{{ $registration['skill_level_label'] }}</td>
                            <td class="px-4 py-4 text-cyra-muted">{{ $registration['submitted_ago'] }}</td>
                            <td class="px-4 py-4">
                                <x-ui.button href="{{ $registration['show_url'] }}" variant="secondary" size="sm">
                                    View
                                </x-ui.button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-cyra-muted">
                                No free training registrations yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endsection
