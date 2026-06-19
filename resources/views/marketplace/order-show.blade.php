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

            @if ($order->shipment)
                <div class="bg-white shadow-sm sm:rounded-lg p-6 mt-6">
                    <h3 class="font-semibold text-gray-900 mb-2">{{ __('Shipment') }}</h3>
                    <p class="text-sm text-gray-600">{{ __('Status') }}: <span class="uppercase">{{ str_replace('_', ' ', $order->shipment->status) }}</span></p>
                    @if ($order->shipment->carrier)
                        <p class="text-sm text-gray-600">{{ __('Carrier') }}: {{ $order->shipment->carrier }}</p>
                    @endif
                    @if ($order->shipment->tracking_number)
                        <p class="text-sm text-gray-600">{{ __('Tracking #') }}: {{ $order->shipment->tracking_number }}</p>
                    @endif
                    @if ($order->shipment->shipped_at)
                        <p class="text-sm text-gray-600">{{ __('Shipped') }}: {{ $order->shipment->shipped_at->format('M j, Y') }}</p>
                    @endif
                    @if ($order->shipment->delivered_at)
                        <p class="text-sm text-gray-600">{{ __('Delivered') }}: {{ $order->shipment->delivered_at->format('M j, Y') }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
