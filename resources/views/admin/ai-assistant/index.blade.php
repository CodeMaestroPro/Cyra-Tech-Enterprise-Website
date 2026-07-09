@extends('layouts.admin')

@section('title', 'AI Assistant')

@section('content')
    <div
        id="ai-assistant-page"
        class="space-y-6"
        data-query-url="{{ route('admin.ai-assistant.query') }}"
        data-csrf-token="{{ csrf_token() }}"
    >
        <x-ui.breadcrumb :items="[
            ['label' => 'Admin', 'href' => route('admin.dashboard')],
            ['label' => 'AI Assistant'],
        ]" />

        <x-ui.section-heading
            eyebrow="Executive Workspace"
            :title="$workspace['assistant']['name']"
            :description="$workspace['assistant']['tagline']"
        />

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($workspace['insights'] as $insight)
                <div class="cyra-card flex items-center gap-4 p-4">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-cyra-primary/30 bg-cyra-primary/10 text-cyra-accent">
                        <x-ui.icon :name="$insight['icon'] ?? 'spark'" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="text-xs text-cyra-muted">{{ $insight['label'] }}</p>
                        <p class="text-lg font-semibold text-cyra-text">{{ $insight['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.7fr)_minmax(0,1fr)]">
            <x-ui.card title="Executive Chat" description="Ask Cyra AI about company health, pipeline, projects, analytics, and strategic priorities.">
                <div
                    id="ai-assistant-messages"
                    class="mb-4 max-h-[32rem] space-y-4 overflow-y-auto rounded-xl border border-cyra-border/70 bg-cyra-navy/40 p-4"
                    aria-live="polite"
                    aria-label="AI Assistant conversation"
                >
                    <div class="flex items-start gap-3" data-message-role="assistant">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-cyra-primary/30 bg-cyra-primary/10 text-cyra-accent">
                            <x-ui.icon name="ai" class="h-4 w-4" />
                        </span>
                        <div class="min-w-0 flex-1 rounded-xl border border-cyra-border/60 bg-cyra-surface/60 px-4 py-3">
                            <p class="whitespace-pre-line text-sm leading-relaxed text-cyra-text">{{ $workspace['initial_message']['content'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-4 flex flex-wrap gap-2">
                    @foreach ($workspace['prompts'] as $prompt)
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-full border border-cyra-border bg-cyra-navy/60 px-3 py-1.5 text-xs font-medium text-cyra-muted transition hover:border-cyra-primary/40 hover:text-cyra-text"
                            data-ai-prompt="{{ $prompt['slug'] }}"
                            data-ai-prompt-label="{{ $prompt['label'] }}"
                        >
                            <x-ui.icon :name="$prompt['icon'] ?? 'spark'" class="h-3.5 w-3.5" />
                            {{ $prompt['label'] }}
                        </button>
                    @endforeach
                </div>

                <form id="ai-assistant-form" class="flex flex-col gap-3 sm:flex-row">
                    <label class="sr-only" for="ai-assistant-input">Message Cyra AI</label>
                    <input
                        id="ai-assistant-input"
                        type="text"
                        name="message"
                        maxlength="1000"
                        placeholder="Ask Cyra AI anything about the enterprise..."
                        class="flex-1 rounded-lg border border-cyra-border bg-cyra-navy px-4 py-3 text-sm text-cyra-text placeholder:text-cyra-muted focus:border-cyra-primary focus:outline-none focus:ring-2 focus:ring-cyra-primary/30"
                        autocomplete="off"
                        required
                    />
                    <x-ui.button type="submit" id="ai-assistant-submit">
                        Send
                    </x-ui.button>
                </form>

                <p id="ai-assistant-status" class="mt-3 hidden text-xs text-cyra-muted"></p>
            </x-ui.card>

            <aside class="space-y-6">
                <x-ui.card title="Model Status" description="Cyra Intelligence Engine operational profile.">
                    <dl class="space-y-4">
                        <div class="flex items-center justify-between border-b border-cyra-border/60 pb-3">
                            <dt class="text-sm text-cyra-muted">Engine</dt>
                            <dd class="text-sm font-medium text-cyra-text">{{ $workspace['assistant']['model']['label'] ?? 'Cyra Intelligence Engine' }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-b border-cyra-border/60 pb-3">
                            <dt class="text-sm text-cyra-muted">Version</dt>
                            <dd class="text-sm font-medium text-cyra-text">{{ $workspace['assistant']['model']['version'] ?? '1.0' }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-b border-cyra-border/60 pb-3">
                            <dt class="text-sm text-cyra-muted">Latency</dt>
                            <dd class="text-sm font-medium text-cyra-text">{{ $workspace['assistant']['model']['latency'] ?? '< 2s' }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-cyra-muted">Status</dt>
                            <dd><x-ui.badge variant="success">{{ ucfirst($workspace['assistant']['model']['status'] ?? 'operational') }}</x-ui.badge></dd>
                        </div>
                    </dl>
                </x-ui.card>

                <x-ui.card title="Capabilities" description="What Cyra AI can analyze for executives.">
                    <div class="space-y-3">
                        @foreach ($workspace['capabilities'] as $capability)
                            <div class="flex items-start gap-3 rounded-lg border border-cyra-border/70 bg-cyra-navy/50 px-4 py-3">
                                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-cyra-primary/10 text-cyra-accent">
                                    <x-ui.icon :name="$capability['icon'] ?? 'ai'" class="h-4 w-4" />
                                </span>
                                <div>
                                    <p class="text-sm font-medium text-cyra-text">{{ $capability['title'] }}</p>
                                    <p class="mt-1 text-xs text-cyra-muted">{{ $capability['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-ui.card title="Guardrails" description="Responsible use guidelines for executive AI.">
                    <ul class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-1">
                        @foreach ($workspace['guardrails'] as $guardrail)
                            <li class="flex min-w-0 items-start gap-3 rounded-lg border border-cyra-border/70 bg-cyra-navy/50 p-3 sm:p-4">
                                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-cyra-primary/10 text-cyra-accent sm:h-9 sm:w-9">
                                    <x-ui.icon name="shield" class="h-4 w-4" />
                                </span>
                                <span class="min-w-0 flex-1 text-xs leading-relaxed text-cyra-muted sm:text-sm">{{ $guardrail }}</span>
                            </li>
                        @endforeach
                    </ul>
                </x-ui.card>

                <x-ui.card title="Related Workspaces">
                    <div class="space-y-2">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 rounded-lg border border-cyra-border/70 px-4 py-3 text-sm text-cyra-text transition hover:border-cyra-primary/40 hover:bg-cyra-surface/40">
                            <x-ui.icon name="command" class="h-4 w-4 text-cyra-accent" />
                            Command Center Brief
                        </a>
                        <a href="{{ route('admin.strategic-roadmap.index') }}" class="flex items-center gap-2 rounded-lg border border-cyra-border/70 px-4 py-3 text-sm text-cyra-text transition hover:border-cyra-primary/40 hover:bg-cyra-surface/40">
                            <x-ui.icon name="map" class="h-4 w-4 text-cyra-accent" />
                            Strategic Roadmap
                        </a>
                        <a href="{{ route('admin.analytics.index') }}" class="flex items-center gap-2 rounded-lg border border-cyra-border/70 px-4 py-3 text-sm text-cyra-text transition hover:border-cyra-primary/40 hover:bg-cyra-surface/40">
                            <x-ui.icon name="analytics" class="h-4 w-4 text-cyra-accent" />
                            Analytics Dashboard
                        </a>
                    </div>
                </x-ui.card>
            </aside>
        </div>
    </div>
@endsection
