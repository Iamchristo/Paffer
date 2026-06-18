<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Become a Tutor') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-sm text-gray-600 mb-4">{{ __('Tell us about your expertise. An admin will review your application before you can publish courses.') }}</p>

                <form method="POST" action="{{ route('tutor.apply.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="expertise" :value="__('Your Expertise')" />
                        <textarea id="expertise" name="expertise" rows="4" class="mt-1 block w-full rounded-md border-gray-300" autofocus>{{ old('expertise') }}</textarea>
                        <x-input-error :messages="$errors->get('expertise')" class="mt-2" />
                    </div>

                    <x-primary-button>{{ __('Submit Application') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
