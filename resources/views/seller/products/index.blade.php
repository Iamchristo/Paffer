<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('My Products') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ session('status') }}</div>
            @endif

            <div class="flex justify-end">
                <a href="{{ route('seller.products.create') }}">
                    <x-primary-button>{{ __('Add Product') }}</x-primary-button>
                </a>
            </div>

            @forelse ($products as $product)
                <div class="bg-white shadow-sm sm:rounded-lg p-6 flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">{{ $product->stock }} {{ __('in stock') }} &middot; <span class="uppercase">{{ $product->status }}</span></p>
                    </div>
                    <p class="font-medium text-gray-900">${{ number_format($product->price_cents / 100, 2) }}</p>
                    <div class="flex gap-3 items-center">
                        <a href="{{ route('seller.products.edit', $product) }}" class="text-sm text-indigo-600">{{ __('Edit') }}</a>
                        <form method="POST" action="{{ route('seller.products.destroy', $product) }}" onsubmit="return confirm('{{ __('Delete this product?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-500">{{ __('Delete') }}</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-600">{{ __('No products yet.') }}</div>
            @endforelse

            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>
