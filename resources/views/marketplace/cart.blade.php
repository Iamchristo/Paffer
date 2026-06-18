<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Your Cart') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ session('status') }}</div>
            @endif

            @forelse ($items as $item)
                <div class="bg-white shadow-sm sm:rounded-lg p-6 flex items-center justify-between">
                    <div>
                        <a href="{{ route('marketplace.product', $item['product']) }}" class="font-semibold text-gray-900">{{ $item['product']->name }}</a>
                        <p class="text-sm text-gray-500">{{ $item['product']->store->name }}</p>
                    </div>
                    <form method="POST" action="{{ route('cart.update', $item['product']) }}" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}" class="w-16 rounded-md border-gray-300 text-sm">
                        <button type="submit" class="text-xs text-indigo-600">{{ __('Update') }}</button>
                    </form>
                    <p class="font-medium text-gray-900">${{ number_format($item['subtotal_cents'] / 100, 2) }}</p>
                    <form method="POST" action="{{ route('cart.destroy', $item['product']) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-500">{{ __('Remove') }}</button>
                    </form>
                </div>
            @empty
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-600">{{ __('Your cart is empty.') }}</div>
            @endforelse

            @if ($items->isNotEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6 flex items-center justify-between">
                    <p class="text-lg font-semibold text-gray-900">{{ __('Total') }}: ${{ number_format($totalCents / 100, 2) }}</p>
                    <form method="POST" action="{{ route('checkout.store') }}">
                        @csrf
                        <x-primary-button>{{ __('Checkout') }}</x-primary-button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
