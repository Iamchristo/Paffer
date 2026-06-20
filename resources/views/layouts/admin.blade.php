<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('Admin') }} - {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @include('partials.theme-css')
    </head>
    <body class="font-sans antialiased">
        @php
            $adminNavGroups = [
                __('Overview') => [
                    ['label' => __('Dashboard'), 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard'],
                ],
                __('Review & Moderation') => [
                    ['label' => __('Verification'), 'route' => 'admin.verification.index', 'pattern' => 'admin.verification.*'],
                    ['label' => __('Moderation'), 'route' => 'admin.moderation.index', 'pattern' => 'admin.moderation.*'],
                    ['label' => __('Orders'), 'route' => 'admin.orders.index', 'pattern' => 'admin.orders.*'],
                    ['label' => __('Ads'), 'route' => 'admin.ads.index', 'pattern' => 'admin.ads.*'],
                ],
                __('People') => [
                    ['label' => __('Users'), 'route' => 'admin.users.index', 'pattern' => 'admin.users.*'],
                ],
                __('Phase 2') => [
                    ['label' => __('Wallets'), 'route' => 'admin.wallets.index', 'pattern' => 'admin.wallets.*'],
                    ['label' => __('Workspaces'), 'route' => 'admin.workspaces.index', 'pattern' => 'admin.workspaces.*'],
                    ['label' => __('Recommendations'), 'route' => 'admin.recommendations.edit', 'pattern' => 'admin.recommendations.*'],
                    ['label' => __('Mobile App'), 'route' => 'admin.mobile.edit', 'pattern' => 'admin.mobile.*'],
                ],
                __('Communication') => [
                    ['label' => __('Announcements'), 'route' => 'admin.announcements.index', 'pattern' => 'admin.announcements.*'],
                ],
                __('Configuration') => [
                    ['label' => __('Settings'), 'route' => 'admin.settings.edit', 'pattern' => 'admin.settings.*'],
                ],
            ];
        @endphp

        <div x-data="{ sidebarOpen: false }" class="min-h-screen flex bg-gray-100">
            <!-- Mobile sidebar overlay -->
            <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden" @click="sidebarOpen = false"></div>

            <!-- Sidebar -->
            <aside
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed inset-y-0 left-0 z-40 w-64 bg-gray-900 text-gray-300 transform transition-transform duration-150 lg:static lg:translate-x-0 overflow-y-auto"
            >
                <div class="flex items-center gap-2 px-5 h-16 border-b border-gray-800">
                    <x-application-logo class="block h-7 w-auto fill-current text-white" />
                    <span class="font-semibold text-white">{{ __('Admin') }}</span>
                </div>

                <nav class="px-3 py-4 space-y-6">
                    @foreach ($adminNavGroups as $group => $items)
                        <div>
                            <p class="px-3 text-xs font-semibold uppercase tracking-wider text-gray-500">{{ $group }}</p>
                            <div class="mt-1 space-y-0.5">
                                @foreach ($items as $item)
                                    <a
                                        href="{{ route($item['route']) }}"
                                        class="block rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs($item['pattern']) ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                                    >
                                        {{ $item['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </nav>
            </aside>

            <!-- Content -->
            <div class="flex-1 min-w-0 flex flex-col">
                <header class="bg-white shadow-sm">
                    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-3">
                            <button @click="sidebarOpen = ! sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                {{ $header ?? __('Admin') }}
                            </h2>
                        </div>

                        <div class="flex items-center gap-4 text-sm">
                            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">{{ __('View site') }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-gray-500 hover:text-gray-700">{{ __('Log out') }}</button>
                            </form>
                        </div>
                    </div>
                </header>

                <main class="flex-1 py-8">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
