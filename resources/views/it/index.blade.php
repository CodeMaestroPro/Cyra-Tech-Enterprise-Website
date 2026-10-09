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
        $steps = $form['steps'] ?? [];
        $hasSuccess = session('success') && $registration;
        $initialStep = 1;
        if ($errors->hasAny(['institution', 'course_of_study', 'academic_level'])) {
            $initialStep = 2;
        } elseif ($errors->hasAny(['interest_area', 'availability', 'motivation'])) {
            $initialStep = 3;
        }
        $whatsappLinks = $registration['whatsapp_links'] ?? [];
    @endphp

    <main id="main-content" class="cyra-it-page" data-it-registration-page>
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

        <section class="cyra-section cyra-it-section">
            <div class="cyra-container">
                @if ($hasSuccess)
                    <div
                        id="registration-success"
                        class="cyra-it-success mb-10"
                        data-it-success
                        data-success-title="Registration Successful"
                        data-success-text="{{ session('success') }}"
                        data-success-reference="{{ $registration['reference'] }}"
                        data-whatsapp-hint="{{ $whatsapp['hint'] ?? 'WhatsApp will open with your acknowledgement already filled in. Just tap Send.' }}"
                        @if (! empty($whatsappLinks))
                            data-whatsapp-links='@json($whatsappLinks)'
                        @endif
                    >
                        <div class="cyra-it-success-banner no-print">
                            <div class="cyra-it-success-banner-glow" aria-hidden="true"></div>
                            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                                <div class="max-w-2xl">
                                    <span class="cyra-it-success-pill">Registration complete</span>
                                    <h2 class="mt-4 text-2xl font-bold tracking-tight text-cyra-text sm:text-3xl">You're in — one last step</h2>
                                    <p class="mt-3 text-sm leading-relaxed text-cyra-muted sm:text-base">
                                        Your acknowledgement slip is ready. Open WhatsApp and your details will already be filled in — just tap <strong class="text-cyra-text">Send</strong>.
                                    </p>
                                    <p class="mt-4 inline-flex rounded-xl border border-cyra-primary/20 bg-cyra-primary/10 px-4 py-2 font-mono text-sm text-cyra-accent">
                                        {{ $registration['reference'] }}
                                    </p>
                                </div>

                                <div class="flex w-full flex-col gap-3 lg:max-w-sm">
                                    @forelse ($whatsappLinks as $link)
                                        <x-it.whatsapp-button :url="$link['url']" :label="$link['label']" class="w-full" />
                                    @empty
                                        <a
                                            href="mailto:{{ config('cyra.contact.notification_email', 'cyratech01@gmail.com') }}?subject={{ rawurlencode('IT Student Acknowledgement - '.$registration['reference']) }}&body={{ rawurlencode($registration['whatsapp_message']) }}"
                                            class="cyra-it-whatsapp-btn cyra-it-whatsapp-btn--primary w-full"
                                        >
                                            Email acknowledgement
                                        </a>
                                    @endforelse
                                    <button type="button" class="cyra-it-btn-ghost w-full" onclick="window.print()">
                                        Print acknowledgement slip
                                    </button>
                                </div>
                            </div>
                        </div>

                        <article id="it-student-acknowledgement-slip" class="cyra-it-slip">
                            <div class="cyra-it-slip-header">
                                <div>
                                    <p class="cyra-it-slip-eyebrow">{{ $acknowledgement['subtitle'] ?? 'Cyra-Tech IT Student Registration' }}</p>
                                    <h2 class="cyra-it-slip-title">{{ $acknowledgement['title'] ?? 'Acknowledgement Slip' }}</h2>
                                    <p class="cyra-it-slip-subtitle">{{ $acknowledgement['company_line'] ?? config('cyra.name') }}</p>
                                </div>
                                <div class="cyra-it-slip-ref">
                                    <p class="cyra-it-slip-ref-label">Reference</p>
                                    <p class="cyra-it-slip-ref-value">{{ $registration['reference'] }}</p>
                                </div>
                            </div>

                            <div class="cyra-it-slip-grid">
                                @foreach ([
                                    'Student Name' => $registration['name'],
                                    'Registration Date' => $registration['registered_date'],
                                    'Email' => $registration['email'],
                                    'Phone' => $registration['phone'],
                                    'Institution' => $registration['institution'],
                                    'Course of Study' => $registration['course_of_study'],
                                    'Academic Level' => $registration['academic_level_label'],
                                    'Interest Area' => $registration['interest_area_label'],
                                    'Availability' => $registration['availability_label'],
                                ] as $label => $value)
                                    <div class="cyra-it-slip-field">
                                        <p class="cyra-it-slip-field-label">{{ $label }}</p>
                                        <p class="cyra-it-slip-field-value">{{ $value }}</p>
                                    </div>
                                @endforeach
                                <div class="cyra-it-slip-field">
                                    <p class="cyra-it-slip-field-label">Status</p>
                                    <span class="cyra-it-slip-status">Registered</span>
                                </div>
                            </div>
                        </article>

                        <div class="cyra-it-whatsapp-card no-print">
                            <div class="cyra-it-whatsapp-card-icon" aria-hidden="true">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20.52 3.48A11.86 11.86 0 0012.04 0C5.5 0 .2 5.3.2 11.84c0 2.09.55 4.12 1.6 5.92L0 24l6.4-1.67a11.8 11.8 0 005.64 1.44h.01c6.54 0 11.84-5.3 11.84-11.84 0-3.16-1.23-6.13-3.37-8.45zM12.05 21.5h-.01a9.64 9.64 0 01-4.91-1.35l-.35-.21-3.8.99 1.02-3.7-.23-.38a9.64 9.64 0 01-1.48-5.15c0-5.33 4.34-9.66 9.68-9.66 2.58 0 5.01 1.01 6.84 2.84a9.6 9.6 0 012.83 6.83c0 5.33-4.34 9.66-9.67 9.66zm5.3-7.23c-.29-.15-1.72-.85-1.99-.94-.27-.1-.46-.15-.66.15-.19.29-.76.94-.93 1.13-.17.2-.34.22-.63.07-.29-.15-1.23-.45-2.34-1.44-.86-.77-1.45-1.72-1.62-2.01-.17-.29-.02-.45.13-.6.13-.13.29-.34.43-.51.15-.17.19-.29.29-.49.1-.2.05-.37-.02-.52-.07-.15-.66-1.59-.9-2.18-.24-.58-.48-.5-.66-.51h-.56c-.19 0-.5.07-.76.37-.26.29-1 1-1 2.43s1.02 2.82 1.16 3.01c.15.19 2.01 3.07 4.87 4.31.68.29 1.21.47 1.62.6.68.22 1.3.19 1.79.12.55-.08 1.72-.7 1.96-1.38.24-.68.24-1.26.17-1.38-.07-.12-.26-.19-.55-.34z"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-base font-semibold text-cyra-text">Missed the popup?</p>
                                <p class="mt-1 text-sm leading-relaxed text-cyra-muted">
                                    Send your acknowledgement on WhatsApp anytime from here.
                                </p>
                            </div>
                            <div class="flex w-full flex-col gap-3 sm:w-auto sm:min-w-[14rem]">
                                @forelse ($whatsappLinks as $link)
                                    <x-it.whatsapp-button :url="$link['url']" :label="$link['label']" class="w-full" />
                                @empty
                                    <p class="text-sm text-cyra-muted">WhatsApp is not configured yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_20rem] xl:gap-10">
                    <div class="min-w-0">
                        @unless ($hasSuccess)
                            <div class="cyra-it-wizard-shell">
                                <div class="cyra-it-wizard-header">
                                    <div>
                                        <p class="cyra-it-wizard-eyebrow">{{ $form['eyebrow'] ?? 'Student Registration' }}</p>
                                        <h2 class="cyra-it-wizard-heading">{{ $form['title'] ?? 'Register in 3 easy steps' }}</h2>
                                        @if (! empty($form['description']))
                                            <p class="cyra-it-wizard-lead">{{ $form['description'] }}</p>
                                        @endif
                                    </div>
                                    <p class="cyra-it-wizard-meta" data-it-step-meta>Step 1 of {{ count($steps) }}</p>
                                </div>

                                <div class="cyra-it-wizard-progress" aria-hidden="true">
                                    <div class="cyra-it-wizard-progress-fill" data-it-progress style="width: 33.33%"></div>
                                </div>

                                @if ($errors->any())
                                    <x-ui.alert variant="error" class="mx-5 mt-5 sm:mx-8">
                                        {{ $errors->first() }}
                                    </x-ui.alert>
                                @endif

                                <form
                                    method="POST"
                                    action="{{ route('it.store') }}"
                                    class="cyra-it-wizard"
                                    id="it-student-registration-form"
                                    data-it-wizard
                                    data-initial-step="{{ $initialStep }}"
                                    data-total-steps="{{ count($steps) }}"
                                    novalidate
                                >
                                    @csrf

                                    <ol class="cyra-it-wizard-steps" aria-label="Registration steps">
                                        @foreach ($steps as $index => $step)
                                            <li class="cyra-it-wizard-step" data-step-indicator="{{ $index + 1 }}">
                                                <span class="cyra-it-wizard-step-index">{{ $index + 1 }}</span>
                                                <span class="cyra-it-wizard-step-label">{{ $step['label'] }}</span>
                                            </li>
                                        @endforeach
                                    </ol>

                                    <div class="cyra-it-wizard-body">
                                        <div class="cyra-it-wizard-panel is-active" data-step-panel="1">
                                            <h3 class="cyra-it-wizard-title">{{ $steps[0]['title'] ?? 'Personal details' }}</h3>
                                            @if (! empty($steps[0]['description']))
                                                <p class="cyra-it-wizard-description">{{ $steps[0]['description'] }}</p>
                                            @endif
                                            <div class="cyra-it-field-grid">
                                                <x-ui.input name="name" label="Full name" placeholder="Your full name" autocomplete="name" required />
                                                <x-ui.input name="email" type="email" label="Email address" placeholder="you@example.com" autocomplete="email" required />
                                                <x-ui.input name="phone" type="tel" label="Phone / WhatsApp number" placeholder="+234 800 000 0000" autocomplete="tel" class="sm:col-span-2" required />
                                            </div>
                                        </div>

                                        <div class="cyra-it-wizard-panel hidden" data-step-panel="2">
                                            <h3 class="cyra-it-wizard-title">{{ $steps[1]['title'] ?? 'Education details' }}</h3>
                                            @if (! empty($steps[1]['description']))
                                                <p class="cyra-it-wizard-description">{{ $steps[1]['description'] }}</p>
                                            @endif
                                            <div class="cyra-it-field-grid">
                                                <x-ui.input name="institution" label="School / Institution" placeholder="University or polytechnic name" required />
                                                <x-ui.input name="course_of_study" label="Course of study" placeholder="e.g. Computer Science" required />
                                                <x-ui.select name="academic_level" label="Academic level" :options="$academicLevelOptions" placeholder="Select level" class="sm:col-span-2" required />
                                            </div>
                                        </div>

                                        <div class="cyra-it-wizard-panel hidden" data-step-panel="3">
                                            <h3 class="cyra-it-wizard-title">{{ $steps[2]['title'] ?? 'Interest & availability' }}</h3>
                                            @if (! empty($steps[2]['description']))
                                                <p class="cyra-it-wizard-description">{{ $steps[2]['description'] }}</p>
                                            @endif
                                            <div class="cyra-it-field-grid">
                                                <x-ui.select name="interest_area" label="Area of interest" :options="$interestAreaOptions" placeholder="Select interest" required />
                                                <x-ui.select name="availability" label="Availability" :options="$availabilityOptions" placeholder="Select availability" required />
                                                <div class="sm:col-span-2">
                                                    <x-ui.textarea name="motivation" label="Why do you want to join Cyra-Tech?" placeholder="Share your goals, experience level, and what you hope to learn..." rows="5" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="cyra-it-wizard-actions">
                                        <button type="button" class="cyra-it-wizard-btn cyra-it-wizard-btn-secondary hidden" data-it-prev>
                                            Back
                                        </button>
                                        <div class="cyra-it-wizard-actions-primary">
                                            <button type="button" class="cyra-it-wizard-btn cyra-it-wizard-btn-primary" data-it-next>
                                                Next
                                            </button>
                                            <button type="submit" class="cyra-it-wizard-btn cyra-it-wizard-btn-primary hidden" data-it-submit>
                                                {{ $form['submit_label'] ?? 'Complete Registration' }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="cyra-it-wizard-shell cyra-it-wizard-shell--done">
                                <div class="cyra-it-done-card">
                                    <span class="cyra-it-success-pill">All set</span>
                                    <h2 class="mt-4 text-xl font-bold text-cyra-text">Registration submitted</h2>
                                    <p class="mt-2 text-sm leading-relaxed text-cyra-muted">
                                        Your acknowledgement slip and WhatsApp actions are shown above. Keep your reference handy for follow-up.
                                    </p>
                                </div>
                            </div>
                        @endunless
                    </div>

                    <aside class="cyra-it-sidebar space-y-4">
                        <div class="cyra-it-sidebar-intro">
                            <p class="cyra-it-sidebar-eyebrow">Why join Cyra-Tech</p>
                            <h2 class="cyra-it-sidebar-title">Build skills that matter</h2>
                        </div>

                        @foreach ($highlights as $highlight)
                            <article class="cyra-it-sidebar-card">
                                <x-it.highlight-icon :icon="$highlight['icon'] ?? 'check'" />
                                <div>
                                    <h3 class="cyra-it-sidebar-card-title">{{ $highlight['title'] ?? '' }}</h3>
                                    <p class="cyra-it-sidebar-card-text">{{ $highlight['description'] ?? '' }}</p>
                                </div>
                            </article>
                        @endforeach

                        <div class="cyra-it-help-card">
                            <p class="cyra-it-help-label">Need help?</p>
                            <p class="cyra-it-help-text">
                                Questions about the IT intake? Email
                                <a href="mailto:{{ config('cyra.contact.notification_email', 'cyratech01@gmail.com') }}" class="cyra-it-help-link">
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
