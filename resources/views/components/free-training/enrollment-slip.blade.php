@props([
    'registration' => [],
    'acknowledgement' => [],
])

@php
    $offices = config('cyra.contact.offices', []);
    $email = config('cyra.free_training.notification_email')
        ?: config('cyra.contact.notification_email', 'cyratech01@gmail.com');
    $website = config('cyra.website', 'https://cyratechltd.com');
    $logoUrl = asset(config('cyra.brand.logo', 'images/brand/cyra-tech-logo.png'));
    $logoAlt = config('cyra.brand.logo_alt', config('cyra.name'));
@endphp

<article {{ $attributes->merge(['id' => 'free-training-confirmation', 'class' => 'cyra-tp-receipt']) }}>
    <header class="cyra-tp-receipt-masthead">
        <div class="cyra-tp-receipt-brand">
            <img src="{{ $logoUrl }}" alt="{{ $logoAlt }}" class="cyra-tp-receipt-logo" width="160" height="48">
            <div>
                <p class="cyra-tp-receipt-org">{{ config('cyra.name') }}</p>
                <p class="cyra-tp-receipt-division">{{ $acknowledgement['subtitle'] ?? 'Free Training Academy' }}</p>
            </div>
        </div>
        <div class="cyra-tp-receipt-docmeta">
            <p class="cyra-tp-receipt-doctype">Enrollment Confirmation</p>
            <p class="cyra-tp-receipt-docno">{{ $registration['reference'] }}</p>
        </div>
    </header>

    <div class="cyra-tp-receipt-statusbar">
        <div>
            <p class="cyra-tp-receipt-kicker">Registered</p>
            <p class="cyra-tp-receipt-strong">{{ $registration['registered_date'] ?? now()->format('F j, Y') }}</p>
        </div>
        <div>
            <p class="cyra-tp-receipt-kicker">Program type</p>
            <p class="cyra-tp-receipt-strong">Free training</p>
        </div>
        <div class="cyra-tp-receipt-statuswrap">
            <span class="cyra-tp-receipt-stamp is-paid">Enrolled</span>
        </div>
    </div>

    <div class="cyra-tp-receipt-body">
        <section class="cyra-tp-receipt-party">
            <p class="cyra-tp-receipt-kicker">Learner</p>
            <h3 class="cyra-tp-receipt-name">{{ $registration['name'] }}</h3>
            <ul class="cyra-tp-receipt-contact">
                <li>{{ $registration['email'] }}</li>
                <li>{{ $registration['phone'] }}</li>
                @if (! empty($registration['location']))
                    <li>{{ $registration['location'] }}</li>
                @endif
            </ul>
        </section>

        <section class="cyra-tp-receipt-program">
            <p class="cyra-tp-receipt-kicker">Course</p>
            <h3 class="cyra-tp-receipt-name">{{ $registration['program_label'] }}</h3>
            <p class="cyra-tp-receipt-muted">{{ $registration['skill_level_label'] ?? '—' }} · No tuition fee</p>
        </section>
    </div>

    <table class="cyra-tp-receipt-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Learner</td>
                <td>{{ $registration['name'] }}</td>
            </tr>
            <tr>
                <td>Skill level</td>
                <td>{{ $registration['skill_level_label'] ?: '—' }}</td>
            </tr>
            <tr>
                <td>Previous tech experience</td>
                <td>{{ $registration['has_tech_experience_label'] }}</td>
            </tr>
            <tr>
                <td>Course</td>
                <td>{{ $registration['program_label'] }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th>Tuition</th>
                <th>Free</th>
            </tr>
        </tfoot>
    </table>

    <div class="cyra-tp-receipt-verify">
        <div class="cyra-tp-receipt-qr" aria-hidden="{{ empty($registration['verification_qr']) ? 'true' : 'false' }}">
            @if (! empty($registration['verification_qr']))
                {!! $registration['verification_qr'] !!}
            @endif
        </div>
        <div>
            <p class="cyra-tp-receipt-kicker">Verification</p>
            <p class="cyra-tp-receipt-verify-title">{{ $acknowledgement['qr_caption'] ?? 'Scan to verify this enrollment' }}</p>
            <p class="cyra-tp-receipt-muted">
                This QR code confirms the enrollment was issued by {{ config('cyra.name') }}.
                Print or save a copy on your device.
            </p>
            @if (! empty($registration['verification_url']))
                <p class="cyra-tp-receipt-verify-url">{{ $registration['verification_url'] }}</p>
            @endif
        </div>
    </div>

    <footer class="cyra-tp-receipt-footer">
        <p>{{ $acknowledgement['company_line'] ?? config('cyra.tagline') }}</p>
        <p>
            {{ $email }}
            @if ($website)
                · {{ parse_url($website, PHP_URL_HOST) ?: $website }}
            @endif
        </p>
        @if ($offices !== [])
            <p>
                @foreach ($offices as $index => $office)
                    {{ $office['city'] ?? '' }}{{ ! empty($office['address']) ? ' — '.$office['address'] : '' }}{{ $index < count($offices) - 1 ? ' · ' : '' }}
                @endforeach
            </p>
        @endif
        <p class="cyra-tp-receipt-legal">This is an official computer-generated enrollment confirmation. No signature is required.</p>
    </footer>
</article>
