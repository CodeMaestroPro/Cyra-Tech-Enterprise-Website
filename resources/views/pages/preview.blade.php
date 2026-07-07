@extends('layouts.app')

@section('title', $title)

@section('content')
    <main id="main-content" class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
        <x-ui.breadcrumb :items="[
            ['label' => 'Home', 'href' => route('home')],
            ['label' => $title],
        ]" />

        <div class="mt-8 rounded-xl border border-cyra-border bg-cyra-surface/40 p-8">
            <p class="text-sm font-medium uppercase tracking-[0.2em] text-cyra-accent">
                Module {{ $module['id'] ?? '—' }} Preview
            </p>
            <h1 class="mt-3 text-3xl font-bold text-cyra-text">{{ $title }}</h1>
            <p class="mt-4 text-base leading-relaxed text-cyra-muted">
                This section is scaffolded by Module 04 (Global Navigation). Full content will be delivered when the
                <strong class="text-cyra-text">{{ $module['name'] ?? $title }}</strong> module is implemented.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <x-ui.button href="{{ route('home') }}" variant="secondary">Back to Home</x-ui.button>
                <x-ui.button href="{{ route('contact') }}">Contact Us</x-ui.button>
            </div>
        </div>
    </main>
@endsection
