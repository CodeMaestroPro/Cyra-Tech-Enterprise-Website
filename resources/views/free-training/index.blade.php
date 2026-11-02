@extends('layouts.training-portal')

@section('title', $page['seo']['title'] ?? 'Cyra-Tech Free Training')

@push('head')
    <meta name="description" content="{{ $page['seo']['description'] ?? '' }}">
    @if (! empty($page['seo']['keywords']))
        <meta name="keywords" content="{{ implode(', ', $page['seo']['keywords']) }}">
    @endif
    <meta property="og:title" content="{{ $page['seo']['title'] ?? 'Cyra-Tech Free Training' }}">
    <meta property="og:description" content="{{ $page['seo']['description'] ?? '' }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @media print {
            .no-print,
            .cyra-tp-ambient,
            .cyra-tp-header,
            .cyra-tp-showcase,
            .cyra-tp-success-hero,
            .cyra-tp-alert {
                display: none !important;
            }
            body,
            .theme-training-portal,
            .cyra-tp,
            .cyra-tp-shell,
            .cyra-tp-main,
            .cyra-tp-success {
                background: #fff !important;
                color: #0f172a !important;
                display: block !important;
                width: 100% !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
            }
            #free-training-confirmation {
                box-shadow: none !important;
                border-color: #cbd5e1 !important;
                max-width: 100% !important;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $hero = $page['hero'] ?? [];
        $form = $page['form'] ?? [];
        $highlights = $page['highlights'] ?? [];
        $acknowledgement = $page['acknowledgement'] ?? [];
        $steps = $form['steps'] ?? [];
        $hasConfirmation = filled($registration['reference'] ?? null);
        $showSuccessAlert = $hasConfirmation && session('success');
        $initialStep = 1;
        if ($errors->hasAny(['program', 'skill_level', 'has_tech_experience'])) {
            $initialStep = 2;
        }
        $selectedProgram = old('program');
        $selectedSkillLevel = old('skill_level');
        $selectedTechExperience = old('has_tech_experience');
    @endphp

    <div class="cyra-tp" data-free-training-page>
        <div class="cyra-tp-ambient" aria-hidden="true">
            <div class="cyra-tp-ambient-orb cyra-tp-ambient-orb--one"></div>
            <div class="cyra-tp-ambient-orb cyra-tp-ambient-orb--two"></div>
            <div class="cyra-tp-ambient-grid"></div>
        </div>

        <header class="cyra-tp-header no-print">
            <div class="cyra-tp-header-brand">
                <x-brand.logo variant="icon" size="sm" />
                <div>
                    <p class="cyra-tp-header-kicker">Cyra-Tech Academy</p>
                    <p class="cyra-tp-header-title">Free Training</p>
                </div>
            </div>
            <div class="cyra-tp-header-actions">
                <a href="{{ route('home') }}" class="cyra-tp-exit-link">Exit portal</a>
            </div>
        </header>

        <div class="cyra-tp-shell">
            <aside class="cyra-tp-showcase no-print">
                <div class="cyra-tp-showcase-inner">
                    @if (! empty($hero['eyebrow']))
                        <p class="cyra-tp-eyebrow">{{ $hero['eyebrow'] }}</p>
                    @endif
                    <h1 class="cyra-tp-headline">{{ $hero['title'] ?? 'Join a free Cyra-Tech training course' }}</h1>
                    @if (! empty($hero['description']))
                        <p class="cyra-tp-lead">{{ $hero['description'] }}</p>
                    @endif

                    <div class="cyra-tp-highlights">
                        @foreach ($highlights as $highlight)
                            <article class="cyra-tp-highlight">
                                <x-it.highlight-icon :icon="$highlight['icon'] ?? 'check'" class="cyra-tp-highlight-icon" />
                                <div>
                                    <h3 class="cyra-tp-highlight-title">{{ $highlight['title'] ?? '' }}</h3>
                                    <p class="cyra-tp-highlight-text">{{ $highlight['description'] ?? '' }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <p class="cyra-tp-support">
                        Need help?
                        <a href="mailto:{{ config('cyra.contact.notification_email', 'cyratech01@gmail.com') }}">
                            {{ config('cyra.contact.notification_email', 'cyratech01@gmail.com') }}
                        </a>
                    </p>
                </div>
            </aside>

            <main id="main-content" class="cyra-tp-main">
                @if (session('error'))
                    <div class="cyra-tp-alert cyra-tp-alert--error no-print" role="alert">{{ session('error') }}</div>
                @endif

                @if ($hasConfirmation)
                    <div
                        class="cyra-tp-success"
                        @if ($showSuccessAlert)
                            data-free-training-success
                            data-success-title="You're enrolled"
                            data-success-text="{{ session('success') }}"
                        @endif
                        data-success-reference="{{ $registration['reference'] }}"
                        data-receipt-hint="{{ $acknowledgement['note'] ?? 'Print this confirmation or save it as a PDF on your phone.' }}"
                    >
                        <div class="cyra-tp-success-hero no-print">
                            <span class="cyra-tp-success-badge">Free training confirmed</span>
                            <h2 class="cyra-tp-success-title">Welcome aboard</h2>
                            <p class="cyra-tp-success-copy">
                                Your place in <strong>{{ $registration['program_label'] }}</strong> is reserved at no cost. Print or save your confirmation below — our team will follow up with onboarding details.
                            </p>
                            <div class="cyra-tp-success-meta">
                                <span class="cyra-tp-ref">{{ $registration['reference'] }}</span>
                                <span class="cyra-tp-paid">Free</span>
                            </div>
                            <div class="cyra-tp-success-actions">
                                <button type="button" class="cyra-tp-btn cyra-tp-btn--primary" data-free-training-print>
                                    Print / save confirmation
                                </button>
                                @if (! empty($registration['verification_url']))
                                    <a href="{{ $registration['verification_url'] }}" class="cyra-tp-btn cyra-tp-btn--ghost">
                                        Verify enrollment
                                    </a>
                                @endif
                            </div>
                            <p class="cyra-tp-receipt-save-hint">On a phone, choose Save as PDF in the print dialog to keep this confirmation.</p>
                        </div>

                        <x-free-training.enrollment-slip
                            :registration="$registration"
                            :acknowledgement="$acknowledgement"
                        />
                    </div>
                @else
                    <div class="cyra-tp-panel no-print">
                        <div class="cyra-tp-panel-top">
                            <div>
                                <p class="cyra-tp-panel-eyebrow">{{ $form['eyebrow'] ?? 'Free Training' }}</p>
                                <h2 class="cyra-tp-panel-title">{{ $form['title'] ?? 'Onboard in 2 steps' }}</h2>
                                @if (! empty($form['description']))
                                    <p class="cyra-tp-panel-copy">{{ $form['description'] }}</p>
                                @endif
                            </div>
                            <p class="cyra-tp-step-pill" data-free-training-step-meta>Step 1 of {{ count($steps) }}</p>
                        </div>

                        <div class="cyra-tp-progress" aria-hidden="true">
                            <div class="cyra-tp-progress-fill" data-free-training-progress style="width: 50%"></div>
                        </div>

                        @if ($errors->any())
                            <div class="cyra-tp-alert cyra-tp-alert--error" role="alert">{{ $errors->first() }}</div>
                        @endif

                        <form
                            method="POST"
                            action="{{ route('free-training.store') }}"
                            class="cyra-tp-form"
                            id="free-training-form"
                            data-free-training-wizard
                            data-initial-step="{{ $initialStep }}"
                            data-total-steps="{{ count($steps) }}"
                            novalidate
                        >
                            @csrf

                            <ol class="cyra-tp-steps" aria-label="Registration steps">
                                @foreach ($steps as $index => $step)
                                    <li class="cyra-tp-step" data-step-indicator="{{ $index + 1 }}">
                                        <span class="cyra-tp-step-index">{{ $index + 1 }}</span>
                                        <span class="cyra-tp-step-label">{{ $step['label'] }}</span>
                                    </li>
                                @endforeach
                            </ol>

                            <div class="cyra-tp-form-body">
                                <div class="cyra-tp-form-panel is-active" data-step-panel="1">
                                    <h3 class="cyra-tp-form-title">{{ $steps[0]['title'] ?? 'Personal details' }}</h3>
                                    @if (! empty($steps[0]['description']))
                                        <p class="cyra-tp-form-description">{{ $steps[0]['description'] }}</p>
                                    @endif
                                    <div class="cyra-tp-field-grid">
                                        <x-ui.input name="name" label="Full name" placeholder="Your full name" autocomplete="name" required />
                                        <x-ui.input name="email" type="email" label="Email address" placeholder="you@example.com" autocomplete="email" required />
                                        <x-ui.input name="phone" type="tel" label="Phone number" placeholder="+234 800 000 0000" autocomplete="tel" required />
                                        <x-ui.input name="date_of_birth" type="date" label="Date of birth" required />
                                        <x-ui.select name="gender" label="Gender" :options="$genderOptions" placeholder="Select gender" required />
                                        <x-ui.select name="state" label="State" :options="$stateOptions" placeholder="Select state" required />
                                        <x-ui.input name="city" label="City" placeholder="Your city" required />
                                    </div>
                                </div>

                                <div class="cyra-tp-form-panel hidden" data-step-panel="2">
                                    <h3 class="cyra-tp-form-title">{{ $steps[1]['title'] ?? 'Choose your course' }}</h3>
                                    @if (! empty($steps[1]['description']))
                                        <p class="cyra-tp-form-description">{{ $steps[1]['description'] }}</p>
                                    @endif

                                    <div class="cyra-tp-program-list" data-free-training-course-list>
                                        @foreach ($programs as $program)
                                            <x-students.program-option
                                                :slug="$program['slug']"
                                                :label="$program['label']"
                                                :description="$program['description']"
                                                :selected="$selectedProgram === $program['slug']"
                                                badge="Free"
                                            />
                                        @endforeach
                                    </div>

                                    <fieldset class="cyra-tp-choice-group mt-6">
                                        <legend class="cyra-tp-choice-legend">Current tech skill level</legend>
                                        <div class="cyra-tp-choice-list">
                                            @foreach ($skillLevelOptions as $value => $label)
                                                <label class="cyra-tp-choice {{ $selectedSkillLevel === $value ? 'is-selected' : '' }}">
                                                    <input type="radio" name="skill_level" value="{{ $value }}" class="sr-only" @checked($selectedSkillLevel === $value) required>
                                                    <span>{{ $label }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </fieldset>

                                    <fieldset class="cyra-tp-choice-group mt-5">
                                        <legend class="cyra-tp-choice-legend">Do you have any previous experience in technology?</legend>
                                        <div class="cyra-tp-choice-list">
                                            <label class="cyra-tp-choice {{ $selectedTechExperience === 'yes' ? 'is-selected' : '' }}">
                                                <input type="radio" name="has_tech_experience" value="yes" class="sr-only" @checked($selectedTechExperience === 'yes') required>
                                                <span>Yes</span>
                                            </label>
                                            <label class="cyra-tp-choice {{ $selectedTechExperience === 'no' ? 'is-selected' : '' }}">
                                                <input type="radio" name="has_tech_experience" value="no" class="sr-only" @checked($selectedTechExperience === 'no') required>
                                                <span>No</span>
                                            </label>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="cyra-tp-form-actions">
                                <button type="button" class="cyra-tp-btn cyra-tp-btn--ghost hidden" data-free-training-prev>Back</button>
                                <div class="cyra-tp-form-actions-primary">
                                    <button type="button" class="cyra-tp-btn cyra-tp-btn--primary" data-free-training-next>Next</button>
                                    <button type="submit" class="cyra-tp-btn cyra-tp-btn--pay hidden" data-free-training-submit>
                                        {{ $form['submit_label'] ?? 'Complete free enrollment' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </main>
        </div>
    </div>
@endsection
