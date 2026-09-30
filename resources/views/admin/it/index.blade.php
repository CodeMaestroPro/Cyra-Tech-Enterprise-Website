@extends('layouts.admin')

@section('title', 'IT Registrations')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'IT Registrations'],
    ]" class="mb-6" />

    <x-ui.section-heading
        eyebrow="People"
        title="IT Registrations"
        description="{{ $workspace['description'] }}"
        class="cyra-section-heading"
    />

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @if (session('error'))
        <x-ui.alert variant="error" class="mb-6">{{ session('error') }}</x-ui.alert>
    @endif

    <div class="mb-6 grid gap-4 sm:grid-cols-2">
        <x-ui.metric-card label="Total Registrations" :value="(string) $workspace['summary']['total']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Registered" :value="(string) $workspace['summary']['registered']" accent="text-cyra-success" />
    </div>

    <x-ui.card title="Student Intake" description="Everyone who completed the IT registration form.">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-cyra-border text-sm">
                <thead>
                    <tr class="text-left text-cyra-muted">
                        <th class="px-4 py-3 font-medium">Reference</th>
                        <th class="px-4 py-3 font-medium">Student</th>
                        <th class="px-4 py-3 font-medium">Interest</th>
                        <th class="px-4 py-3 font-medium">Availability</th>
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
                                <x-ui.badge variant="primary">{{ $registration['interest_area_label'] }}</x-ui.badge>
                                <p class="mt-1 text-xs text-cyra-muted">{{ $registration['institution'] }}</p>
                            </td>
                            <td class="px-4 py-4 text-cyra-muted">{{ $registration['availability_label'] }}</td>
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
                                No IT registrations yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endsection
