<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Admin Settings') }}</h2>
    </x-slot>

    @if (session('status') === 'settings-updated')
        <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('Mail settings updated.') }}</div>
    @endif

    @if (session('status') === 'theme-updated')
        <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('Appearance updated.') }}</div>
    @endif

    @if (session('status') === 'guest-feed-updated')
        <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('Guest feed visibility updated.') }}</div>
    @endif

    <div class="bg-white shadow-sm sm:rounded-lg p-6" x-data="{ siteTheme: '{{ old('site_theme', $theme['site_theme']) }}' }">
        <h3 class="font-semibold text-gray-900 mb-4">{{ __('Appearance') }}</h3>

        <form method="POST" action="{{ route('admin.settings.theme.update') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="site_theme" :value="__('Site theme')" />
                <select id="site_theme" name="site_theme" x-model="siteTheme" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="default" @selected($theme['site_theme'] === 'default')>{{ __('Default') }}</option>
                    <option value="paffar" @selected($theme['site_theme'] === 'paffar')>{{ __('PAFFAR Glass') }}</option>
                    <option value="custom" @selected($theme['site_theme'] === 'custom')>{{ __('Custom CSS') }}</option>
                </select>
                <x-input-error :messages="$errors->get('site_theme')" class="mt-2" />
                <p class="text-xs text-gray-500 mt-1">{{ __('PAFFAR Glass applies a purple/pink glassmorphism look across the site. Custom CSS lets you paste or upload your own overrides.') }}</p>
            </div>

            <div x-show="siteTheme === 'custom'" x-cloak class="space-y-4">
                <div>
                    <x-input-label for="theme_custom_css" :value="__('Custom CSS')" />
                    <textarea id="theme_custom_css" name="theme_custom_css" rows="8" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm font-mono text-sm" placeholder=":root { --paffar-primary: #5B3E9A; }">{{ old('theme_custom_css', $theme['theme_custom_css']) }}</textarea>
                    <x-input-error :messages="$errors->get('theme_custom_css')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="theme_css_file" :value="__('Or upload a .css file')" />
                    <input id="theme_css_file" name="theme_css_file" type="file" accept=".css,text/css" class="mt-1 block w-full text-sm">
                    <x-input-error :messages="$errors->get('theme_css_file')" class="mt-2" />
                    <p class="text-xs text-gray-500 mt-1">{{ __('Uploading a file replaces the text above (max 200KB).') }}</p>
                </div>
            </div>

            <x-primary-button>{{ __('Save Appearance') }}</x-primary-button>
        </form>
    </div>

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-4">{{ __('Home Page & Guest Access') }}</h3>

        <form method="POST" action="{{ route('admin.settings.guest-feed.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="flex items-start gap-3">
                <input id="guest_feed_enabled" name="guest_feed_enabled" type="checkbox" value="1" class="mt-1 rounded border-gray-300" @checked(old('guest_feed_enabled', $guestFeedEnabled))>
                <div>
                    <x-input-label for="guest_feed_enabled" :value="__('Allow guests to view the feed')" />
                    <p class="text-xs text-gray-500 mt-1">{{ __('When enabled, visitors who are not logged in can browse the feed from the home page. When disabled, the home page shows the login page to guests. Logged-in users always see the feed.') }}</p>
                </div>
            </div>

            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </form>
    </div>

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-4">{{ __('Email / SMTP Settings') }}</h3>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="mailer" :value="__('Mailer')" />
                <select id="mailer" name="mailer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @foreach (['log' => 'Log (no real emails sent)', 'smtp' => 'SMTP', 'sendmail' => 'Sendmail'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('mailer', $values['mailer']) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('mailer')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="host" :value="__('SMTP Host')" />
                <x-text-input id="host" name="host" type="text" class="mt-1 block w-full" :value="old('host', $values['host'])" />
                <x-input-error :messages="$errors->get('host')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="port" :value="__('SMTP Port')" />
                <x-text-input id="port" name="port" type="text" class="mt-1 block w-full" :value="old('port', $values['port'])" />
                <x-input-error :messages="$errors->get('port')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="username" :value="__('SMTP Username')" />
                <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $values['username'])" />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('SMTP Password')" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" placeholder="{{ __('Leave blank to keep current password') }}" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="encryption" :value="__('Encryption')" />
                <select id="encryption" name="encryption" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @foreach (['' => 'None', 'tls' => 'TLS', 'ssl' => 'SSL'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('encryption', $values['encryption']) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('encryption')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="from_address" :value="__('From Address')" />
                <x-text-input id="from_address" name="from_address" type="email" class="mt-1 block w-full" :value="old('from_address', $values['from_address'])" required />
                <x-input-error :messages="$errors->get('from_address')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="from_name" :value="__('From Name')" />
                <x-text-input id="from_name" name="from_name" type="text" class="mt-1 block w-full" :value="old('from_name', $values['from_name'])" required />
                <x-input-error :messages="$errors->get('from_name')" class="mt-2" />
            </div>

            <x-primary-button>{{ __('Save Settings') }}</x-primary-button>
        </form>
    </div>
</x-admin-layout>
