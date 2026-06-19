<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('New Ad') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('ads.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" value="{{ old('title') }}" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="body" :value="__('Description')" />
                        <textarea id="body" name="body" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('body') }}</textarea>
                        <x-input-error :messages="$errors->get('body')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="target_url" :value="__('Link URL')" />
                        <x-text-input id="target_url" name="target_url" value="{{ old('target_url') }}" class="mt-1 block w-full" placeholder="https://" />
                        <x-input-error :messages="$errors->get('target_url')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="image" :value="__('Image')" />
                        <input id="image" type="file" name="image" class="mt-1 block w-full text-sm">
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <p class="text-xs text-gray-500">{{ __('Ads are reviewed by an admin before they appear on the platform.') }}</p>

                    <x-primary-button>{{ __('Submit for Review') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
