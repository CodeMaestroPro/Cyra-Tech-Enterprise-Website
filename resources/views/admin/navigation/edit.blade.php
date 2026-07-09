@extends('layouts.admin')

@section('title', 'Edit Navigation Item')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Navigation', 'href' => route('admin.navigation.index')],
        ['label' => $item['label']],
    ]" class="mb-6" />

    <x-ui.section-heading
        class="mb-8"
        eyebrow="Navigation"
        :title="$item['label']"
        :description="'Group: '.$item['group_label']"
    />

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    <form method="POST" action="{{ route('admin.navigation.update', $item['id']) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <x-ui.card title="Navigation Item">
            <div class="grid gap-6 md:grid-cols-2">
                <x-ui.input name="label" label="Label" :value="old('label', $item['label'])" required />
                <x-ui.input name="route_name" label="Route Name" :value="old('route_name', $item['route_name'])" placeholder="admin.dashboard" />
                <x-ui.input name="url" label="Fallback URL" :value="old('url', $item['url'])" />
                <x-ui.input name="permission" label="Permission" :value="old('permission', $item['permission'])" />
                <x-ui.input name="sort_order" label="Sort Order" type="number" min="0" :value="old('sort_order', $item['sort_order'])" />
            </div>
        </x-ui.card>

        <x-ui.card title="Visibility">
            <div class="flex flex-wrap gap-6">
                <label class="inline-flex items-center gap-2 text-sm text-cyra-muted">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item['is_active'])) class="h-4 w-4 rounded border-cyra-border bg-cyra-navy text-cyra-primary focus:ring-cyra-primary/30" />
                    <span>Active</span>
                </label>
                <label class="inline-flex items-center gap-2 text-sm text-cyra-muted">
                    <input type="checkbox" name="is_available" value="1" @checked(old('is_available', $item['is_available'])) class="h-4 w-4 rounded border-cyra-border bg-cyra-navy text-cyra-primary focus:ring-cyra-primary/30" />
                    <span>Available</span>
                </label>
                <label class="inline-flex items-center gap-2 text-sm text-cyra-muted">
                    <input type="checkbox" name="opens_in_new_tab" value="1" @checked(old('opens_in_new_tab', $item['opens_in_new_tab'])) class="h-4 w-4 rounded border-cyra-border bg-cyra-navy text-cyra-primary focus:ring-cyra-primary/30" />
                    <span>Open in new tab</span>
                </label>
            </div>
        </x-ui.card>

        <div class="flex flex-wrap gap-3">
            <x-ui.button type="submit">Save Changes</x-ui.button>
            <x-ui.button href="{{ route('admin.navigation.index') }}" variant="secondary">Cancel</x-ui.button>
        </div>
    </form>
@endsection
