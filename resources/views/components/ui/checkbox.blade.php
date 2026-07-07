@props(['name', 'label', 'checked' => false])

<label class="inline-flex items-center gap-2 text-sm text-cyra-muted">
    <input
        type="checkbox"
        name="{{ $name }}"
        value="1"
        @checked(old($name, $checked))
        class="h-4 w-4 rounded border-cyra-border bg-cyra-navy text-cyra-primary focus:ring-cyra-primary/30"
    />
    <span>{{ $label }}</span>
</label>
