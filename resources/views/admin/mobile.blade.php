<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Mobile App') }}</h2>
    </x-slot>

    @if (session('status') === 'mobile-settings-updated')
        <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('Mobile app settings updated.') }}</div>
    @endif

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-2">{{ __('Progressive Web App') }}</h3>
        <p class="text-xs text-gray-500 mb-4">{{ __('PAFFAR ships as an installable Progressive Web App rather than a separate native codebase — members can add it to their home screen on iOS/Android and it works offline for already-visited pages.') }}</p>

        <form method="POST" action="{{ route('admin.mobile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-2">
                <input type="checkbox" id="pwa_enabled" name="pwa_enabled" value="1" @checked(old('pwa_enabled', $values['pwa_enabled'])) class="rounded border-gray-300">
                <x-input-label for="pwa_enabled" :value="__('Enable install / home-screen prompt')" />
            </div>

            <div>
                <x-input-label for="pwa_short_name" :value="__('App short name (shown under the home-screen icon)')" />
                <x-text-input id="pwa_short_name" name="pwa_short_name" type="text" class="mt-1 block w-full" :value="old('pwa_short_name', $values['pwa_short_name'])" maxlength="30" required />
                <x-input-error :messages="$errors->get('pwa_short_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="pwa_theme_color" :value="__('Theme color')" />
                <input type="color" id="pwa_theme_color" name="pwa_theme_color" value="{{ old('pwa_theme_color', $values['pwa_theme_color']) }}" class="mt-1 h-10 w-20 rounded-md border-gray-300">
                <x-input-error :messages="$errors->get('pwa_theme_color')" class="mt-2" />
            </div>

            <x-primary-button>{{ __('Save Settings') }}</x-primary-button>
        </form>
    </div>
</x-admin-layout>
