<nav class="fixed inset-x-0 bottom-0 z-30 bg-white border-t border-gray-200 sm:hidden" style="padding-bottom: env(safe-area-inset-bottom)">
    <div class="grid grid-cols-4 text-center">
        <a href="{{ route('network.feed') }}" class="flex flex-col items-center justify-center py-2 text-xs {{ request()->routeIs('network.feed') ? 'text-indigo-600' : 'text-gray-500' }}">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 01-1 1h-3m-6 0h6m-6 0v-6a1 1 0 011-1h4a1 1 0 011 1v6" />
            </svg>
            {{ __('Feed') }}
        </a>

        <a href="{{ route('marketplace.index') }}" class="flex flex-col items-center justify-center py-2 text-xs {{ request()->routeIs('marketplace.*') ? 'text-indigo-600' : 'text-gray-500' }}">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293A1 1 0 005 17h12m-9 4a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z" />
            </svg>
            {{ __('Shop') }}
        </a>

        @auth
            <a href="{{ route('messages.index') }}" class="flex flex-col items-center justify-center py-2 text-xs {{ request()->routeIs('messages.*') ? 'text-indigo-600' : 'text-gray-500' }}">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                {{ __('Messages') }}
            </a>
        @else
            <a href="{{ route('learn.index') }}" class="flex flex-col items-center justify-center py-2 text-xs {{ request()->routeIs('learn.*') ? 'text-indigo-600' : 'text-gray-500' }}">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0112 21a12.083 12.083 0 01-6.16-10.422L12 14z" />
                </svg>
                {{ __('Learn') }}
            </a>
        @endauth

        @auth
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center py-2 text-xs {{ request()->routeIs('profile.*') ? 'text-indigo-600' : 'text-gray-500' }}">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ __('Profile') }}
            </a>
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center justify-center py-2 text-xs {{ request()->routeIs('login') ? 'text-indigo-600' : 'text-gray-500' }}">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                {{ __('Login') }}
            </a>
        @endauth
    </div>
</nav>
