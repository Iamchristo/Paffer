<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Verification') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-3">{{ __('Pending Sellers') }}</h3>
                @forelse ($pendingSellers as $seller)
                    <div class="border-b border-gray-100 py-3 flex items-center justify-between text-sm">
                        <div>
                            <p class="font-medium text-gray-900">{{ $seller->name }}</p>
                            <p class="text-gray-500">{{ $seller->store?->name }}</p>
                        </div>
                        <div class="flex gap-3">
                            <form method="POST" action="{{ route('admin.verification.sellers.approve', $seller) }}">
                                @csrf
                                <button type="submit" class="text-green-600">{{ __('Approve') }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.verification.sellers.reject', $seller) }}">
                                @csrf
                                <button type="submit" class="text-red-500">{{ __('Reject') }}</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600 text-sm">{{ __('No pending sellers.') }}</p>
                @endforelse
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-3">{{ __('Pending Tutors') }}</h3>
                @forelse ($pendingTutors as $tutor)
                    <div class="border-b border-gray-100 py-3 flex items-center justify-between text-sm">
                        <p class="font-medium text-gray-900">{{ $tutor->name }}</p>
                        <div class="flex gap-3">
                            <form method="POST" action="{{ route('admin.verification.tutors.approve', $tutor) }}">
                                @csrf
                                <button type="submit" class="text-green-600">{{ __('Approve') }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.verification.tutors.reject', $tutor) }}">
                                @csrf
                                <button type="submit" class="text-red-500">{{ __('Reject') }}</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600 text-sm">{{ __('No pending tutors.') }}</p>
                @endforelse
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-3">{{ __('Pending Courses') }}</h3>
                @forelse ($pendingCourses as $course)
                    <div class="border-b border-gray-100 py-3 flex items-center justify-between text-sm">
                        <div>
                            <p class="font-medium text-gray-900">{{ $course->title }}</p>
                            <p class="text-gray-500">{{ __('By') }} {{ $course->tutor->name }}</p>
                        </div>
                        <div class="flex gap-3">
                            <form method="POST" action="{{ route('admin.verification.courses.approve', $course) }}">
                                @csrf
                                <button type="submit" class="text-green-600">{{ __('Approve') }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.verification.courses.reject', $course) }}">
                                @csrf
                                <button type="submit" class="text-red-500">{{ __('Reject') }}</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600 text-sm">{{ __('No pending courses.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
