@props(['for' => null])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-cyra-text']) }} @if($for) for="{{ $for }}" @endif>
    {{ $slot }}
</label>
