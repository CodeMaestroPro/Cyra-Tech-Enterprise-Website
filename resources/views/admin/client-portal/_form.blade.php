<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <x-ui.card title="Account Details">
        <div class="grid gap-6 md:grid-cols-2">
            <x-ui.input name="name" label="Account Name" :value="old('name', $account['name'] ?? null)" required />

            @if ($account === null)
                <x-ui.input name="slug" label="Slug" placeholder="acme-corp" required />
            @else
                <div class="space-y-2">
                    <x-ui.label>Slug</x-ui.label>
                    <p class="rounded-lg border border-cyra-border bg-cyra-navy px-4 py-2.5 text-sm text-cyra-muted">{{ $account['slug'] }}</p>
                </div>
            @endif

            <x-ui.input name="industry" label="Industry" :value="old('industry', $account['industry'] ?? null)" />
            <x-ui.input name="region" label="Region" :value="old('region', $account['region'] ?? null)" />
            <x-ui.input name="account_manager" label="Account Manager" :value="old('account_manager', $account['account_manager'] ?? null)" />
            <x-ui.input name="support_email" label="Support Email" type="email" :value="old('support_email', $account['support_email'] ?? null)" />
        </div>
    </x-ui.card>

    <x-ui.card title="Visibility">
        <label class="inline-flex items-center gap-2 text-sm text-cyra-muted">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $account['is_active'] ?? true)) class="h-4 w-4 rounded border-cyra-border bg-cyra-navy text-cyra-primary focus:ring-cyra-primary/30" />
            <span>Active account</span>
        </label>
    </x-ui.card>

    <div class="flex flex-wrap items-center gap-3">
        <x-ui.button type="submit">{{ $account === null ? 'Create Account' : 'Save Changes' }}</x-ui.button>
        <x-ui.button href="{{ route('admin.client-portal.index') }}" variant="secondary">Cancel</x-ui.button>

        @if ($account !== null && auth()->user()?->hasPermission('cms.update'))
            <x-ui.button type="submit" form="delete-account-form" variant="danger" class="ml-auto" onclick="return confirm('Delete this account permanently?')">
                Delete Account
            </x-ui.button>
        @endif
    </div>
</form>

@if ($account !== null && auth()->user()?->hasPermission('cms.update'))
    <form id="delete-account-form" method="POST" action="{{ route('admin.client-portal.destroy', $account['slug']) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endif
