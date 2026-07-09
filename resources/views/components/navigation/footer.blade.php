@props([
    'navigation' => [],
])

@php
    $footer = $navigation['footer'] ?? [];
    $columns = $footer['columns'] ?? [];
    $social = $footer['social'] ?? [];
    $legal = $footer['legal'] ?? [];
    $newsletter = $footer['newsletter'] ?? [];
    $newsletterStatus = session('newsletter_status');
    $newsletterMessage = session('newsletter_message');
@endphp

<footer class="border-t border-cyra-border bg-gradient-to-b from-cyra-navy to-cyra-midnight" data-navigation-footer>
    <div class="cyra-container cyra-section-compact">
        <div class="grid gap-10 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <x-brand.logo size="md" variant="compact" />
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-cyra-muted">
                    {{ config('cyra.tagline') }}
                </p>

                @if (! empty($newsletter))
                    <div id="newsletter-signup" class="mt-6 scroll-mt-24">
                        <h2 class="text-sm font-semibold text-cyra-text">{{ $newsletter['title'] ?? 'Newsletter' }}</h2>
                        <p class="mt-2 max-w-md text-sm leading-relaxed text-cyra-muted">{{ $newsletter['description'] ?? '' }}</p>

                        @if ($newsletterMessage)
                            <x-ui.alert
                                :variant="$newsletterStatus === 'success' ? 'success' : ($newsletterStatus === 'info' ? 'info' : 'error')"
                                class="mt-4"
                                role="status"
                            >
                                {{ $newsletterMessage }}
                            </x-ui.alert>
                        @endif

                        <form
                            method="POST"
                            action="{{ route('newsletter.subscribe') }}"
                            class="mt-4 flex w-full max-w-md flex-col gap-2 sm:flex-row sm:items-stretch"
                            aria-label="Newsletter signup"
                            data-newsletter-form
                        >
                            @csrf

                            <div class="min-w-0 flex-1">
                                <label for="newsletter-email" class="sr-only">Email address</label>
                                <input
                                    id="newsletter-email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="{{ $newsletter['placeholder'] ?? 'Email address' }}"
                                    autocomplete="email"
                                    inputmode="email"
                                    required
                                    @class([
                                        'cyra-input w-full',
                                        'cyra-input-error' => $errors->has('email'),
                                    ])
                                >
                                @error('email')
                                    <p class="mt-2 text-xs text-cyra-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <x-ui.button
                                type="submit"
                                variant="primary"
                                size="sm"
                                class="w-full shrink-0 sm:w-auto sm:min-w-[7.5rem]"
                            >
                                {{ $newsletter['button'] ?? 'Subscribe' }}
                            </x-ui.button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="grid gap-8 sm:grid-cols-2 lg:col-span-5 lg:grid-cols-4">
                @foreach ($columns as $column)
                    <div>
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-cyra-text">{{ $column['title'] }}</h2>
                        <ul class="mt-4 space-y-2">
                            @foreach ($column['links'] as $link)
                                <li>
                                    <a
                                        href="{{ $link['url'] }}"
                                        class="text-sm text-cyra-muted transition-colors hover:text-cyra-primary"
                                        @if ($link['opens_in_new_tab']) target="_blank" rel="noreferrer" @endif
                                    >
                                        {{ $link['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <div class="lg:col-span-3">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-cyra-text">Follow Cyra-Tech</h2>
                <ul class="mt-4 space-y-2">
                    @foreach ($social as $link)
                        <li>
                            <a
                                href="{{ $link['url'] }}"
                                class="text-sm text-cyra-muted transition-colors hover:text-cyra-primary"
                                target="_blank"
                                rel="noreferrer"
                            >
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="mt-10 flex flex-col gap-4 border-t border-cyra-border pt-6 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-cyra-muted">
                &copy; {{ date('Y') }} {{ config('cyra.name') }}. All rights reserved.
            </p>
            <ul class="flex flex-wrap gap-4">
                @foreach ($legal as $link)
                    <li>
                        <a href="{{ $link['url'] }}" class="text-sm text-cyra-muted hover:text-cyra-primary">
                            {{ $link['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</footer>
