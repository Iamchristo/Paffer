<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('My Courses') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @forelse ($enrollments as $enrollment)
                <a href="{{ route('learn.show', $enrollment->course) }}" class="bg-white shadow-sm sm:rounded-lg p-6 flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $enrollment->course->title }}</p>
                        <p class="text-xs text-gray-500">{{ __('By') }} {{ $enrollment->course->tutor->name }}</p>
                    </div>
                    <span class="text-xs uppercase tracking-wide text-gray-500">{{ $enrollment->completed_at ? __('Completed') : __('In Progress') }}</span>
                </a>
            @empty
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-600">{{ __("You haven't enrolled in any courses yet.") }}</div>
            @endforelse

            {{ $enrollments->links() }}
        </div>
    </div>
</x-app-layout>
