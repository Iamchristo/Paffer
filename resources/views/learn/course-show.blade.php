<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $course->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'enrolled')
                <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('You are now enrolled!') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if ($course->cover_path)
                    <img src="{{ Storage::url($course->cover_path) }}" class="rounded-lg max-h-80 object-cover mb-4 w-full" alt="">
                @endif
                <p class="text-sm text-gray-500">{{ __('Taught by') }} <a href="{{ route('people.show', $course->tutor) }}" class="text-indigo-600">{{ $course->tutor->name }}</a></p>
                <p class="mt-2 text-gray-700">{{ $course->description }}</p>
                <p class="mt-3 text-2xl font-semibold text-gray-900">{{ $course->isFree() ? __('Free') : '$'.number_format($course->price_cents / 100, 2) }}</p>
                <p class="text-sm text-gray-500">{{ $course->lessons->count() }} {{ __('lessons') }}</p>

                @auth
                    @if ($isEnrolled)
                        <a href="{{ route('learn.show', $course) }}" class="mt-4 inline-block">
                            <x-primary-button>{{ __('Continue Learning') }}</x-primary-button>
                        </a>
                    @else
                        <form method="POST" action="{{ route('enrollments.store', $course) }}" class="mt-4">
                            @csrf
                            <x-primary-button>{{ __('Enroll') }}</x-primary-button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="mt-4 inline-block text-indigo-600">{{ __('Log in to enroll') }}</a>
                @endauth
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-3">{{ __('Reviews') }}</h3>
                @forelse ($course->reviews as $review)
                    <div class="border-b border-gray-100 py-2 text-sm">
                        <span class="font-medium text-gray-800">{{ $review->user->name }}</span>
                        <span class="text-yellow-500">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                        <p class="text-gray-600">{{ $review->comment }}</p>
                    </div>
                @empty
                    <p class="text-gray-600 text-sm">{{ __('No reviews yet.') }}</p>
                @endforelse

                @auth
                    <form method="POST" action="{{ route('reviews.store-course', $course) }}" class="mt-4 space-y-2">
                        @csrf
                        <select name="rating" class="rounded-md border-gray-300 text-sm">
                            @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}">{{ $i }} {{ __('stars') }}</option>
                            @endfor
                        </select>
                        <textarea name="comment" rows="2" class="w-full rounded-md border-gray-300 text-sm" placeholder="{{ __('Share your experience...') }}"></textarea>
                        <x-primary-button>{{ __('Submit Review') }}</x-primary-button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
