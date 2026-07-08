<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <x-ui.card title="Project Details">
        <div class="grid gap-6 md:grid-cols-2">
            <x-ui.input name="name" label="Project Name" :value="$project['name'] ?? null" required />
            <x-ui.input name="client_name" label="Client" :value="$project['client_name'] ?? null" />

            <x-ui.select name="status" label="Status" required placeholder="Select status">
                @foreach ($options['statuses'] as $status)
                    <option value="{{ $status['slug'] }}" @selected(old('status', $project['status'] ?? 'planning') === $status['slug'])>
                        {{ $status['label'] }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.select name="phase" label="Phase" required placeholder="Select phase">
                @foreach ($options['phases'] as $phase)
                    <option value="{{ $phase['slug'] }}" @selected(old('phase', $project['phase'] ?? 'discovery') === $phase['slug'])>
                        {{ $phase['label'] }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.select name="priority" label="Priority" required placeholder="Select priority">
                @foreach ($options['priorities'] as $priority)
                    <option value="{{ $priority['slug'] }}" @selected(old('priority', $project['priority'] ?? 'medium') === $priority['slug'])>
                        {{ $priority['label'] }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.input
                name="progress"
                label="Progress (%)"
                type="number"
                min="0"
                max="100"
                :value="$project['progress'] ?? 0"
            />

            <x-ui.input
                name="budget"
                label="Budget (₦)"
                type="number"
                step="0.01"
                min="0"
                :value="$project['budget'] ?? null"
            />

            <x-ui.input name="start_date" label="Start Date" type="date" :value="$project['start_date'] ?? null" />
            <x-ui.input name="due_date" label="Due Date" type="date" :value="$project['due_date'] ?? null" />

            <x-ui.select name="project_manager_id" label="Project Manager" placeholder="Unassigned">
                <option value="">Unassigned</option>
                @foreach ($options['managers'] as $id => $name)
                    <option value="{{ $id }}" @selected((string) old('project_manager_id', $project['project_manager_id'] ?? '') === (string) $id)>
                        {{ $name }}
                    </option>
                @endforeach
            </x-ui.select>

            @if ($project !== null)
                <x-ui.input
                    name="sort_order"
                    label="Sort Order"
                    type="number"
                    min="0"
                    :value="$project['sort_order'] ?? 0"
                />

                <div class="flex items-center gap-3 pt-7">
                    <input
                        id="is_active"
                        name="is_active"
                        type="checkbox"
                        value="1"
                        @checked(old('is_active', $project['is_active'] ?? true))
                        class="rounded border-cyra-border bg-cyra-navy text-cyra-primary focus:ring-cyra-primary"
                    />
                    <label for="is_active" class="text-sm text-cyra-text">Active project</label>
                </div>
            @endif
        </div>

        <div class="mt-6">
            <x-ui.textarea name="description" label="Description" rows="4">{{ old('description', $project['description'] ?? '') }}</x-ui.textarea>
        </div>
    </x-ui.card>

    <div class="flex flex-wrap gap-3">
        <x-ui.button type="submit">
            {{ $project === null ? 'Create Project' : 'Save Changes' }}
        </x-ui.button>
        <x-ui.button href="{{ route('admin.projects.index') }}" variant="secondary">
            Cancel
        </x-ui.button>
    </div>
</form>
