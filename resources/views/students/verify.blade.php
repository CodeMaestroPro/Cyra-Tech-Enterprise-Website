@extends('layouts.training-portal')

@section('title', ($valid ? 'Verified receipt '.$registration['reference'] : 'Receipt not verified').' | Cyra-Tech')

@push('head')
    <meta name="robots" content="noindex, nofollow">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            #student-registration-receipt {
                box-shadow: none !important;
                border-color: #cbd5e1 !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="cyra-tp cyra-tp--verify">
        <header class="cyra-tp-header no-print">
            <div class="cyra-tp-header-brand">
                <x-brand.logo variant="icon" size="sm" />
                <div>
                    <p class="cyra-tp-header-kicker">Cyra-Tech Academy</p>
                    <p class="cyra-tp-header-title">Receipt Verification</p>
                </div>
            </div>
            <div class="cyra-tp-header-actions">
                <a href="{{ route('students') }}" class="cyra-tp-exit-link">Training portal</a>
            </div>
        </header>

        <div class="cyra-tp-verify-shell">
            @if ($valid)
                <div class="cyra-tp-verify-banner no-print">
                    <span class="cyra-tp-success-badge">Authentic receipt</span>
                    <h1 class="cyra-tp-success-title">This Cyra-Tech receipt is verified</h1>
                    <p class="cyra-tp-success-copy">
                        Reference <strong>{{ $registration['reference'] }}</strong> matches an official paid training registration.
                    </p>
                    <div class="cyra-tp-success-actions">
                        <button type="button" class="cyra-tp-btn cyra-tp-btn--primary" onclick="window.print()">Print / save receipt</button>
                    </div>
                </div>
                <x-students.payment-receipt
                    :registration="$registration"
                    :acknowledgement="config('cyra.student_registrations.acknowledgement', [])"
                />
            @else
                <div class="cyra-tp-verify-banner cyra-tp-verify-banner--invalid">
                    <span class="cyra-tp-success-badge">Not verified</span>
                    <h1 class="cyra-tp-success-title">This receipt could not be verified</h1>
                    <p class="cyra-tp-success-copy">
                        The QR code or verification link is invalid, expired, or does not match an official Cyra-Tech payment receipt.
                    </p>
                    <a href="{{ route('students') }}" class="cyra-tp-btn cyra-tp-btn--primary mt-6 inline-flex">Go to training portal</a>
                </div>
            @endif
        </div>
    </div>
@endsection
