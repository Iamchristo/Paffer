<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $store->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900">{{ $store->name }}</h3>
                <p class="text-sm text-gray-600 mt-1">{{ $store->description }}</p>
                <p class="text-xs text-gray-500 mt-2">{{ __('Run by') }} <a href="{{ route('people.show', $store->user) }}" class="text-indigo-600">{{ $store->user->name }}</a></p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($store->products as $product)
                    <a href="{{ route('marketplace.product', $product) }}" class="bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                        <h4 class="font-semibold text-gray-900">{{ $product->name }}</h4>
                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $product->description }}</p>
                        <p class="mt-2 font-medium text-gray-900">${{ number_format($product->price_cents / 100, 2) }}</p>
                    </a>
                @empty
                    <p class="text-gray-600">{{ __('No products listed yet.') }}</p>
                @endforelse
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-3">{{ __('Reviews') }}</h3>
                @forelse ($store->reviews as $review)
                    <div class="border-b border-gray-100 py-2 text-sm">
                        <span class="font-medium text-gray-800">{{ $review->user->name }}</span>
                        <span class="text-yellow-500">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                        <p class="text-gray-600">{{ $review->comment }}</p>
                    </div>
                @empty
                    <p class="text-gray-600 text-sm">{{ __('No reviews yet.') }}</p>
                @endforelse

                @auth
                    <form method="POST" action="{{ route('reviews.store-store', $store) }}" class="mt-4 space-y-2">
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
