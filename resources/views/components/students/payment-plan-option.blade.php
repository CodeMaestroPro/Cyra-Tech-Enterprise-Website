@props([
    'value',
    'label',
    'description' => '',
    'meta' => '',
    'selected' => false,
])

<label class="cyra-tp-plan {{ $selected ? 'is-selected' : '' }}" data-payment-plan-option="{{ $value }}">
    <input
        type="radio"
        name="payment_plan"
        value="{{ $value }}"
        class="sr-only"
        @checked($selected)
        required
    >
    <span class="cyra-tp-plan-radio" aria-hidden="true"></span>
    <span class="min-w-0 flex-1">
        <span class="cyra-tp-plan-title">{{ $label }}</span>
        @if ($description)
            <span class="cyra-tp-plan-description">{{ $description }}</span>
        @endif
    </span>
    @if ($meta)
        <span class="cyra-tp-plan-meta">{{ $meta }}</span>
    @endif
</label>
