<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Order') }} #{{ $order->id }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-sm text-gray-500">{{ __('Store') }}: {{ $order->store->name }}</p>
                <p class="text-sm text-gray-500">{{ __('Status') }}: <span class="uppercase">{{ $order->status }}</span></p>
                <div class="mt-4 divide-y divide-gray-100">
                    @foreach ($order->items as $item)
                        <div class="py-2 flex justify-between text-sm">
                            <span>{{ $item->product->name }} &times; {{ $item->quantity }}</span>
                            <span>${{ number_format($item->price_cents * $item->quantity / 100, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <p class="mt-4 text-right font-semibold text-gray-900">{{ __('Total') }}: ${{ number_format($order->total_cents / 100, 2) }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
