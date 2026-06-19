<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Groups') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex gap-3">
                <form method="GET" class="bg-white shadow-sm sm:rounded-lg p-4 flex gap-3 flex-1">
                    <input type="text" name="q" value="{{ $search }}" placeholder="{{ __('Search groups by name or description...') }}" class="flex-1 rounded-md border-gray-300">
                    <x-primary-button>{{ __('Search') }}</x-primary-button>
                </form>
                @auth
                    <a href="{{ route('groups.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        {{ __('New Group') }}
                    </a>
                @endauth
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($groups as $group)
                    <a href="{{ route('groups.show', $group) }}" class="bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                        <h3 class="font-semibold text-gray-900">{{ $group->name }}</h3>
                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $group->description }}</p>
                        <p class="text-xs text-gray-500 mt-3">{{ $group->members_count }} {{ __('members') }}</p>
                    </a>
                @empty
                    <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-600 sm:col-span-2 lg:col-span-3">
                        {{ __('No groups yet. Be the first to start one.') }}
                    </div>
                @endforelse
            </div>

            {{ $groups->links() }}
        </div>
    </div>
</x-app-layout>
