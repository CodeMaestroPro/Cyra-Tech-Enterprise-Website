@props([
    'registration' => [],
    'acknowledgement' => [],
])

@php
    $offices = config('cyra.contact.offices', []);
    $email = config('cyra.student_registrations.notification_email')
        ?: config('cyra.contact.notification_email', 'cyratech01@gmail.com');
    $website = config('cyra.website', 'https://cyratechltd.com');
    $logoUrl = asset(config('cyra.brand.logo', 'images/brand/cyra-tech-logo.png'));
    $logoAlt = config('cyra.brand.logo_alt', config('cyra.name'));
    $isPartial = ($registration['payment_status'] ?? '') === 'partial';
    $statusLabel = $registration['payment_status_label'] ?? 'Paid';
@endphp

<article {{ $attributes->merge(['id' => 'student-registration-receipt', 'class' => 'cyra-tp-receipt']) }}>
    <header class="cyra-tp-receipt-masthead">
        <div class="cyra-tp-receipt-brand">
            <img src="{{ $logoUrl }}" alt="{{ $logoAlt }}" class="cyra-tp-receipt-logo" width="160" height="48">
            <div>
                <p class="cyra-tp-receipt-org">{{ config('cyra.name') }}</p>
                <p class="cyra-tp-receipt-division">{{ $acknowledgement['subtitle'] ?? 'Training Academy' }}</p>
            </div>
        </div>
        <div class="cyra-tp-receipt-docmeta">
            <p class="cyra-tp-receipt-doctype">Official Receipt</p>
            <p class="cyra-tp-receipt-docno">{{ $registration['reference'] }}</p>
        </div>
    </header>

    <div class="cyra-tp-receipt-statusbar">
        <div>
            <p class="cyra-tp-receipt-kicker">Issued</p>
            <p class="cyra-tp-receipt-strong">{{ $registration['registered_date'] ?? now()->format('F j, Y') }}</p>
        </div>
        <div>
            <p class="cyra-tp-receipt-kicker">Payment channel</p>
            <p class="cyra-tp-receipt-strong">Paystack</p>
        </div>
        <div class="cyra-tp-receipt-statuswrap">
            <span class="cyra-tp-receipt-stamp {{ $isPartial ? 'is-partial' : 'is-paid' }}">{{ $statusLabel }}</span>
        </div>
    </div>

    <div class="cyra-tp-receipt-body">
        <section class="cyra-tp-receipt-party">
            <p class="cyra-tp-receipt-kicker">Received from</p>
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
            <p class="cyra-tp-receipt-kicker">Enrollment</p>
            <h3 class="cyra-tp-receipt-name">{{ $registration['program_label'] }}</h3>
            <p class="cyra-tp-receipt-muted">{{ $registration['payment_plan_label'] }} · {{ $registration['skill_level_label'] ?? '—' }}</p>
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
                <td>Student</td>
                <td>{{ $registration['name'] }}</td>
            </tr>
            <tr>
                <td>Education</td>
                <td>{{ $registration['education_level_label'] }}{{ ! empty($registration['field_of_study']) ? ' · '.$registration['field_of_study'] : '' }}</td>
            </tr>
            <tr>
                <td>Program fee</td>
                <td>{{ $registration['amount_formatted'] }}</td>
            </tr>
            <tr>
                <td>Amount paid</td>
                <td>{{ $registration['amount_paid_formatted'] }}</td>
            </tr>
            <tr>
                <td>Balance remaining</td>
                <td>{{ ($registration['amount_remaining'] ?? 0) > 0 ? $registration['amount_remaining_formatted'] : 'Nil' }}</td>
            </tr>
            <tr>
                <td>Paystack reference</td>
                <td class="cyra-tp-receipt-mono">{{ $registration['paystack_reference'] ?: '—' }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th>Total paid</th>
                <th>{{ $registration['amount_paid_formatted'] }}</th>
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
            <p class="cyra-tp-receipt-verify-title">{{ $acknowledgement['qr_caption'] ?? 'Scan to verify this receipt' }}</p>
            <p class="cyra-tp-receipt-muted">
                This QR code confirms the receipt was issued by {{ config('cyra.name') }}.
                Keep a printed or saved copy on your device — it is not sent on WhatsApp.
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
        <p class="cyra-tp-receipt-legal">This is an official computer-generated receipt. No signature is required.</p>
    </footer>
</article>
