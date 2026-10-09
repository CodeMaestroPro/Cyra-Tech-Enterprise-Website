@props([
    'compact' => false,
])

<div {{ $attributes->merge(['class' => $compact ? 'cyra-tp-lookup cyra-tp-lookup--compact' : 'cyra-tp-lookup']) }}>
    <p class="cyra-tp-lookup-kicker">Already enrolled?</p>
    <h2 class="cyra-tp-lookup-title">Pay remaining balance</h2>
    <p class="cyra-tp-lookup-copy">
        Enter your registration reference and email to continue a 60/40 installment payment.
    </p>

    @if ($errors->lookup->any())
        <p class="cyra-tp-lookup-error" role="alert">{{ $errors->lookup->first() }}</p>
    @endif

    <form method="POST" action="{{ route('students.lookup') }}" class="cyra-tp-lookup-form">
        @csrf
        <label class="cyra-tp-lookup-field">
            <span>Reference</span>
            <input
                type="text"
                name="lookup_reference"
                value="{{ old('lookup_reference') }}"
                placeholder="STR-CT-001"
                autocomplete="off"
                required
            >
        </label>
        <label class="cyra-tp-lookup-field">
            <span>Email address</span>
            <input
                type="email"
                name="lookup_email"
                value="{{ old('lookup_email') }}"
                placeholder="you@example.com"
                autocomplete="email"
                required
            >
        </label>
        <button type="submit" class="cyra-tp-btn cyra-tp-btn--primary w-full">
            Find my registration
        </button>
    </form>
</div>
