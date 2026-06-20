<div x-data="{ drawerOpen: false }">
<nav class="bg-white border-b border-gray-100 sticky top-0 z-40">
    <div class="px-3 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-3">
            <!-- Menu button -->
            <button @click="drawerOpen = true" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none">
                <x-icon name="menu" />
            </button>

            <!-- Search -->
            <form action="{{ route('people.index') }}" method="GET" class="flex-1 max-w-md">
                <label for="nav-search" class="sr-only">{{ __('Search people') }}</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <x-icon name="search" class="h-4 w-4" />
                    </span>
                    <input
                        id="nav-search"
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="{{ __('Search people...') }}"
                        class="w-full rounded-full border-gray-200 bg-gray-100 pl-9 pr-3 py-2 text-sm focus:bg-white focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>
            </form>

            <!-- Right icons -->
            <div class="flex items-center gap-1 shrink-0">
                @auth
                    <a href="{{ route('messages.index') }}" class="relative inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100" title="{{ __('Messages') }}">
                        <x-icon name="chat" />
                        @if (($unreadMessagesCount = Auth::user()->unreadMessagesCount()) > 0)
                            <span class="absolute top-1 right-1 inline-flex h-4 w-4 items-center justify-center rounded-full bg-indigo-600 text-[10px] font-semibold text-white">{{ $unreadMessagesCount }}</span>
                        @endif
                    </a>
                    <button type="button" class="relative inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100" title="{{ __('Notifications') }}">
                        <x-icon name="bell" />
                    </button>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">{{ __('Log in') }}</a>
                    <a href="{{ route('register') }}" class="px-3 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-800">{{ __('Register') }}</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

    <!-- Drawer overlay -->
    <div x-show="drawerOpen" x-cloak x-transition.opacity @click="drawerOpen = false" class="fixed inset-0 z-40 bg-gray-900/50"></div>

    <!-- Slide-out drawer -->
    <aside
        x-show="drawerOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-50 w-1/2 min-w-[260px] max-w-sm bg-white shadow-xl overflow-y-auto"
        @click.outside="drawerOpen = false"
    >
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-100">
            @auth
                <div class="flex items-center gap-2 min-w-0">
                    <x-avatar :user="Auth::user()" size="9" />
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            @else
                <span class="font-semibold text-gray-800">{{ __('Menu') }}</span>
            @endauth
            <button @click="drawerOpen = false" class="p-1.5 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                <x-icon name="x-mark" class="h-5 w-5" />
            </button>
        </div>

        <nav class="px-3 py-4 space-y-6">
            <div>
                <p class="px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">{{ __('Browse') }}</p>
                <div class="mt-1 space-y-0.5">
                    <x-drawer-link :href="route('network.feed')" icon="home" :active="request()->routeIs('network.feed')">{{ __('Feed') }}</x-drawer-link>
                    <x-drawer-link :href="route('people.index')" icon="users" :active="request()->routeIs('people.*')">{{ __('People') }}</x-drawer-link>
                    <x-drawer-link :href="route('groups.index')" icon="user-group" :active="request()->routeIs('groups.*')">{{ __('Groups') }}</x-drawer-link>
                    <x-drawer-link :href="route('events.index')" icon="calendar" :active="request()->routeIs('events.*')">{{ __('Events') }}</x-drawer-link>
                    <x-drawer-link :href="route('rides.index')" icon="truck" :active="request()->routeIs('rides.*')">{{ __('Rides') }}</x-drawer-link>
                    <x-drawer-link :href="route('marketplace.index')" icon="shopping-bag" :active="request()->routeIs('marketplace.*')">{{ __('Marketplace') }}</x-drawer-link>
                    <x-drawer-link :href="route('learn.index')" icon="academic-cap" :active="request()->routeIs('learn.*')">{{ __('Learn') }}</x-drawer-link>
                </div>
            </div>

            @auth
                <div>
                    <p class="px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">{{ __('Workspace') }}</p>
                    <div class="mt-1 space-y-0.5">
                        <x-drawer-link :href="route('messages.index')" icon="chat" :active="request()->routeIs('messages.*')" :badge="($c = Auth::user()->unreadMessagesCount()) > 0 ? $c : null">{{ __('Messages') }}</x-drawer-link>
                        <x-drawer-link :href="route('workspaces.index')" icon="briefcase" :active="request()->routeIs('workspaces.*')">{{ __('Workspaces') }}</x-drawer-link>
                        @if (Auth::user()->isApprovedSeller())
                            <x-drawer-link :href="route('seller.dashboard')" icon="building-storefront" :active="request()->routeIs('seller.*')">{{ __('My Store') }}</x-drawer-link>
                        @endif
                        @if (Auth::user()->isApprovedTutor())
                            <x-drawer-link :href="route('tutor.courses.index')" icon="academic-cap" :active="request()->routeIs('tutor.*')">{{ __('My Courses') }}</x-drawer-link>
                        @endif
                        @if (Auth::user()->isAdmin())
                            <x-drawer-link :href="route('admin.dashboard')" icon="shield-check" :active="request()->routeIs('admin.*')">{{ __('Admin') }}</x-drawer-link>
                        @endif
                    </div>
                </div>

                <div>
                    <p class="px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">{{ __('Account') }}</p>
                    <div class="mt-1 space-y-0.5">
                        <x-drawer-link :href="route('profile.edit')" icon="user-circle" :active="request()->routeIs('profile.*')">{{ __('Profile') }}</x-drawer-link>
                        <x-drawer-link :href="route('wallet.index')" icon="wallet" :active="request()->routeIs('wallet.*')">{{ __('Wallet') }}</x-drawer-link>
                        <x-drawer-link :href="route('cart.index')" icon="shopping-cart" :active="request()->routeIs('cart.*')">{{ __('Cart') }}</x-drawer-link>
                        <x-drawer-link :href="route('orders.index')" icon="clipboard" :active="request()->routeIs('orders.*')">{{ __('My Orders') }}</x-drawer-link>
                        <x-drawer-link :href="route('enrollments.index')" icon="academic-cap" :active="request()->routeIs('enrollments.*')">{{ __('My Enrollments') }}</x-drawer-link>
                        <x-drawer-link :href="route('ads.index')" icon="megaphone" :active="request()->routeIs('ads.*')">{{ __('My Ads') }}</x-drawer-link>
                        @unless (Auth::user()->is_seller)
                            <x-drawer-link :href="route('seller.apply')" icon="star">{{ __('Become a Seller') }}</x-drawer-link>
                        @endunless
                        @unless (Auth::user()->is_tutor)
                            <x-drawer-link :href="route('tutor.apply')" icon="star">{{ __('Become a Tutor') }}</x-drawer-link>
                        @endunless
                    </div>
                </div>

                <div class="px-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 text-sm font-medium">
                            <x-icon name="logout" class="h-5 w-5" />
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            @else
                <div>
                    <div class="mt-1 space-y-0.5">
                        <x-drawer-link :href="route('login')" icon="user-circle">{{ __('Log in') }}</x-drawer-link>
                        <x-drawer-link :href="route('register')" icon="star">{{ __('Register') }}</x-drawer-link>
                    </div>
                </div>
            @endauth
        </nav>
    </aside>
</div>
