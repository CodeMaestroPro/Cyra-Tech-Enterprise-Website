@extends('layouts.app')

@section('title', $page['seo']['title'] ?? 'IT Student Registration')

@push('head')
    <meta name="description" content="{{ $page['seo']['description'] ?? '' }}">
    @if (! empty($page['seo']['keywords']))
        <meta name="keywords" content="{{ implode(', ', $page['seo']['keywords']) }}">
    @endif
    <meta property="og:title" content="{{ $page['seo']['title'] ?? 'IT Student Registration' }}">
    <meta property="og:description" content="{{ $page['seo']['description'] ?? '' }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @media print {
            body * { visibility: hidden !important; }
            #it-student-acknowledgement-slip,
            #it-student-acknowledgement-slip * { visibility: visible !important; }
            #it-student-acknowledgement-slip {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
            }
            .no-print { display: none !important; }
        }
    </style>
@endpush

@section('content')
    @php
        $hero = $page['hero'] ?? [];
        $form = $page['form'] ?? [];
        $highlights = $page['highlights'] ?? [];
        $whatsapp = $page['whatsapp'] ?? [];
        $acknowledgement = $page['acknowledgement'] ?? [];
        $steps = $form['steps'] ?? [
            ['key' => 'personal', 'label' => 'Personal', 'title' => 'Personal details'],
            ['key' => 'education', 'label' => 'Education', 'title' => 'Education details'],
            ['key' => 'interest', 'label' => 'Interest', 'title' => 'Interest & availability'],
        ];
        $hasSuccess = session('success') && $registration;
        $initialStep = 1;
        if ($errors->hasAny(['institution', 'course_of_study', 'academic_level'])) {
            $initialStep = 2;
        } elseif ($errors->hasAny(['interest_area', 'availability', 'motivation'])) {
            $initialStep = 3;
        }
    @endphp

    <main id="main-content" data-it-registration-page>
        <section class="cyra-page-hero">
            <div class="cyra-page-hero-glow" aria-hidden="true"></div>
            <div class="cyra-container relative cyra-section-hero-inner">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'IT'],
                ]" />

                @if (! empty($hero['eyebrow']))
                    <p class="cyra-hero-badge mt-6">{{ $hero['eyebrow'] }}</p>
                @endif
                <h1 class="mt-3 cyra-display">{{ $hero['title'] ?? 'IT Student Registration' }}</h1>
                @if (! empty($hero['description']))
                    <p class="mt-4 max-w-3xl text-lg leading-relaxed text-cyra-muted">{{ $hero['description'] }}</p>
                @endif
            </div>
        </section>

        <section class="cyra-section">
            <div class="cyra-container">
                @if ($hasSuccess)
                    <div
                        id="registration-success"
                        class="mb-10"
                        data-it-success
                        data-success-title="Registration Successful"
                        data-success-text="{{ session('success') }}"
                        data-success-reference="{{ $registration['reference'] }}"
                        data-whatsapp-hint="{{ $whatsapp['hint'] ?? 'WhatsApp will open with your acknowledgement already filled in. Just tap Send.' }}"
                        @if (! empty($registration['whatsapp_links']))
                            data-whatsapp-links='@json($registration['whatsapp_links'])'
                        @endif
                    >
                        <div class="overflow-hidden rounded-3xl border border-emerald-200/40 bg-gradient-to-br from-emerald-500/10 via-cyra-surface to-cyra-surface p-6 sm:p-8">
                            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                                <div class="max-w-2xl">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-400">Registration complete</p>
                                    <h2 class="mt-2 text-2xl font-bold text-cyra-text sm:text-3xl">You're in — one last step</h2>
                                    <p class="mt-3 text-sm leading-relaxed text-cyra-muted sm:text-base">
                                        Your acknowledgement slip is ready. Tap the WhatsApp button and your slip details will already be filled in — just press <strong class="text-cyra-text">Send</strong>.
                                    </p>
                                    <p class="mt-3 font-mono text-sm text-cyra-accent">Reference: {{ $registration['reference'] }}</p>
                                </div>

                                <div class="flex flex-col gap-3 no-print">
                                    @forelse ($registration['whatsapp_links'] ?? [] as $link)
                                        <a
                                            href="{{ $link['url'] }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center justify-center gap-3 rounded-full bg-[#25D366] px-6 py-3.5 text-base font-semibold text-white shadow-lg shadow-emerald-500/25 transition hover:bg-[#1ebe57]"
                                        >
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M20.52 3.48A11.86 11.86 0 0012.04 0C5.5 0 .2 5.3.2 11.84c0 2.09.55 4.12 1.6 5.92L0 24l6.4-1.67a11.8 11.8 0 005.64 1.44h.01c6.54 0 11.84-5.3 11.84-11.84 0-3.16-1.23-6.13-3.37-8.45zM12.05 21.5h-.01a9.64 9.64 0 01-4.91-1.35l-.35-.21-3.8.99 1.02-3.7-.23-.38a9.64 9.64 0 01-1.48-5.15c0-5.33 4.34-9.66 9.68-9.66 2.58 0 5.01 1.01 6.84 2.84a9.6 9.6 0 012.83 6.83c0 5.33-4.34 9.66-9.67 9.66zm5.3-7.23c-.29-.15-1.72-.85-1.99-.94-.27-.1-.46-.15-.66.15-.19.29-.76.94-.93 1.13-.17.2-.34.22-.63.07-.29-.15-1.23-.45-2.34-1.44-.86-.77-1.45-1.72-1.62-2.01-.17-.29-.02-.45.13-.6.13-.13.29-.34.43-.51.15-.17.19-.29.29-.49.1-.2.05-.37-.02-.52-.07-.15-.66-1.59-.9-2.18-.24-.58-.48-.5-.66-.51h-.56c-.19 0-.5.07-.76.37-.26.29-1 1-1 2.43s1.02 2.82 1.16 3.01c.15.19 2.01 3.07 4.87 4.31.68.29 1.21.47 1.62.6.68.22 1.3.19 1.79.12.55-.08 1.72-.7 1.96-1.38.24-.68.24-1.26.17-1.38-.07-.12-.26-.19-.55-.34z"/>
                                            </svg>
                                            {{ $link['label'] }}
                                        </a>
                                    @empty
                                        <a
                                            href="mailto:{{ config('cyra.contact.notification_email', 'cyratech01@gmail.com') }}?subject={{ rawurlencode('IT Student Acknowledgement - '.$registration['reference']) }}&body={{ rawurlencode($registration['whatsapp_message']) }}"
                                            class="inline-flex items-center justify-center gap-2 rounded-full bg-cyra-primary px-6 py-3.5 text-base font-semibold text-white"
                                        >
                                            Email acknowledgement
                                        </a>
                                    @endforelse

                                    <button
                                        type="button"
                                        class="inline-flex items-center justify-center gap-2 rounded-full border border-cyra-border px-5 py-3 text-sm font-semibold text-cyra-text transition hover:bg-cyra-soft"
                                        onclick="window.print()"
                                    >
                                        Print acknowledgement slip
                                    </button>
                                </div>
                            </div>
                        </div>

                        <article
                            id="it-student-acknowledgement-slip"
                            class="mt-6 overflow-hidden rounded-2xl border border-cyra-border bg-white text-slate-900 shadow-xl"
                        >
                            <div class="border-b border-slate-200 bg-slate-50 px-6 py-5 sm:px-8">
                                <div class="flex flex-wrap items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">
                                            {{ $acknowledgement['subtitle'] ?? 'Cyra-Tech IT Student Registration' }}
                                        </p>
                                        <h2 class="mt-2 text-2xl font-bold text-slate-900">
                                            {{ $acknowledgement['title'] ?? 'Acknowledgement Slip' }}
                                        </h2>
                                        <p class="mt-1 text-sm text-slate-600">
                                            {{ $acknowledgement['company_line'] ?? config('cyra.name') }}
                                        </p>
                                    </div>
                                    <div class="rounded-xl bg-blue-600 px-4 py-3 text-right text-white">
                                        <p class="text-[11px] uppercase tracking-wide text-blue-100">Reference</p>
                                        <p class="mt-1 font-mono text-sm font-semibold">{{ $registration['reference'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid gap-6 px-6 py-6 sm:px-8 md:grid-cols-2">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Student Name</p>
                                    <p class="mt-1 text-base font-semibold text-slate-900">{{ $registration['name'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Registration Date</p>
                                    <p class="mt-1 text-base font-semibold text-slate-900">{{ $registration['registered_date'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</p>
                                    <p class="mt-1 text-sm text-slate-800">{{ $registration['email'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Phone</p>
                                    <p class="mt-1 text-sm text-slate-800">{{ $registration['phone'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Institution</p>
                                    <p class="mt-1 text-sm text-slate-800">{{ $registration['institution'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Course of Study</p>
                                    <p class="mt-1 text-sm text-slate-800">{{ $registration['course_of_study'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Academic Level</p>
                                    <p class="mt-1 text-sm text-slate-800">{{ $registration['academic_level_label'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Interest Area</p>
                                    <p class="mt-1 text-sm text-slate-800">{{ $registration['interest_area_label'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Availability</p>
                                    <p class="mt-1 text-sm text-slate-800">{{ $registration['availability_label'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</p>
                                    <p class="mt-1 inline-flex rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-700">
                                        Registered
                                    </p>
                                </div>
                            </div>
                        </article>
                    </div>
                @endif

                <div class="grid gap-10 lg:grid-cols-3">
                    <div class="lg:col-span-2">
                        <x-ui.section-heading
                            :eyebrow="$form['eyebrow'] ?? 'Student Registration'"
                            :title="$form['title'] ?? 'Register in 3 easy steps'"
                            :description="$form['description'] ?? null"
                            class="cyra-section-heading"
                        />

                        @if ($errors->any())
                            <x-ui.alert variant="error" class="mb-6">
                                {{ $errors->first() }}
                            </x-ui.alert>
                        @endif

                        @unless ($hasSuccess)
                            <form
                                method="POST"
                                action="{{ route('it.store') }}"
                                class="cyra-it-wizard"
                                id="it-student-registration-form"
                                data-it-wizard
                                data-initial-step="{{ $initialStep }}"
                                novalidate
                            >
                                @csrf

                                <ol class="cyra-it-wizard-steps" aria-label="Registration steps">
                                    @foreach ($steps as $index => $step)
                                        <li
                                            class="cyra-it-wizard-step"
                                            data-step-indicator="{{ $index + 1 }}"
                                        >
                                            <span class="cyra-it-wizard-step-index">{{ $index + 1 }}</span>
                                            <span class="cyra-it-wizard-step-label">{{ $step['label'] }}</span>
                                        </li>
                                    @endforeach
                                </ol>

                                <p class="cyra-it-wizard-meta" data-it-step-meta>Step 1 of {{ count($steps) }}</p>

                                <div class="cyra-it-wizard-panel" data-step-panel="1">
                                    <h2 class="cyra-it-wizard-title">{{ $steps[0]['title'] ?? 'Personal details' }}</h2>
                                    <div class="mt-5 grid gap-5 md:grid-cols-2">
                                        <x-ui.input
                                            name="name"
                                            label="Full name"
                                            placeholder="Your full name"
                                            autocomplete="name"
                                            required
                                        />
                                        <x-ui.input
                                            name="email"
                                            type="email"
                                            label="Email address"
                                            placeholder="you@example.com"
                                            autocomplete="email"
                                            required
                                        />
                                        <x-ui.input
                                            name="phone"
                                            type="tel"
                                            label="Phone / WhatsApp number"
                                            placeholder="+234 800 000 0000"
                                            autocomplete="tel"
                                            class="md:col-span-2"
                                            required
                                        />
                                    </div>
                                </div>

                                <div class="cyra-it-wizard-panel hidden" data-step-panel="2">
                                    <h2 class="cyra-it-wizard-title">{{ $steps[1]['title'] ?? 'Education details' }}</h2>
                                    <div class="mt-5 grid gap-5 md:grid-cols-2">
                                        <x-ui.input
                                            name="institution"
                                            label="School / Institution"
                                            placeholder="University or polytechnic name"
                                            required
                                        />
                                        <x-ui.input
                                            name="course_of_study"
                                            label="Course of study"
                                            placeholder="e.g. Computer Science"
                                            required
                                        />
                                        <x-ui.select
                                            name="academic_level"
                                            label="Academic level"
                                            :options="$academicLevelOptions"
                                            placeholder="Select level"
                                            class="md:col-span-2"
                                            required
                                        />
                                    </div>
                                </div>

                                <div class="cyra-it-wizard-panel hidden" data-step-panel="3">
                                    <h2 class="cyra-it-wizard-title">{{ $steps[2]['title'] ?? 'Interest & availability' }}</h2>
                                    <div class="mt-5 grid gap-5 md:grid-cols-2">
                                        <x-ui.select
                                            name="interest_area"
                                            label="Area of interest"
                                            :options="$interestAreaOptions"
                                            placeholder="Select interest"
                                            required
                                        />
                                        <x-ui.select
                                            name="availability"
                                            label="Availability"
                                            :options="$availabilityOptions"
                                            placeholder="Select availability"
                                            required
                                        />
                                        <div class="md:col-span-2">
                                            <x-ui.textarea
                                                name="motivation"
                                                label="Why do you want to join Cyra-Tech?"
                                                placeholder="Share your goals, experience level, and what you hope to learn..."
                                                rows="5"
                                                required
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="cyra-it-wizard-actions">
                                    <button
                                        type="button"
                                        class="cyra-it-wizard-btn cyra-it-wizard-btn-secondary hidden"
                                        data-it-prev
                                    >
                                        Back
                                    </button>
                                    <button
                                        type="button"
                                        class="cyra-it-wizard-btn cyra-it-wizard-btn-primary"
                                        data-it-next
                                    >
                                        Next
                                    </button>
                                    <button
                                        type="submit"
                                        class="cyra-it-wizard-btn cyra-it-wizard-btn-primary hidden"
                                        data-it-submit
                                    >
                                        {{ $form['submit_label'] ?? 'Complete Registration' }}
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="cyra-card p-6">
                                <h2 class="text-lg font-semibold text-cyra-text">Registration submitted</h2>
                                <p class="mt-2 text-sm leading-relaxed text-cyra-muted">
                                    Use the WhatsApp button above to send your acknowledgement slip. Your details stay on this page for printing.
                                </p>
                            </div>
                        @endunless
                    </div>

                    <aside class="space-y-6">
                        @foreach ($highlights as $highlight)
                            <div class="cyra-card p-5 sm:p-6">
                                <h2 class="text-lg font-semibold text-cyra-text">{{ $highlight['title'] ?? '' }}</h2>
                                <p class="mt-2 text-sm leading-relaxed text-cyra-muted">{{ $highlight['description'] ?? '' }}</p>
                            </div>
                        @endforeach

                        <div class="cyra-chip p-6">
                            <h2 class="text-sm font-semibold uppercase tracking-wide text-cyra-muted">Need help?</h2>
                            <p class="mt-2 text-sm leading-relaxed text-cyra-text">
                                Questions about the IT intake? Email
                                <a href="mailto:{{ config('cyra.contact.notification_email', 'cyratech01@gmail.com') }}" class="font-semibold text-cyra-primary hover:text-cyra-primary-hover">
                                    {{ config('cyra.contact.notification_email', 'cyratech01@gmail.com') }}
                                </a>
                            </p>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </main>
@endsection
