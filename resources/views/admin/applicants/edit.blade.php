@extends('layouts.admin')

@section('title', 'Edit Applicant')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Applicants', 'href' => route('admin.applicants.index')],
        ['label' => $applicant['reference']],
    ]" class="mb-6" />

    <x-ui.section-heading
        class="mb-8"
        eyebrow="Applicant"
        :title="$applicant['name']"
        :description="$applicant['role_title']"
    />

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    <x-ui.card title="Application Details" class="mb-6">
        <dl class="grid gap-4 md:grid-cols-2 text-sm">
            <div><dt class="text-cyra-muted">Reference</dt><dd class="font-mono text-cyra-accent">{{ $applicant['reference'] }}</dd></div>
            <div><dt class="text-cyra-muted">Email</dt><dd>{{ $applicant['email'] }}</dd></div>
            <div><dt class="text-cyra-muted">Phone</dt><dd>{{ $applicant['phone'] ?? '—' }}</dd></div>
            <div><dt class="text-cyra-muted">Applied</dt><dd>{{ $applicant['applied_at'] }}</dd></div>
            @if (! empty($applicant['cover_letter']))
                <div class="md:col-span-2">
                    <dt class="text-cyra-muted">Cover Letter</dt>
                    <dd class="mt-1 whitespace-pre-wrap">{{ $applicant['cover_letter'] }}</dd>
                </div>
            @endif
        </dl>
    </x-ui.card>

    <form method="POST" action="{{ route('admin.applicants.update', $applicant['reference']) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <x-ui.card title="Pipeline">
            <div class="grid gap-6">
                <x-ui.select name="status" label="Status" required>
                    @foreach ($statusOptions as $status)
                        <option value="{{ $status }}" @selected(old('status', $applicant['status']) === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </x-ui.select>

                <x-ui.textarea name="notes" label="Internal Notes" rows="5">{{ old('notes', $applicant['notes'] ?? '') }}</x-ui.textarea>
            </div>
        </x-ui.card>

        <div class="flex flex-wrap items-center gap-3">
            <x-ui.button type="submit">Save Changes</x-ui.button>
            <x-ui.button href="{{ route('admin.applicants.index') }}" variant="secondary">Cancel</x-ui.button>

            @if (auth()->user()?->hasPermission('crm.update'))
                <x-ui.button type="submit" form="delete-applicant-form" variant="danger" class="ml-auto" onclick="return confirm('Delete this applicant permanently?')">
                    Delete Applicant
                </x-ui.button>
            @endif
        </div>
    </form>

    @if (auth()->user()?->hasPermission('crm.update'))
        <form id="delete-applicant-form" method="POST" action="{{ route('admin.applicants.destroy', $applicant['reference']) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif
@endsection
