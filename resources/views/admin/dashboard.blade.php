<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Admin Dashboard') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('admin._nav')

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['users'] }}</p>
                    <p class="text-sm text-gray-500">{{ __('Users') }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['sellers'] }}</p>
                    <p class="text-sm text-gray-500">{{ __('Sellers') }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['tutors'] }}</p>
                    <p class="text-sm text-gray-500">{{ __('Tutors') }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['stores'] }}</p>
                    <p class="text-sm text-gray-500">{{ __('Stores') }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['courses'] }}</p>
                    <p class="text-sm text-gray-500">{{ __('Courses') }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['orders'] }}</p>
                    <p class="text-sm text-gray-500">{{ __('Orders') }}</p>
                </div>
            </div>

            <a href="{{ route('admin.verification.index') }}" class="block bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                <h3 class="font-semibold text-gray-900">{{ __('Pending Verification') }}</h3>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $stats['pending_sellers'] }} {{ __('sellers') }} &middot;
                    {{ $stats['pending_tutors'] }} {{ __('tutors') }} &middot;
                    {{ $stats['pending_courses'] }} {{ __('courses') }}
                </p>
            </a>
        </div>
    </div>
</x-app-layout>
