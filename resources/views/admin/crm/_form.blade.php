<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <x-ui.card title="Lead Details">
        <div class="grid gap-6 md:grid-cols-2">
            <x-ui.input name="name" label="Full Name" :value="$lead['name'] ?? null" required />
            <x-ui.input name="email" label="Email" type="email" :value="$lead['email'] ?? null" required />
            <x-ui.input name="company" label="Company" :value="$lead['company'] ?? null" />
            <x-ui.input name="phone" label="Phone" :value="$lead['phone'] ?? null" />
            <x-ui.input name="job_title" label="Job Title" :value="$lead['job_title'] ?? null" />

            <x-ui.select name="source" label="Source" required placeholder="Select source">
                @foreach ($options['sources'] as $source)
                    <option value="{{ $source['slug'] }}" @selected(old('source', $lead['source'] ?? '') === $source['slug'])>
                        {{ $source['label'] }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.select name="pipeline_stage" label="Pipeline Stage" required placeholder="Select stage">
                @foreach ($options['pipeline_stages'] as $stage)
                    <option value="{{ $stage['slug'] }}" @selected(old('pipeline_stage', $lead['pipeline_stage'] ?? 'new') === $stage['slug'])>
                        {{ $stage['label'] }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.select name="priority" label="Priority" required placeholder="Select priority">
                @foreach ($options['priorities'] as $priority)
                    <option value="{{ $priority['slug'] }}" @selected(old('priority', $lead['priority'] ?? 'medium') === $priority['slug'])>
                        {{ $priority['label'] }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.input
                name="estimated_value"
                label="Estimated Value (₦)"
                type="number"
                step="0.01"
                min="0"
                :value="$lead['estimated_value'] ?? null"
            />

            <x-ui.select name="assigned_to" label="Assigned To" placeholder="Unassigned">
                <option value="">Unassigned</option>
                @foreach ($options['assignees'] as $id => $name)
                    <option value="{{ $id }}" @selected((string) old('assigned_to', $lead['assigned_to'] ?? '') === (string) $id)>
                        {{ $name }}
                    </option>
                @endforeach
            </x-ui.select>

            @if ($lead !== null)
                <x-ui.input
                    name="sort_order"
                    label="Sort Order"
                    type="number"
                    min="0"
                    :value="$lead['sort_order'] ?? 0"
                />

                <div class="flex items-center gap-3 pt-7">
                    <input
                        id="is_active"
                        name="is_active"
                        type="checkbox"
                        value="1"
                        @checked(old('is_active', $lead['is_active'] ?? true))
                        class="rounded border-cyra-border bg-cyra-navy text-cyra-primary focus:ring-cyra-primary"
                    />
                    <label for="is_active" class="text-sm text-cyra-text">Active lead</label>
                </div>

                @if ($lead['contact_inquiry_reference'] ?? null)
                    <div class="md:col-span-2">
                        <x-ui.label>Linked Inquiry</x-ui.label>
                        <p class="rounded-lg border border-cyra-border bg-cyra-navy px-4 py-2.5 text-sm text-cyra-muted">
                            {{ $lead['contact_inquiry_reference'] }}
                        </p>
                    </div>
                @endif
            @endif
        </div>

        <div class="mt-6">
            <x-ui.textarea name="notes" label="Notes" rows="5">{{ old('notes', $lead['notes'] ?? '') }}</x-ui.textarea>
        </div>
    </x-ui.card>

    <div class="flex flex-wrap gap-3">
        <x-ui.button type="submit">
            {{ $lead === null ? 'Create Lead' : 'Save Changes' }}
        </x-ui.button>
        <x-ui.button href="{{ route('admin.crm.index') }}" variant="secondary">
            Cancel
        </x-ui.button>
    </div>
</form>
