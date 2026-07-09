@extends('layouts.admin')

@section('title', 'Contact Inquiries')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Contact'],
    ]" class="mb-6" />

    <x-ui.section-heading
        eyebrow="Growth Workspace"
        title="Contact Inquiries"
        description="{{ $contact['description'] }}"
        class="cyra-section-heading"
    />

    <div class="mb-6 grid gap-4 md:grid-cols-3">
        <x-ui.metric-card label="Total Inquiries" :value="(string) $contact['summary']['total']" accent="text-cyra-accent" />
        <x-ui.metric-card label="Pending" :value="(string) $contact['summary']['pending']" accent="text-cyra-warning" />
        <x-ui.metric-card label="Converted" :value="(string) $contact['summary']['converted']" accent="text-cyra-success" />
    </div>

    <x-ui.card title="Inquiry Queue" description="Inbound messages from the public contact form.">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-cyra-border text-sm">
                <thead>
                    <tr class="text-left text-cyra-muted">
                        <th class="px-4 py-3 font-medium">Reference</th>
                        <th class="px-4 py-3 font-medium">Contact</th>
                        <th class="px-4 py-3 font-medium">Type</th>
                        <th class="px-4 py-3 font-medium">Submitted</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        @if (auth()->user()?->hasPermission('crm.update'))
                            <th class="px-4 py-3 font-medium">Actions</th>
                        @endif
                </thead>
                <tbody class="divide-y divide-cyra-border/70">
                    @forelse ($contact['inquiries'] as $inquiry)
                        <tr>
                            <td class="px-4 py-4 font-mono text-xs text-cyra-accent">{{ $inquiry['reference'] }}</td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-cyra-text">{{ $inquiry['name'] }}</p>
                                <p class="text-xs text-cyra-muted">{{ $inquiry['email'] }}</p>
                                @if (! empty($inquiry['company']))
                                    <p class="text-xs text-cyra-muted">{{ $inquiry['company'] }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <x-ui.badge variant="primary">{{ $inquiry['inquiry_type_label'] }}</x-ui.badge>
                            </td>
                            <td class="px-4 py-4 text-cyra-muted">{{ $inquiry['submitted_ago'] }}</td>
                            <td class="px-4 py-4">
                                <x-ui.badge :variant="$inquiry['status'] === 'pending' ? 'warning' : 'success'">
                                    {{ ucfirst($inquiry['status']) }}
                                </x-ui.badge>
                            </td>
                            @if (auth()->user()?->hasPermission('crm.update'))
                                <td class="px-4 py-4">
                                    <x-ui.button href="{{ $inquiry['edit_url'] }}" variant="secondary" size="sm">
                                        Edit
                                    </x-ui.button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-cyra-muted">
                                No contact inquiries yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endsection
