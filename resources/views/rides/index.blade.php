<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Ride Board') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex gap-3">
                <form method="GET" class="bg-white shadow-sm sm:rounded-lg p-4 flex gap-3 flex-1">
                    <input type="text" name="q" value="{{ $search }}" placeholder="{{ __('Search rides by origin or destination...') }}" class="flex-1 rounded-md border-gray-300">
                    <x-primary-button>{{ __('Search') }}</x-primary-button>
                </form>
                @auth
                    <a href="{{ route('rides.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        {{ __('Offer a Ride') }}
                    </a>
                @endauth
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($rides as $ride)
                    <a href="{{ route('rides.show', $ride) }}" class="bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                        <h3 class="font-semibold text-gray-900">{{ $ride->origin }} &rarr; {{ $ride->destination }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $ride->departure_at->format('M j, Y g:i A') }}</p>
                        @if ($ride->event)
                            <p class="text-xs text-indigo-600 mt-1">{{ __('For') }}: {{ $ride->event->title }}</p>
                        @endif
                        <p class="text-xs text-gray-500 mt-3">{{ $ride->seatsAvailable() }} {{ __('seats left') }} · {{ __('by') }} {{ $ride->driver->name }}</p>
                    </a>
                @empty
                    <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-600 sm:col-span-2 lg:col-span-3">
                        {{ __('No rides offered yet.') }}
                    </div>
                @endforelse
            </div>

            {{ $rides->links() }}
        </div>
    </div>
</x-app-layout>
