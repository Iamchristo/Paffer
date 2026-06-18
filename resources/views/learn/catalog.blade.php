<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Learn') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="GET" class="bg-white shadow-sm sm:rounded-lg p-4">
                <input type="text" name="q" value="{{ $search }}" placeholder="{{ __('Search courses...') }}" class="w-full rounded-md border-gray-300">
            </form>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($courses as $course)
                    <a href="{{ route('learn.show-catalog', $course) }}" class="bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                        @if ($course->cover_path)
                            <img src="{{ Storage::url($course->cover_path) }}" class="rounded-lg h-32 w-full object-cover mb-3" alt="">
                        @endif
                        <h4 class="font-semibold text-gray-900">{{ $course->title }}</h4>
                        <p class="text-sm text-gray-500 mt-1">{{ __('By') }} {{ $course->tutor->name }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $course->lessons_count }} {{ __('lessons') }}</p>
                        <p class="mt-2 font-medium text-gray-900">{{ $course->isFree() ? __('Free') : '$'.number_format($course->price_cents / 100, 2) }}</p>
                    </a>
                @empty
                    <p class="text-gray-600">{{ __('No courses found.') }}</p>
                @endforelse
            </div>

            {{ $courses->links() }}
        </div>
    </div>
</x-app-layout>
