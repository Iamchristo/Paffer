<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $ride->origin }} &rarr; {{ $ride->destination }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-sm text-gray-600">{{ $ride->departure_at->format('l, F j, Y \a\t g:i A') }}</p>
                @if ($ride->event)
                    <p class="text-xs text-indigo-600 mt-1">
                        {{ __('For event') }}: <a href="{{ route('events.show', $ride->event) }}">{{ $ride->event->title }}</a>
                    </p>
                @endif
                @if ($ride->notes)
                    <p class="mt-4 text-gray-700 whitespace-pre-line">{{ $ride->notes }}</p>
                @endif
                <p class="text-xs text-gray-500 mt-3">
                    {{ __('Offered by') }} {{ $ride->driver->name }} ·
                    {{ $ride->seatsAvailable() }} / {{ $ride->seats_total }} {{ __('seats left') }}
                </p>

                @auth
                    @if ($ride->driver_id !== Auth::id())
                        @if ($hasBooking)
                            <form method="POST" action="{{ route('rides.bookings.destroy', $ride) }}" class="mt-4">
                                @csrf
                                @method('DELETE')
                                <x-secondary-button>{{ __('Cancel Booking') }}</x-secondary-button>
                            </form>
                        @elseif ($ride->seatsAvailable() > 0)
                            <form method="POST" action="{{ route('rides.bookings.store', $ride) }}" class="mt-4 flex items-end gap-3">
                                @csrf
                                <div>
                                    <x-input-label for="seats_booked" :value="__('Seats to book')" />
                                    <x-text-input id="seats_booked" name="seats_booked" type="number" min="1" max="{{ $ride->seatsAvailable() }}" value="1" class="mt-1 block w-24" />
                                </div>
                                <x-primary-button>{{ __('Book') }}</x-primary-button>
                            </form>
                        @else
                            <p class="mt-4 text-sm text-red-600">{{ __('This ride is fully booked.') }}</p>
                        @endif
                    @endif
                @endauth
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-3">{{ __('Passengers') }}</h3>
                <div class="space-y-2">
                    @forelse ($ride->bookings as $booking)
                        <div class="text-sm bg-gray-50 rounded-md p-2">
                            <span class="font-medium text-gray-800">{{ $booking->passenger->name }}</span>
                            <span class="text-gray-600">{{ $booking->seats_booked }} {{ __('seat(s)') }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600">{{ __('No passengers booked yet.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
