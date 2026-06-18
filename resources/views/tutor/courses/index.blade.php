<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('My Courses') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ session('status') }}</div>
            @endif

            @auth
                @if (auth()->user()->tutor_status === 'pending')
                    <div class="bg-yellow-50 text-yellow-700 text-sm rounded-md p-3">{{ __('Your tutor application is pending admin approval.') }}</div>
                @elseif (auth()->user()->tutor_status === 'rejected')
                    <div class="bg-red-50 text-red-700 text-sm rounded-md p-3">{{ __('Your tutor application was rejected.') }}</div>
                @endif
            @endauth

            <div class="flex justify-end">
                <a href="{{ route('tutor.courses.create') }}">
                    <x-primary-button>{{ __('Create Course') }}</x-primary-button>
                </a>
            </div>

            @forelse ($courses as $course)
                <div class="bg-white shadow-sm sm:rounded-lg p-6 flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $course->title }}</p>
                        <p class="text-xs text-gray-500">{{ $course->lessons_count }} {{ __('lessons') }} &middot; {{ $course->enrollments_count }} {{ __('students') }} &middot; <span class="uppercase">{{ $course->status }}</span></p>
                    </div>
                    <div class="flex gap-3 items-center">
                        <a href="{{ route('tutor.courses.edit', $course) }}" class="text-sm text-indigo-600">{{ __('Manage') }}</a>
                        <form method="POST" action="{{ route('tutor.courses.destroy', $course) }}" onsubmit="return confirm('{{ __('Delete this course?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-500">{{ __('Delete') }}</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-600">{{ __("You haven't created any courses yet.") }}</div>
            @endforelse

            {{ $courses->links() }}
        </div>
    </div>
</x-app-layout>
