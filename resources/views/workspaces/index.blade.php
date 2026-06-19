<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Workspaces') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex justify-end">
                <a href="{{ route('workspaces.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    {{ __('New Workspace') }}
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($workspaces as $workspace)
                    <a href="{{ route('workspaces.show', $workspace) }}" class="bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                        <h3 class="font-semibold text-gray-900">{{ $workspace->name }}</h3>
                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $workspace->description }}</p>
                        <p class="text-xs text-gray-500 mt-3">{{ $workspace->members_count }} {{ __('members') }} &middot; {{ $workspace->tasks_count }} {{ __('tasks') }}</p>
                    </a>
                @empty
                    <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-600 sm:col-span-2 lg:col-span-3">
                        {{ __("You're not part of any workspace yet.") }}
                    </div>
                @endforelse
            </div>

            {{ $workspaces->links() }}
        </div>
    </div>
</x-app-layout>
