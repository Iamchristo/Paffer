<nav class="fixed inset-x-0 bottom-0 z-30 bg-white border-t border-gray-200 sm:hidden" style="padding-bottom: env(safe-area-inset-bottom)">
    <div class="relative grid grid-cols-4 items-center text-center">
        <a href="{{ route('network.feed') }}" class="flex flex-col items-center justify-center py-2 text-xs {{ request()->routeIs('network.feed') ? 'text-indigo-600' : 'text-gray-500' }}">
            <x-icon name="home" class="h-6 w-6" />
            {{ __('Feed') }}
        </a>

        <a href="{{ route('marketplace.index') }}" class="flex flex-col items-center justify-center py-2 text-xs {{ request()->routeIs('marketplace.*') ? 'text-indigo-600' : 'text-gray-500' }}">
            <x-icon name="building-storefront" class="h-6 w-6" />
            {{ __('Stores') }}
        </a>

        <div class="flex flex-col items-center justify-center">
            <a href="{{ route('network.feed') }}#post-composer" class="flex items-center justify-center h-12 w-12 -mt-6 rounded-full bg-indigo-600 text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-700">
                <x-icon name="plus" class="h-7 w-7" />
            </a>
            <span class="mt-0.5 text-xs text-gray-500">{{ __('Post') }}</span>
        </div>

        @auth
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center py-2 text-xs {{ request()->routeIs('profile.*') ? 'text-indigo-600' : 'text-gray-500' }}">
                <x-icon name="user-circle" class="h-6 w-6" />
                {{ __('Profile') }}
            </a>
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center justify-center py-2 text-xs {{ request()->routeIs('login') ? 'text-indigo-600' : 'text-gray-500' }}">
                <x-icon name="user-circle" class="h-6 w-6" />
                {{ __('Login') }}
            </a>
        @endauth
    </div>
</nav>
