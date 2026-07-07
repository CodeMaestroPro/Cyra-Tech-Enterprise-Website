@extends('layouts.admin')

@section('title', 'Design System')

@section('content')
    <div data-design-system-page>
        <x-ui.breadcrumb :items="[
            ['label' => 'Admin', 'href' => route('admin.dashboard')],
            ['label' => 'Design System'],
        ]" class="mb-6" />

        <x-ui.section-heading
            eyebrow="Module 03 Complete"
            title="Cyra-Tech Design System"
            description="Blade components, Tailwind CSS 4 tokens, and vanilla JavaScript behaviors. No React."
            class="mb-8"
        />

        <x-ui.tabs id="design-system-tabs" :labels="['Colors', 'Typography', 'Components', 'Interactive']">
            <div data-cyra-tab-panel="0" class="space-y-6">
                <x-ui.card title="Color Tokens" description="Core palette from the Command Center and public site mockups.">
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($catalog['tokens']['colors'] as $name => $hex)
                            <div class="rounded-lg border border-cyra-border bg-cyra-navy/60 p-4">
                                <div class="mb-3 h-12 rounded-lg border border-cyra-border" style="background-color: {{ $hex }}"></div>
                                <p class="text-sm font-medium text-cyra-text">{{ $name }}</p>
                                <p class="text-xs text-cyra-muted">{{ $hex }}</p>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>
            </div>

            <div data-cyra-tab-panel="1" class="hidden space-y-6">
                <x-ui.card title="Typography Scale">
                    <div class="space-y-6">
                        <p class="cyra-display">Display / Hero</p>
                        <p class="cyra-heading-1">Heading 1</p>
                        <p class="cyra-heading-2">Heading 2</p>
                        <p class="cyra-heading-3">Heading 3</p>
                        <p class="cyra-body">Body text for descriptions and supporting content.</p>
                        <p class="cyra-small">Small text for metadata.</p>
                        <p class="cyra-caption">Caption / Eyebrow</p>
                    </div>
                </x-ui.card>
            </div>

            <div data-cyra-tab-panel="2" class="hidden space-y-6">
                <x-ui.card title="Buttons">
                    <div class="flex flex-wrap gap-3">
                        <x-ui.button>Primary</x-ui.button>
                        <x-ui.button variant="secondary">Secondary</x-ui.button>
                        <x-ui.button variant="outline">Outline</x-ui.button>
                        <x-ui.button variant="ghost">Ghost</x-ui.button>
                        <x-ui.button variant="success">Success</x-ui.button>
                        <x-ui.button variant="danger">Danger</x-ui.button>
                    </div>
                </x-ui.card>

                <x-ui.card title="Badges & Alerts">
                    <div class="flex flex-wrap gap-2">
                        <x-ui.badge>Default</x-ui.badge>
                        <x-ui.badge variant="primary">Primary</x-ui.badge>
                        <x-ui.badge variant="success">Success</x-ui.badge>
                        <x-ui.badge variant="warning">Warning</x-ui.badge>
                        <x-ui.status-badge status="completed" />
                        <x-ui.status-badge status="in_progress" />
                    </div>
                    <div class="mt-4 space-y-3">
                        <x-ui.alert variant="info">Informational alert for neutral messages.</x-ui.alert>
                        <x-ui.alert variant="success">Success alert for completed actions.</x-ui.alert>
                        <x-ui.alert variant="error">Error alert for validation or failure states.</x-ui.alert>
                    </div>
                </x-ui.card>

                <x-ui.card title="Form Controls">
                    <div class="grid gap-4 md:grid-cols-2">
                        <x-ui.input name="demo_email" label="Email" placeholder="name@cyratech.com" />
                        <x-ui.select name="demo_role" label="Role" placeholder="Select role" :options="['admin' => 'Administrator', 'viewer' => 'Viewer']" />
                        <x-ui.textarea name="demo_notes" label="Notes" placeholder="Enter notes..." class="md:col-span-2" />
                        <x-ui.checkbox name="demo_remember" label="Remember preference" />
                    </div>
                </x-ui.card>

                <x-ui.card title="Data Display">
                    <x-ui.table>
                        <thead>
                            <tr>
                                <th>Component</th>
                                <th>Blade Tag</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($catalog['components'] as $component)
                                <tr>
                                    <td>{{ $component['name'] }}</td>
                                    <td><code>&lt;{{ $component['blade'] }}&gt;</code></td>
                                    <td><x-ui.badge variant="success">Ready</x-ui.badge></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </x-ui.table>
                </x-ui.card>

                <x-ui.empty-state title="Empty State" description="Use when lists or tables have no records.">
                    <x-slot:action>
                        <x-ui.button variant="secondary">Add Item</x-ui.button>
                    </x-slot:action>
                </x-ui.empty-state>
            </div>

            <div data-cyra-tab-panel="3" class="hidden space-y-6">
                <x-ui.card title="Modal & Spinner" description="Vanilla JavaScript only — no React.">
                    <div class="flex flex-wrap items-center gap-4">
                        <x-ui.button data-cyra-modal-open="demo-modal">Open Modal</x-ui.button>
                        <x-ui.spinner size="lg" />
                    </div>
                </x-ui.card>

                <x-ui.card title="Design Principles">
                    <ul class="list-disc space-y-2 pl-5 text-sm text-cyra-muted">
                        @foreach ($catalog['principles'] as $principle)
                            <li>{{ $principle }}</li>
                        @endforeach
                    </ul>
                </x-ui.card>
            </div>
        </x-ui.tabs>

        <x-ui.modal id="demo-modal" title="Example Modal" description="Accessible dialog powered by vanilla JavaScript.">
            <p class="cyra-body">This modal uses data attributes and plain JS event listeners. It supports backdrop click and close button interactions.</p>
            <x-slot:footer>
                <x-ui.button variant="secondary" data-cyra-modal-close>Cancel</x-ui.button>
                <x-ui.button data-cyra-modal-close>Confirm</x-ui.button>
            </x-slot:footer>
        </x-ui.modal>
    </div>
@endsection
