@extends('layouts.admin')

@section('title', 'Student Registrations')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Student Registrations'],
    ]" class="mb-6" />

    <x-ui.section-heading
        eyebrow="People"
        title="Student Registrations"
        description="{{ $workspace['description'] }}"
        class="cyra-section-heading"
    />

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @if (session('error'))
        <x-ui.alert variant="error" class="mb-6">{{ session('error') }}</x-ui.alert>
    @endif

    <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.metric-card label="Total Registrations" :value="(string) $workspace['summary']['total']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Paid in Full" :value="(string) $workspace['summary']['paid']" accent="text-cyra-success" />
        <x-ui.metric-card label="Partially Paid" :value="(string) $workspace['summary']['partial']" accent="text-amber-600" />
        <x-ui.metric-card label="Pending Payment" :value="(string) $workspace['summary']['pending']" accent="text-cyra-muted" />
    </div>

    <x-ui.card title="Training Intake" description="Everyone who registered for a paid training program.">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-cyra-border text-sm">
                <thead>
                    <tr class="text-left text-cyra-muted">
                        <th class="px-4 py-3 font-medium">Reference</th>
                        <th class="px-4 py-3 font-medium">Student</th>
                        <th class="px-4 py-3 font-medium">Program</th>
                        <th class="px-4 py-3 font-medium">Amount</th>
                        <th class="px-4 py-3 font-medium">Payment</th>
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
                                <p class="mt-1 text-xs text-cyra-muted">{{ $registration['institution'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-cyra-text">{{ $registration['amount_paid_formatted'] }}</p>
                                <p class="text-xs text-cyra-muted">of {{ $registration['amount_formatted'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                @if ($registration['payment_status'] === 'paid')
                                    <x-ui.badge variant="success">Paid</x-ui.badge>
                                @elseif ($registration['payment_status'] === 'partial')
                                    <x-ui.badge variant="warning">Partial</x-ui.badge>
                                @elseif ($registration['payment_status'] === 'failed')
                                    <x-ui.badge variant="danger">Failed</x-ui.badge>
                                @else
                                    <x-ui.badge variant="warning">Pending</x-ui.badge>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-cyra-muted">{{ $registration['submitted_ago'] }}</td>
                            <td class="px-4 py-4">
                                <x-ui.button href="{{ $registration['show_url'] }}" variant="secondary" size="sm">
                                    View
                                </x-ui.button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-cyra-muted">
                                No student registrations yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endsection
