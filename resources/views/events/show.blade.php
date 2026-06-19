<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $event->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-gray-600">{{ $event->starts_at->format('l, F j, Y \a\t g:i A') }}</p>
                        @if ($event->ends_at)
                            <p class="text-xs text-gray-500">{{ __('Ends') }} {{ $event->ends_at->format('l, F j, Y \a\t g:i A') }}</p>
                        @endif
                        @if ($event->location)
                            <p class="text-xs text-gray-500 mt-1">{{ __('Location') }}: {{ $event->location }}</p>
                        @endif
                        <p class="mt-4 text-gray-700 whitespace-pre-line">{{ $event->description }}</p>
                        <p class="text-xs text-gray-500 mt-3">
                            {{ __('Organized by') }} {{ $event->organizer->name }} ·
                            {{ $attendeesCount }} {{ __('attending') }}
                            @if ($event->capacity)
                                / {{ $event->capacity }}
                            @endif
                        </p>
                    </div>

                    @auth
                        @if ($isAttending)
                            @if ($event->organizer_id !== Auth::id())
                                <form method="POST" action="{{ route('events.rsvp.destroy', $event) }}">
                                    @csrf
                                    @method('DELETE')
                                    <x-secondary-button>{{ __('Cancel RSVP') }}</x-secondary-button>
                                </form>
                            @endif
                        @else
                            <form method="POST" action="{{ route('events.rsvp.store', $event) }}">
                                @csrf
                                <x-primary-button>{{ __('RSVP') }}</x-primary-button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">{{ __('Rides to this event') }}</h3>
                    @auth
                        <a href="{{ route('rides.create', ['event_id' => $event->id]) }}" class="text-xs text-indigo-600">{{ __('Offer a ride') }} &rarr;</a>
                    @endauth
                </div>

                <div class="mt-3 space-y-2">
                    @forelse ($event->rides as $ride)
                        <a href="{{ route('rides.show', $ride) }}" class="block text-sm bg-gray-50 rounded-md p-3 hover:bg-gray-100">
                            <span class="font-medium text-gray-800">{{ $ride->origin }} &rarr; {{ $ride->destination }}</span>
                            <span class="text-gray-500"> · {{ $ride->departure_at->format('M j, g:i A') }} · {{ __('by') }} {{ $ride->driver->name }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-gray-600">{{ __('No rides offered yet.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
