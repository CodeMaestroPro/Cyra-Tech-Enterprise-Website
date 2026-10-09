@extends('layouts.training-portal')

@section('title', $page['seo']['title'] ?? 'Cyra-Tech Training Portal')

@push('head')
    <meta name="description" content="{{ $page['seo']['description'] ?? '' }}">
    @if (! empty($page['seo']['keywords']))
        <meta name="keywords" content="{{ implode(', ', $page['seo']['keywords']) }}">
    @endif
    <meta property="og:title" content="{{ $page['seo']['title'] ?? 'Cyra-Tech Training Portal' }}">
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
            #student-registration-receipt {
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
        $payment = $page['payment'] ?? [];
        $steps = $form['steps'] ?? [];
        $hasReceipt = $registration && in_array($registration['payment_status'] ?? '', ['paid', 'partial'], true);
        $showPaymentAlert = $hasReceipt && session('success') && ! session('registration_lookup');
        $initialStep = 1;
        if ($errors->hasAny(['education_level', 'field_of_study'])) {
            $initialStep = 2;
        } elseif ($errors->hasAny(['program', 'payment_plan', 'skill_level', 'has_tech_experience', 'payment'])) {
            $initialStep = 3;
        }
        $selectedProgram = old('program');
        $selectedPaymentPlan = old('payment_plan', 'full');
        $selectedSkillLevel = old('skill_level');
        $selectedTechExperience = old('has_tech_experience');
        $paymentPlans = $payment['plans'] ?? [];
        $installmentInitialPercent = (int) ($payment['installment_initial_percent'] ?? 60);
        $installmentBalancePercent = (int) ($payment['installment_balance_percent'] ?? 40);
    @endphp

    <div class="cyra-tp" data-students-registration-page>
        <div class="cyra-tp-ambient" aria-hidden="true">
            <div class="cyra-tp-ambient-orb cyra-tp-ambient-orb--one"></div>
            <div class="cyra-tp-ambient-orb cyra-tp-ambient-orb--two"></div>
            <div class="cyra-tp-ambient-grid"></div>
        </div>

        <header class="cyra-tp-header no-print">
            <div class="cyra-tp-header-brand">
                <x-brand.logo variant="icon" size="sm" />
                <div>
                    <p class="cyra-tp-header-kicker">Cyra-Tech</p>
                    <p class="cyra-tp-header-title">Training Portal</p>
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
                    <h1 class="cyra-tp-headline">{{ $hero['title'] ?? 'Enroll in a Cyra-Tech training program' }}</h1>
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

                    @unless ($hasReceipt)
                        <x-students.balance-lookup class="mt-8" />
                    @endunless

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

                @if ($hasReceipt)
                    <div
                        class="cyra-tp-success"
                        @if ($showPaymentAlert)
                            data-students-success
                            data-success-title="Payment Successful"
                            data-success-text="{{ session('success') }}"
                        @endif
                        data-success-reference="{{ $registration['reference'] }}"
                        data-receipt-hint="{{ $acknowledgement['note'] ?? 'Print this receipt or save it as a PDF on your phone. Scan the QR code anytime to verify authenticity.' }}"
                    >
                        <div class="cyra-tp-success-hero no-print">
                            <span class="cyra-tp-success-badge">
                                {{ ($registration['payment_status'] ?? '') === 'partial' ? 'Installment received' : 'Payment confirmed' }}
                            </span>
                            <h2 class="cyra-tp-success-title">Welcome aboard</h2>
                            <p class="cyra-tp-success-copy">
                                @if (($registration['payment_status'] ?? '') === 'partial')
                                    Your initial {{ $installmentInitialPercent }}% payment is confirmed. You can complete the remaining {{ $installmentBalancePercent }}% balance anytime from this page.
                                @else
                                    Your enrollment is complete. Print or save the official receipt below on your phone — our team will follow up with onboarding details.
                                @endif
                            </p>
                            <div class="cyra-tp-success-meta">
                                <span class="cyra-tp-ref">{{ $registration['reference'] }}</span>
                                <span class="cyra-tp-paid">{{ $registration['amount_paid_formatted'] }} paid</span>
                                @if (($registration['amount_remaining'] ?? 0) > 0)
                                    <span class="cyra-tp-balance">{{ $registration['amount_remaining_formatted'] }} balance</span>
                                @endif
                            </div>
                            @if ($registration['can_pay_balance'] ?? false)
                                <form method="POST" action="{{ $registration['balance_payment_url'] }}" class="cyra-tp-balance-form">
                                    @csrf
                                    <button type="submit" class="cyra-tp-btn cyra-tp-btn--pay">
                                        Pay remaining {{ $registration['amount_remaining_formatted'] }}
                                    </button>
                                </form>
                                <p class="cyra-tp-success-copy mt-3">
                                    You can also return later to this portal, enter <strong>{{ $registration['reference'] }}</strong> and your email, then pay the balance.
                                </p>
                            @endif
                            <div class="cyra-tp-success-actions">
                                <button type="button" class="cyra-tp-btn cyra-tp-btn--primary" data-students-print-receipt>
                                    Print / save receipt
                                </button>
                                @if (! empty($registration['verification_url']))
                                    <a href="{{ $registration['verification_url'] }}" class="cyra-tp-btn cyra-tp-btn--ghost">
                                        Verify receipt
                                    </a>
                                @endif
                            </div>
                            <p class="cyra-tp-receipt-save-hint">On a phone, choose Save as PDF in the print dialog to keep this receipt.</p>
                        </div>

                        <x-students.payment-receipt
                            :registration="$registration"
                            :acknowledgement="$acknowledgement"
                        />
                    </div>
                @else
                    <x-students.balance-lookup class="mb-6 lg:hidden no-print" compact />

                    <div class="cyra-tp-panel no-print">
                        <div class="cyra-tp-panel-top">
                            <div>
                                <p class="cyra-tp-panel-eyebrow">{{ $form['eyebrow'] ?? 'Student Registration' }}</p>
                                <h2 class="cyra-tp-panel-title">{{ $form['title'] ?? 'Register and pay in 3 steps' }}</h2>
                                @if (! empty($form['description']))
                                    <p class="cyra-tp-panel-copy">{{ $form['description'] }}</p>
                                @endif
                            </div>
                            <p class="cyra-tp-step-pill" data-students-step-meta>Step 1 of {{ count($steps) }}</p>
                        </div>

                        <div class="cyra-tp-progress" aria-hidden="true">
                            <div class="cyra-tp-progress-fill" data-students-progress style="width: 33.33%"></div>
                        </div>

                        @if ($errors->any())
                            <div class="cyra-tp-alert cyra-tp-alert--error" role="alert">{{ $errors->first() }}</div>
                        @endif

                        @unless ($paystackConfigured)
                            <div class="cyra-tp-alert cyra-tp-alert--info" role="alert">
                                Online payment is not configured yet. Please contact support to complete your registration.
                            </div>
                        @endunless

                        <form
                            method="POST"
                            action="{{ route('students.store') }}"
                            class="cyra-tp-form"
                            id="student-registration-form"
                            data-students-wizard
                            data-initial-step="{{ $initialStep }}"
                            data-total-steps="{{ count($steps) }}"
                            data-programs='@json($programs)'
                            data-installment-initial="{{ $installmentInitialPercent }}"
                            data-installment-balance="{{ $installmentBalancePercent }}"
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
                                        <x-ui.input name="phone" type="tel" label="Phone / WhatsApp number" placeholder="+234 800 000 0000" autocomplete="tel" required />
                                        <x-ui.input name="date_of_birth" type="date" label="Date of birth" required />
                                        <x-ui.select name="gender" label="Gender" :options="$genderOptions" placeholder="Select gender" required />
                                        <x-ui.select name="state" label="State" :options="$stateOptions" placeholder="Select state" required />
                                        <x-ui.input name="city" label="City" placeholder="Your city" required />
                                    </div>
                                </div>

                                <div class="cyra-tp-form-panel hidden" data-step-panel="2">
                                    <h3 class="cyra-tp-form-title">{{ $steps[1]['title'] ?? 'Education' }}</h3>
                                    @if (! empty($steps[1]['description']))
                                        <p class="cyra-tp-form-description">{{ $steps[1]['description'] }}</p>
                                    @endif
                                    <div class="cyra-tp-field-grid">
                                        <x-ui.select name="education_level" label="Highest level of education" :options="$educationLevelOptions" placeholder="Select education level" required />
                                        <x-ui.input name="field_of_study" label="Field of study" placeholder="e.g. Computer Science" required />
                                    </div>
                                </div>

                                <div class="cyra-tp-form-panel hidden" data-step-panel="3">
                                    <h3 class="cyra-tp-form-title">{{ $steps[2]['title'] ?? 'Choose your program' }}</h3>
                                    @if (! empty($steps[2]['description']))
                                        <p class="cyra-tp-form-description">{{ $steps[2]['description'] }}</p>
                                    @endif

                                    <div class="cyra-tp-program-list" data-students-program-list>
                                        @foreach ($programs as $program)
                                            <x-students.program-option
                                                :slug="$program['slug']"
                                                :label="$program['label']"
                                                :description="$program['description']"
                                                :selected="$selectedProgram === $program['slug']"
                                            />
                                        @endforeach
                                    </div>

                                    <div class="cyra-tp-checkout" data-students-summary hidden>
                                        <p class="cyra-tp-checkout-label">Selected program fee</p>
                                        <p class="cyra-tp-checkout-program" data-students-summary-program>—</p>
                                        <p class="cyra-tp-checkout-fee" data-students-summary-fee>—</p>

                                        <div class="cyra-tp-plan-list" data-students-plan-list hidden>
                                            <p class="cyra-tp-plan-list-label">Choose payment option</p>
                                            @foreach ($paymentPlans as $planKey => $plan)
                                                <x-students.payment-plan-option
                                                    :value="$planKey"
                                                    :label="$plan['label']"
                                                    :description="$plan['description']"
                                                    :selected="$selectedPaymentPlan === $planKey"
                                                />
                                            @endforeach
                                        </div>

                                        <div class="cyra-tp-checkout-due" data-students-due-wrap hidden>
                                            <p class="cyra-tp-checkout-due-label">Amount due today</p>
                                            <p class="cyra-tp-checkout-amount" data-students-summary-amount>—</p>
                                            <p class="cyra-tp-checkout-balance" data-students-summary-balance hidden></p>
                                        </div>

                                        <p class="cyra-tp-checkout-note">{{ $payment['secure_note'] ?? 'Payments are processed securely by Paystack.' }}</p>
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
                                <button type="button" class="cyra-tp-btn cyra-tp-btn--ghost hidden" data-students-prev>Back</button>
                                <div class="cyra-tp-form-actions-primary">
                                    <button type="button" class="cyra-tp-btn cyra-tp-btn--primary" data-students-next>Next</button>
                                    <button type="submit" class="cyra-tp-btn cyra-tp-btn--pay hidden" data-students-submit @disabled(! $paystackConfigured)>
                                        {{ $form['submit_label'] ?? 'Proceed to Paystack' }}
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
