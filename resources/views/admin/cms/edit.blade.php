@extends('layouts.admin')

@section('title', 'Edit Page')

@section('content')
    <x-ui.breadcrumb :items="[
        ['label' => 'Admin', 'href' => route('admin.dashboard')],
        ['label' => 'Pages', 'href' => route('admin.cms.index')],
        ['label' => $page['title']],
    ]" class="mb-6" />

    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <x-ui.section-heading
            :title="$page['title']"
            :description="$page['description'] ?? 'Update page content, SEO metadata, and publication status.'"
        />

        <div class="flex flex-wrap gap-2">
            @if ($page['status'] === 'published')
                <x-ui.button href="{{ route('pages.show', $page['slug']) }}" variant="secondary" target="_blank">
                    View Live
                </x-ui.button>
                @if (auth()->user()?->hasPermission('cms.publish'))
                    <form method="POST" action="{{ route('admin.cms.unpublish', $page['slug']) }}">
                        @csrf
                        <x-ui.button type="submit" variant="outline">Unpublish</x-ui.button>
                    </form>
                @endif
            @elseif (auth()->user()?->hasPermission('cms.publish'))
                <form method="POST" action="{{ route('admin.cms.publish', $page['slug']) }}">
                    @csrf
                    <x-ui.button type="submit" variant="success">Publish</x-ui.button>
                </form>
            @endif
        </div>
    </div>

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    @include('admin.cms._form', [
        'action' => route('admin.cms.update', $page['slug']),
        'method' => 'PUT',
        'page' => $page,
        'templates' => $templates,
        'statuses' => $statuses,
    ])
@endsection
