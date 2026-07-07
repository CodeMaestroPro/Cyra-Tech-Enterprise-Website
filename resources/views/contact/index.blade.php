@extends('layouts.app')

@section('title', $contact['seo']['title'] ?? 'Contact')

@push('head')
    <meta name="description" content="{{ $contact['seo']['description'] ?? '' }}">
    @if (! empty($contact['seo']['keywords']))
        <meta name="keywords" content="{{ implode(', ', $contact['seo']['keywords']) }}">
    @endif
    <meta property="og:title" content="{{ $contact['seo']['title'] ?? 'Contact' }}">
    <meta property="og:description" content="{{ $contact['seo']['description'] ?? '' }}">
@endpush

@section('content')
    @php
        $hero = $contact['hero'] ?? [];
        $offices = $contact['offices'] ?? [];
        $channels = $contact['channels'] ?? [];
        $form = $contact['form'] ?? [];
        $support = $contact['support'] ?? [];
    @endphp

    <main id="main-content">
        <section class="border-b border-cyra-border/60 bg-cyra-navy/30">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <x-ui.breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => 'Contact'],
                ]" />

                @if (! empty($hero['eyebrow']))
                    <p class="cyra-caption mt-6">{{ $hero['eyebrow'] }}</p>
                @endif
                <h1 class="mt-3 cyra-display">{{ $hero['title'] ?? 'Contact' }}</h1>
                @if (! empty($hero['description']))
                    <p class="mt-4 max-w-3xl text-lg leading-relaxed text-cyra-muted">{{ $hero['description'] }}</p>
                @endif
            </div>
        </section>

        <section class="py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-10 lg:grid-cols-3">
                    <div class="lg:col-span-2">
                        <x-ui.section-heading
                            :eyebrow="$form['eyebrow'] ?? 'Get in Touch'"
                            :title="$form['title'] ?? 'Send us a message'"
                            :description="$form['description'] ?? null"
                            class="mb-8"
                        />

                        @if (session('success'))
                            <x-ui.alert variant="success" class="mb-6" role="status">
                                {{ session('success') }}
                                @if (session('reference'))
                                    <span class="mt-1 block text-sm">Reference: {{ session('reference') }}</span>
                                @endif
                            </x-ui.alert>
                        @endif

                        @if ($errors->any())
                            <x-ui.alert variant="error" class="mb-6">
                                {{ $errors->first() }}
                            </x-ui.alert>
                        @endif

                        <form
                            method="POST"
                            action="{{ route('contact.store') }}"
                            class="cyra-card space-y-5 p-6"
                            id="contact-form"
                            novalidate
                        >
                            @csrf

                            <div class="grid gap-5 md:grid-cols-2">
                                <x-ui.input
                                    name="name"
                                    label="Full name"
                                    placeholder="Your name"
                                    autocomplete="name"
                                    required
                                />

                                <x-ui.input
                                    name="email"
                                    type="email"
                                    label="Work email"
                                    placeholder="you@company.com"
                                    autocomplete="email"
                                    required
                                />
                            </div>

                            <div class="grid gap-5 md:grid-cols-2">
                                <x-ui.input
                                    name="company"
                                    label="Company"
                                    placeholder="Organization name"
                                    autocomplete="organization"
                                />

                                <x-ui.input
                                    name="phone"
                                    type="tel"
                                    label="Phone"
                                    placeholder="+1 (555) 000-0000"
                                    autocomplete="tel"
                                />
                            </div>

                            <x-ui.select
                                name="inquiry_type"
                                label="Inquiry type"
                                :options="$inquiryOptions"
                                placeholder="Select inquiry type"
                                required
                                id="inquiry-type"
                            />

                            <x-ui.textarea
                                name="message"
                                label="Message"
                                placeholder="Tell us about your goals, timeline, and how we can help."
                                rows="6"
                                required
                            />

                            <x-ui.button type="submit" class="w-full sm:w-auto" id="contact-submit">
                                {{ $form['submit_label'] ?? 'Send Message' }}
                            </x-ui.button>
                        </form>
                    </div>

                    <aside class="space-y-6">
                        <div class="cyra-card p-6">
                            <h2 class="text-lg font-semibold text-cyra-text">Global Offices</h2>
                            <ul class="mt-4 space-y-4">
                                @foreach ($offices as $office)
                                    <li>
                                        <p class="font-medium text-cyra-text">{{ $office['city'] ?? '' }}</p>
                                        @if (! empty($office['address']))
                                            <p class="mt-1 text-sm text-cyra-muted">{{ $office['address'] }}</p>
                                        @endif
                                        @if (! empty($office['hours']))
                                            <p class="mt-1 text-xs text-cyra-muted">{{ $office['hours'] }}</p>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="cyra-card p-6">
                            <h2 class="text-lg font-semibold text-cyra-text">Direct Channels</h2>
                            <ul class="mt-4 space-y-3">
                                @foreach ($channels as $channel)
                                    <li>
                                        <p class="text-sm font-medium text-cyra-text">{{ $channel['label'] ?? '' }}</p>
                                        <a href="mailto:{{ $channel['email'] ?? '' }}" class="text-sm text-cyra-accent hover:text-cyra-primary">
                                            {{ $channel['email'] ?? '' }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @if (! empty($support))
                            <div class="rounded-lg border border-cyra-border/70 bg-cyra-surface/40 p-6">
                                <h2 class="text-sm font-semibold uppercase tracking-wide text-cyra-muted">{{ $support['title'] ?? 'Support' }}</h2>
                                <p class="mt-2 text-sm leading-relaxed text-cyra-text">{{ $support['description'] ?? '' }}</p>
                            </div>
                        @endif
                    </aside>
                </div>
            </div>
        </section>
    </main>
@endsection
