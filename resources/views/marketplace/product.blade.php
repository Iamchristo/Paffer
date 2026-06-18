<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $product->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if ($product->image_path)
                    <img src="{{ Storage::url($product->image_path) }}" class="rounded-lg max-h-80 object-cover mb-4" alt="">
                @endif
                <p class="text-sm text-gray-500">{{ __('Sold by') }} <a href="{{ route('marketplace.store', $product->store) }}" class="text-indigo-600">{{ $product->store->name }}</a></p>
                <p class="mt-2 text-gray-700">{{ $product->description }}</p>
                <p class="mt-3 text-2xl font-semibold text-gray-900">${{ number_format($product->price_cents / 100, 2) }}</p>
                <p class="text-sm text-gray-500">{{ $product->stock }} {{ __('in stock') }}</p>

                @auth
                    @if ($product->stock > 0)
                        <form method="POST" action="{{ route('cart.store', $product) }}" class="mt-4 flex items-center gap-3">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-20 rounded-md border-gray-300">
                            <x-primary-button>{{ __('Add to Cart') }}</x-primary-button>
                        </form>
                    @else
                        <p class="mt-4 text-red-500 text-sm">{{ __('Out of stock') }}</p>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="mt-4 inline-block text-indigo-600">{{ __('Log in to purchase') }}</a>
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
