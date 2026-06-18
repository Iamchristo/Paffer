<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Create Course') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('tutor.courses.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="price_cents" :value="__('Price (cents, 0 = free)')" />
                        <x-text-input id="price_cents" name="price_cents" type="number" min="0" class="mt-1 block w-full" :value="old('price_cents', 0)" required />
                        <x-input-error :messages="$errors->get('price_cents')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="cover" :value="__('Cover Image')" />
                        <input id="cover" name="cover" type="file" class="mt-1 block w-full text-sm" />
                        <x-input-error :messages="$errors->get('cover')" class="mt-2" />
                    </div>

                    <x-primary-button>{{ __('Create Course') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
