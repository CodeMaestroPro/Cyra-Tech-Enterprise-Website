@extends('layouts.admin')

@section('title', 'Edit Inquiry')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Contact', 'href' => route('admin.contact.index')],
        ['label' => $inquiry['reference']],
    ]" class="mb-6" />

    <x-ui.section-heading
        class="mb-8"
        eyebrow="Contact Inquiry"
        :title="$inquiry['reference']"
        description="Review and update inquiry status."
    />

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    <x-ui.card title="Inquiry Details" class="mb-6">
        <dl class="grid gap-4 md:grid-cols-2 text-sm">
            <div>
                <dt class="text-cyra-muted">Name</dt>
                <dd class="font-medium text-cyra-text">{{ $inquiry['name'] }}</dd>
            </div>
            <div>
                <dt class="text-cyra-muted">Email</dt>
                <dd class="font-medium text-cyra-text">{{ $inquiry['email'] }}</dd>
            </div>
            <div>
                <dt class="text-cyra-muted">Type</dt>
                <dd>{{ $inquiry['inquiry_type_label'] }}</dd>
            </div>
            <div>
                <dt class="text-cyra-muted">Submitted</dt>
                <dd>{{ $inquiry['submitted_at'] }}</dd>
            </div>
            <div class="md:col-span-2">
                <dt class="text-cyra-muted">Message</dt>
                <dd class="mt-1 whitespace-pre-wrap text-cyra-text">{{ $inquiry['message'] }}</dd>
            </div>
        </dl>
    </x-ui.card>

    <form method="POST" action="{{ route('admin.contact.update', $inquiry['reference']) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <x-ui.card title="Status">
            <x-ui.select name="status" label="Status" required>
                @foreach ($statusOptions as $status)
                    <option value="{{ $status }}" @selected(old('status', $inquiry['status']) === $status)>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </x-ui.select>
        </x-ui.card>

        <div class="flex flex-wrap items-center gap-3">
            <x-ui.button type="submit">Save Changes</x-ui.button>
            <x-ui.button href="{{ route('admin.contact.index') }}" variant="secondary">Cancel</x-ui.button>

            @if (auth()->user()?->hasPermission('crm.update'))
                <x-ui.button
                    type="submit"
                    form="delete-inquiry-form"
                    variant="danger"
                    class="ml-auto"
                    onclick="return confirm('Delete this inquiry permanently?')"
                >
                    Delete Inquiry
                </x-ui.button>
            @endif
        </div>
    </form>

    @if (auth()->user()?->hasPermission('crm.update'))
        <form
            id="delete-inquiry-form"
            method="POST"
            action="{{ route('admin.contact.destroy', $inquiry['reference']) }}"
            class="hidden"
        >
            @csrf
            @method('DELETE')
        </form>
    @endif
@endsection
