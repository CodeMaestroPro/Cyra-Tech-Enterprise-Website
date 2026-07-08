@if (auth()->user()?->hasPermission('projects.manage'))
    <x-ui.card title="Project Tasks" description="Manage delivery tasks and milestone work items for this project." class="mt-6">
        @if (count($project['tasks'] ?? []) > 0)
            <div class="mb-6 space-y-4">
                @foreach ($project['tasks'] as $task)
                    <form method="POST" action="{{ route('admin.projects.tasks.update', [$project['reference'], $task['reference']]) }}" class="rounded-lg border border-cyra-border bg-cyra-navy/40 p-4">
                        @csrf
                        @method('PUT')

                        <div class="grid gap-4 md:grid-cols-2">
                            <x-ui.input name="title" label="Title" :value="$task['title']" required />
                            <x-ui.select name="status" label="Status" required>
                                @foreach ($options['task_statuses'] as $status)
                                    <option value="{{ $status['slug'] }}" @selected($task['status'] === $status['slug'])>
                                        {{ $status['label'] }}
                                    </option>
                                @endforeach
                            </x-ui.select>
                            <x-ui.select name="priority" label="Priority" required>
                                @foreach ($options['priorities'] as $priority)
                                    <option value="{{ $priority['slug'] }}" @selected($task['priority'] === $priority['slug'])>
                                        {{ $priority['label'] }}
                                    </option>
                                @endforeach
                            </x-ui.select>
                            <x-ui.input name="due_date" label="Due Date" type="date" :value="$task['due_date']" />
                            <x-ui.select name="assigned_to" label="Assignee" placeholder="Unassigned">
                                <option value="">Unassigned</option>
                                @foreach ($options['managers'] as $id => $name)
                                    <option value="{{ $id }}" @selected((string) ($task['assigned_to'] ?? '') === (string) $id)>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </x-ui.select>
                            <x-ui.input name="sort_order" label="Sort Order" type="number" min="0" :value="$task['sort_order'] ?? 0" />
                        </div>

                        <div class="mt-4">
                            <x-ui.textarea name="description" label="Description" rows="2">{{ $task['description'] ?? '' }}</x-ui.textarea>
                        </div>

                        <div class="mt-4 flex items-center justify-between gap-3">
                            <label class="flex items-center gap-2 text-sm text-cyra-text">
                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    @checked($task['is_active'] ?? true)
                                    class="rounded border-cyra-border bg-cyra-navy text-cyra-primary focus:ring-cyra-primary"
                                />
                                Active task
                            </label>
                            <x-ui.button type="submit" variant="secondary" class="text-xs">Update Task</x-ui.button>
                        </div>
                    </form>
                @endforeach
            </div>
        @else
            <p class="mb-6 text-sm text-cyra-muted">No tasks yet. Add the first task below.</p>
        @endif

        <form method="POST" action="{{ route('admin.projects.tasks.store', $project['reference']) }}" class="rounded-lg border border-dashed border-cyra-border p-4">
            @csrf

            <h3 class="mb-4 text-sm font-semibold text-cyra-text">Add Task</h3>

            <div class="grid gap-4 md:grid-cols-2">
                <x-ui.input name="title" label="Title" required />
                <x-ui.select name="status" label="Status" placeholder="Pending">
                    @foreach ($options['task_statuses'] as $status)
                        <option value="{{ $status['slug'] }}" @selected($status['slug'] === 'pending')>
                            {{ $status['label'] }}
                        </option>
                    @endforeach
                </x-ui.select>
                <x-ui.select name="priority" label="Priority" placeholder="Medium">
                    @foreach ($options['priorities'] as $priority)
                        <option value="{{ $priority['slug'] }}" @selected($priority['slug'] === 'medium')>
                            {{ $priority['label'] }}
                        </option>
                    @endforeach
                </x-ui.select>
                <x-ui.input name="due_date" label="Due Date" type="date" />
            </div>

            <div class="mt-4">
                <x-ui.textarea name="description" label="Description" rows="2"></x-ui.textarea>
            </div>

            <div class="mt-4">
                <x-ui.button type="submit">Add Task</x-ui.button>
            </div>
        </form>
    </x-ui.card>
@endif
