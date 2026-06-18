<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Browse Entrepreneurs') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="GET" class="bg-white shadow-sm sm:rounded-lg p-4 flex gap-3">
                <input type="text" name="q" value="{{ $search }}" placeholder="{{ __('Search by name, industry, headline...') }}" class="flex-1 rounded-md border-gray-300">
                <x-primary-button>{{ __('Search') }}</x-primary-button>
            </form>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($people as $person)
                    <a href="{{ route('people.show', $person) }}" class="bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                        <h3 class="font-semibold text-gray-900">{{ $person->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $person->profile?->headline ?? __('PAFFAR member') }}</p>
                        @if ($person->profile?->industry)
                            <p class="text-xs text-gray-500 mt-1">{{ $person->profile->industry }}</p>
                        @endif
                    </a>
                @endforeach
            </div>

            {{ $people->links() }}
        </div>
    </div>
</x-app-layout>
