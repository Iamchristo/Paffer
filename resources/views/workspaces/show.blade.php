<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $workspace->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('Done.') }}</div>
            @endif
            @if (session('error') === 'workspace-member-not-found')
                <div class="bg-red-50 text-red-700 text-sm rounded-md p-3">{{ __('No user found with that email.') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6 flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-600 whitespace-pre-line">{{ $workspace->description }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ __('Owner') }}: {{ $workspace->owner->name }}</p>
                </div>
                @if ($workspace->owner_id === Auth::id())
                    <form method="POST" action="{{ route('workspaces.destroy', $workspace) }}" onsubmit="return confirm('{{ __('Delete this workspace?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-500">{{ __('Delete Workspace') }}</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('workspace-members.destroy', [$workspace, Auth::user()]) }}">
                        @csrf
                        @method('DELETE')
                        <x-secondary-button>{{ __('Leave') }}</x-secondary-button>
                    </form>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-semibold text-gray-900 mb-3">{{ __('New task') }}</h3>
                        <form method="POST" action="{{ route('workspace-tasks.store', $workspace) }}" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @csrf
                            <input type="text" name="title" placeholder="{{ __('Task title') }}" class="rounded-md border-gray-300 text-sm sm:col-span-2" required>
                            <textarea name="description" placeholder="{{ __('Description (optional)') }}" rows="2" class="rounded-md border-gray-300 text-sm sm:col-span-2"></textarea>
                            <select name="assignee_id" class="rounded-md border-gray-300 text-sm">
                                <option value="">{{ __('Unassigned') }}</option>
                                @foreach ($workspace->members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                            <input type="date" name="due_date" class="rounded-md border-gray-300 text-sm">
                            <x-primary-button class="sm:col-span-2 w-fit">{{ __('Add Task') }}</x-primary-button>
                        </form>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach (['todo' => 'To Do', 'in_progress' => 'In Progress', 'done' => 'Done'] as $status => $label)
                            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-3 text-sm uppercase tracking-wide">{{ __($label) }}</h4>
                                <div class="space-y-3">
                                    @forelse (($tasksByStatus[$status] ?? collect()) as $task)
                                        <div class="border border-gray-100 rounded-md p-3 text-sm">
                                            <p class="font-medium text-gray-900">{{ $task->title }}</p>
                                            @if ($task->description)
                                                <p class="text-xs text-gray-500 mt-1">{{ $task->description }}</p>
                                            @endif
                                            @if ($task->assignee)
                                                <p class="text-xs text-gray-500 mt-1">{{ __('Assigned to') }} {{ $task->assignee->name }}</p>
                                            @endif
                                            @if ($task->due_date)
                                                <p class="text-xs text-gray-500">{{ __('Due') }} {{ $task->due_date->format('M j, Y') }}</p>
                                            @endif

                                            <div class="flex items-center justify-between mt-2">
                                                <form method="POST" action="{{ route('workspace-tasks.update', [$workspace, $task]) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" onchange="this.form.submit()" class="text-xs rounded-md border-gray-300">
                                                        <option value="todo" @selected($task->status === 'todo')>{{ __('To Do') }}</option>
                                                        <option value="in_progress" @selected($task->status === 'in_progress')>{{ __('In Progress') }}</option>
                                                        <option value="done" @selected($task->status === 'done')>{{ __('Done') }}</option>
                                                    </select>
                                                </form>
                                                <form method="POST" action="{{ route('workspace-tasks.destroy', [$workspace, $task]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs text-red-500">{{ __('Delete') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-gray-500">{{ __('No tasks.') }}</p>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-semibold text-gray-900 mb-3">{{ __('Members') }}</h3>
                        <div class="space-y-2">
                            @foreach ($workspace->members as $member)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-800">{{ $member->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $member->pivot->role }}</span>
                                </div>
                            @endforeach
                        </div>

                        @if ($workspace->owner_id === Auth::id())
                            <form method="POST" action="{{ route('workspace-members.store', $workspace) }}" class="flex gap-2 mt-4">
                                @csrf
                                <input type="email" name="email" placeholder="{{ __('Invite by email') }}" class="flex-1 rounded-md border-gray-300 text-sm">
                                <button type="submit" class="text-sm text-indigo-600">{{ __('Add') }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
