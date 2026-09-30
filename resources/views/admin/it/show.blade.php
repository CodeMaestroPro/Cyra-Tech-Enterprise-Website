@extends('layouts.admin')

@section('title', $registration['reference'].' | IT Registration')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'IT Registrations', 'href' => route('admin.it.index')],
        ['label' => $registration['reference']],
    ]" class="mb-6" />

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <x-ui.section-heading
            eyebrow="IT Registration"
            :title="$registration['name']"
            :description="$registration['reference'].' · '.$registration['registered_at']"
            class="cyra-section-heading !mb-0"
        />
        <x-ui.button href="{{ route('admin.it.index') }}" variant="secondary">
            Back to list
        </x-ui.button>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        @foreach ([
            'Email' => $registration['email'],
            'Phone' => $registration['phone'],
            'Institution' => $registration['institution'],
            'Course of study' => $registration['course_of_study'],
            'Academic level' => $registration['academic_level_label'],
            'Area of interest' => $registration['interest_area_label'],
            'Availability' => $registration['availability_label'],
            'Status' => ucfirst($registration['status']),
        ] as $label => $value)
            <div class="cyra-card p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-cyra-muted">{{ $label }}</p>
                <p class="mt-2 text-sm font-medium text-cyra-text">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="cyra-card mt-4 p-5 sm:p-6">
        <p class="text-xs font-semibold uppercase tracking-wide text-cyra-muted">Motivation</p>
        <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-cyra-text">{{ $registration['motivation'] }}</p>
    </div>
@endsection
