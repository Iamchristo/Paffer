<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Settings') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                <div class="h-28 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                <div class="p-6 -mt-12 flex items-end gap-4">
                    <x-avatar :user="$user" size="24" class="ring-4 ring-white text-2xl shrink-0" />
                    <div class="pb-1">
                        <h1 class="text-lg font-bold text-gray-900">{{ $user->name }}</h1>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <nav class="hidden lg:block space-y-1">
                    <a href="#account" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100"><x-icon name="user-circle" class="h-4 w-4" /> {{ __('Account') }}</a>
                    <a href="#profile-details" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100"><x-icon name="briefcase" class="h-4 w-4" /> {{ __('Profile Details') }}</a>
                    <a href="#password" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100"><x-icon name="shield-check" class="h-4 w-4" /> {{ __('Password') }}</a>
                    <a href="#danger" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50"><x-icon name="x-mark" class="h-4 w-4" /> {{ __('Delete Account') }}</a>
                </nav>

                <div class="lg:col-span-3 space-y-6">
                    <div id="account" class="p-6 bg-white shadow-sm rounded-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                    <div id="profile-details" class="p-6 bg-white shadow-sm rounded-xl">
                        @include('profile.partials.update-paffar-profile-form')
                    </div>
                    <div id="password" class="p-6 bg-white shadow-sm rounded-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                    <div id="danger" class="p-6 bg-white shadow-sm rounded-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
