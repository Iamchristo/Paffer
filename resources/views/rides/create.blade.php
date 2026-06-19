<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Offer a Ride') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('rides.store') }}" class="space-y-4">
                    @csrf

                    <input type="hidden" name="event_id" value="{{ old('event_id', $eventId) }}">

                    <div>
                        <x-input-label for="origin" :value="__('Origin')" />
                        <x-text-input id="origin" name="origin" type="text" class="mt-1 block w-full" :value="old('origin')" required />
                        <x-input-error :messages="$errors->get('origin')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="destination" :value="__('Destination')" />
                        <x-text-input id="destination" name="destination" type="text" class="mt-1 block w-full" :value="old('destination')" required />
                        <x-input-error :messages="$errors->get('destination')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="departure_at" :value="__('Departure time')" />
                        <x-text-input id="departure_at" name="departure_at" type="datetime-local" class="mt-1 block w-full" :value="old('departure_at')" required />
                        <x-input-error :messages="$errors->get('departure_at')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="seats_total" :value="__('Seats available')" />
                        <x-text-input id="seats_total" name="seats_total" type="number" min="1" class="mt-1 block w-full" :value="old('seats_total')" required />
                        <x-input-error :messages="$errors->get('seats_total')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="notes" :value="__('Notes (optional)')" />
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <x-primary-button>{{ __('Offer Ride') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
