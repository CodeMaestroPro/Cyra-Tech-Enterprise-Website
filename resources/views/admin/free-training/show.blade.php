@extends('layouts.admin')

@section('title', $registration['reference'].' | Free Training')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Free Training', 'href' => route('admin.free-training.index')],
        ['label' => $registration['reference']],
    ]" class="mb-6" />

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <x-ui.section-heading
            eyebrow="Free Training"
            :title="$registration['name']"
            :description="$registration['reference'].' · '.$registration['registered_at']"
            class="cyra-section-heading !mb-0"
        />
        <x-ui.button href="{{ route('admin.free-training.index') }}" variant="secondary">
            Back to list
        </x-ui.button>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        @foreach ([
            'Email' => $registration['email'],
            'Phone' => $registration['phone'],
            'Date of birth' => $registration['date_of_birth'] ?: '—',
            'Age' => $registration['age'] ? $registration['age'].' years' : '—',
            'Gender' => $registration['gender_label'] ?: '—',
            'Location' => $registration['location'] ?: '—',
            'Skill level' => $registration['skill_level_label'] ?: '—',
            'Previous tech experience' => $registration['has_tech_experience_label'],
            'Course' => $registration['program_label'],
            'Status' => ucfirst($registration['status']),
        ] as $label => $value)
            <div class="cyra-card p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-cyra-muted">{{ $label }}</p>
                <p class="mt-2 text-sm font-medium text-cyra-text">{{ $value }}</p>
            </div>
        @endforeach
    </div>
@endsection
