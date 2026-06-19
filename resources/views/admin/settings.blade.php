<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Admin Settings') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('admin._nav')

            @if (session('status') === 'settings-updated')
                <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('Mail settings updated.') }}</div>
            @endif

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
        </div>
    </div>
</x-app-layout>
