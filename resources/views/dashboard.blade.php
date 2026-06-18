<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Welcome back, :name', ['name' => Auth::user()->name]) }}</h3>
                <p class="mt-1 text-sm text-gray-600">{{ __('Here is what is happening across your PAFFAR network.') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('network.feed') }}" class="bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <h4 class="font-semibold text-gray-900">{{ __('Network Feed') }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ __('See updates from your connections.') }}</p>
                </a>
                <a href="{{ route('marketplace.index') }}" class="bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <h4 class="font-semibold text-gray-900">{{ __('Marketplace') }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ __('Browse stores and products from verified sellers.') }}</p>
                </a>
                <a href="{{ route('learn.index') }}" class="bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <h4 class="font-semibold text-gray-900">{{ __('Learn') }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ __('Grow your skills with business courses.') }}</p>
                </a>

                @if (Auth::user()->isApprovedSeller())
                    <a href="{{ route('seller.dashboard') }}" class="bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                        <h4 class="font-semibold text-gray-900">{{ __('My Store') }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ __('Manage products and orders.') }}</p>
                    </a>
                @elseif (Auth::user()->seller_status === 'pending')
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900">{{ __('Seller Application') }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ __('Your store is pending admin review.') }}</p>
                    </div>
                @else
                    <a href="{{ route('seller.apply') }}" class="bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                        <h4 class="font-semibold text-gray-900">{{ __('Become a Seller') }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ __('Open your own store on PAFFAR.') }}</p>
                    </a>
                @endif

                @if (Auth::user()->isApprovedTutor())
                    <a href="{{ route('tutor.courses.index') }}" class="bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                        <h4 class="font-semibold text-gray-900">{{ __('My Courses') }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ __('Create and manage your courses.') }}</p>
                    </a>
                @elseif (Auth::user()->tutor_status === 'pending')
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900">{{ __('Tutor Application') }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ __('Your tutor application is pending admin review.') }}</p>
                    </div>
                @else
                    <a href="{{ route('tutor.apply') }}" class="bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                        <h4 class="font-semibold text-gray-900">{{ __('Become a Tutor') }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ __('Share your expertise and sell courses.') }}</p>
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
