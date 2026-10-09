@extends('layouts.admin')

@section('title', $registration['reference'].' | Student Registration')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Student Registrations', 'href' => route('admin.students.index')],
        ['label' => $registration['reference']],
    ]" class="mb-6" />

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <x-ui.section-heading
            eyebrow="Student Registration"
            :title="$registration['name']"
            :description="$registration['reference'].' · '.$registration['registered_at']"
            class="cyra-section-heading !mb-0"
        />
        <x-ui.button href="{{ route('admin.students.index') }}" variant="secondary">
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
            'Education' => $registration['education_level_label'],
            'Field of study' => $registration['field_of_study'],
            'Skill level' => $registration['skill_level_label'] ?: '—',
            'Previous tech experience' => $registration['has_tech_experience_label'],
            'Program' => $registration['program_label'],
            'Program fee' => $registration['amount_formatted'],
            'Payment plan' => $registration['payment_plan_label'],
            'Amount paid' => $registration['amount_paid_formatted'],
            'Balance remaining' => ($registration['amount_remaining'] ?? 0) > 0 ? $registration['amount_remaining_formatted'] : '—',
            'Payment status' => $registration['payment_status_label'],
            'Paid at' => $registration['paid_at'] ?? '—',
            'Paystack reference' => $registration['paystack_reference'] ?? '—',
            'Status' => ucfirst($registration['status']),
        ] as $label => $value)
            <div class="cyra-card p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-cyra-muted">{{ $label }}</p>
                <p class="mt-2 text-sm font-medium text-cyra-text">{{ $value }}</p>
            </div>
        @endforeach
    </div>

@endsection
