<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Workspaces') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('admin._nav')

            @if (session('status'))
                <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('Action completed.') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="GET" class="flex gap-3 mb-4">
                    <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Search by name...') }}" class="flex-1 rounded-md border-gray-300 text-sm">
                    <x-primary-button>{{ __('Search') }}</x-primary-button>
                </form>

                @forelse ($workspaces as $workspace)
                    <div class="border-b border-gray-100 py-3 flex items-center justify-between text-sm">
                        <div>
                            <a href="{{ route('admin.workspaces.show', $workspace) }}" class="font-medium text-gray-900 hover:underline">{{ $workspace->name }}</a>
                            <p class="text-gray-500">{{ __('Owner') }}: {{ $workspace->owner->name }} &middot; {{ $workspace->members_count }} {{ __('members') }} &middot; {{ $workspace->tasks_count }} {{ __('tasks') }} &middot; {{ $workspace->status }}</p>
                        </div>
                        <div class="flex gap-3">
                            @if ($workspace->status === 'archived')
                                <form method="POST" action="{{ route('admin.workspaces.unarchive', $workspace) }}">
                                    @csrf
                                    <button type="submit" class="text-green-600">{{ __('Unarchive') }}</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.workspaces.archive', $workspace) }}">
                                    @csrf
                                    <button type="submit" class="text-amber-600">{{ __('Archive') }}</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.workspaces.destroy', $workspace) }}" onsubmit="return confirm('{{ __('Delete this workspace?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500">{{ __('Delete') }}</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600 text-sm">{{ __('No workspaces yet.') }}</p>
                @endforelse

                {{ $workspaces->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
