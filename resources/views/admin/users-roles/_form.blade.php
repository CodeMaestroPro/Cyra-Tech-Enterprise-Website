<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <x-ui.card title="Account Details">
        <div class="grid gap-6 md:grid-cols-2">
            <x-ui.input
                name="name"
                label="Full Name"
                :value="old('name', $user?->name)"
                required
            />

            <x-ui.input
                name="email"
                label="Email Address"
                type="email"
                :value="old('email', $user?->email)"
                required
            />

            <x-ui.input
                name="password"
                label="{{ $user === null ? 'Password' : 'New Password' }}"
                type="password"
                :required="$user === null"
                placeholder="{{ $user === null ? null : 'Leave blank to keep current password' }}"
            />

            <x-ui.input
                name="password_confirmation"
                label="Confirm Password"
                type="password"
                :required="$user === null"
                placeholder="{{ $user === null ? null : 'Leave blank to keep current password' }}"
            />
        </div>

        <div class="mt-6">
            <x-ui.checkbox
                name="is_active"
                label="Active account"
                :checked="old('is_active', $user?->is_active ?? true)"
            />
        </div>
    </x-ui.card>

    @if ($formOptions['can_manage_roles'])
        <x-ui.card title="Role Assignment" description="Select one or more platform roles for this user.">
            @error('roles')
                <x-ui.alert variant="danger" class="mb-4">{{ $message }}</x-ui.alert>
            @enderror

            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($formOptions['assignable_roles'] as $role)
                    @php
                        $selectedRoles = old('roles', $user?->roles->pluck('slug')->all() ?? []);
                        $isChecked = in_array($role['slug'], $selectedRoles, true);
                    @endphp
                    <label @class([
                        'flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition',
                        'border-cyra-primary/50 bg-cyra-primary/10' => $isChecked,
                        'border-cyra-border/70 bg-cyra-navy/50 hover:border-cyra-primary/30' => ! $isChecked,
                    ])>
                        <input
                            type="checkbox"
                            name="roles[]"
                            value="{{ $role['slug'] }}"
                            @checked($isChecked)
                            class="mt-1 h-4 w-4 rounded border-cyra-border bg-cyra-navy text-cyra-primary focus:ring-cyra-primary/30"
                        />
                        <span class="min-w-0">
                            <span class="flex items-center gap-2 text-sm font-medium text-cyra-text">
                                <x-ui.icon :name="$role['icon']" class="h-4 w-4 text-cyra-accent" />
                                {{ $role['name'] }}
                            </span>
                            <span class="mt-1 block text-xs text-cyra-muted">{{ $role['description'] }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </x-ui.card>
    @endif

    <div class="flex flex-wrap items-center gap-3">
        <x-ui.button type="submit">
            {{ $user === null ? 'Create User' : 'Save Changes' }}
        </x-ui.button>

        <x-ui.button href="{{ route('admin.users-roles.index') }}" variant="secondary">
            Cancel
        </x-ui.button>

        @if ($user !== null && auth()->user()?->can('delete', $user))
            <x-ui.button
                type="submit"
                form="delete-user-form"
                variant="danger"
                class="ml-auto"
                onclick="return confirm('Delete this user permanently?')"
            >
                Delete User
            </x-ui.button>
        @endif
    </div>
</form>

@if ($user !== null && auth()->user()?->can('delete', $user))
    <form
        id="delete-user-form"
        method="POST"
        action="{{ route('admin.users-roles.destroy', $user) }}"
        class="hidden"
    >
        @csrf
        @method('DELETE')
    </form>
@endif
