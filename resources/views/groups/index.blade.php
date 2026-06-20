<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Groups') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <form method="GET" class="bg-white shadow-sm rounded-xl p-2 flex gap-2 flex-1">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <x-icon name="search" class="h-4 w-4" />
                        </span>
                        <input type="text" name="q" value="{{ $search }}" placeholder="{{ __('Search groups by name or description...') }}" class="w-full rounded-lg border-0 bg-gray-50 pl-9 focus:ring-indigo-500">
                    </div>
                    <x-primary-button>{{ __('Search') }}</x-primary-button>
                </form>
                @auth
                    <a href="{{ route('groups.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-indigo-700 shrink-0">
                        <x-icon name="plus" class="h-4 w-4" />
                        {{ __('New Group') }}
                    </a>
                @endauth
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($groups as $group)
                    @php $hue = crc32($group->name) % 5; @endphp
                    <a href="{{ route('groups.show', $group) }}" class="group bg-white shadow-sm rounded-xl overflow-hidden hover:shadow-lg transition">
                        <div class="h-16 bg-gradient-to-r {{ ['from-indigo-400 to-purple-500', 'from-emerald-400 to-teal-500', 'from-orange-400 to-pink-500', 'from-sky-400 to-blue-500', 'from-rose-400 to-fuchsia-500'][$hue] }}"></div>
                        <div class="p-5 -mt-8">
                            <div class="h-14 w-14 rounded-xl bg-white shadow flex items-center justify-center text-indigo-600 border border-gray-100">
                                <x-icon name="user-group" class="h-7 w-7" />
                            </div>
                            <h3 class="font-semibold text-gray-900 mt-3 group-hover:text-indigo-600">{{ $group->name }}</h3>
                            <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $group->description }}</p>
                            <p class="text-xs text-gray-500 mt-3 inline-flex items-center gap-1">
                                <x-icon name="users" class="h-3.5 w-3.5" />
                                {{ $group->members_count }} {{ __('members') }}
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="bg-white shadow-sm rounded-xl p-10 text-center text-gray-600 sm:col-span-2 lg:col-span-3">
                        {{ __('No groups yet. Be the first to start one.') }}
                    </div>
                @endforelse
            </div>

            {{ $groups->links() }}
        </div>
    </div>
</x-app-layout>
