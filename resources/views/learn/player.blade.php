<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $course->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div class="lg:col-span-1 bg-white shadow-sm sm:rounded-lg p-4 space-y-1">
                    <h3 class="font-semibold text-gray-900 mb-2">{{ __('Lessons') }}</h3>
                    @foreach ($course->lessons as $courseLesson)
                        <a href="{{ route('learn.show', [$course, $courseLesson]) }}" class="block rounded-md px-3 py-2 text-sm {{ $lesson && $lesson->id === $courseLesson->id ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            {{ $courseLesson->position + 1 }}. {{ $courseLesson->title }}
                        </a>
                    @endforeach
                </div>

                <div class="lg:col-span-3 bg-white shadow-sm sm:rounded-lg p-6">
                    @if ($lesson)
                        <h3 class="text-lg font-semibold text-gray-900">{{ $lesson->title }}</h3>
                        @if ($lesson->video_url)
                            <div class="mt-4 aspect-video">
                                <iframe src="{{ $lesson->video_url }}" class="w-full h-full rounded-lg" allowfullscreen></iframe>
                            </div>
                        @endif
                        <div class="mt-4 text-gray-700 whitespace-pre-line">{{ $lesson->content }}</div>
                    @else
                        <p class="text-gray-600">{{ __('This course has no lessons yet.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
