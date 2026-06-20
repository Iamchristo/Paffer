<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Order') }} #{{ $order->id }}</h2>
    </x-slot>

    <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-1 text-sm">
        <p><span class="text-gray-500">{{ __('Buyer') }}:</span> <span class="font-medium text-gray-900">{{ $order->buyer?->name }}</span></p>
        <p><span class="text-gray-500">{{ __('Store') }}:</span> <span class="font-medium text-gray-900">{{ $order->store?->name }}</span></p>
        <p><span class="text-gray-500">{{ __('Status') }}:</span> <span class="font-medium text-gray-900">{{ ucfirst($order->status) }}</span></p>
        <p><span class="text-gray-500">{{ __('Total') }}:</span> <span class="font-medium text-gray-900">${{ number_format($order->total_cents / 100, 2) }}</span></p>
        <p><span class="text-gray-500">{{ __('Placed') }}:</span> <span class="font-medium text-gray-900">{{ $order->created_at->format('M j, Y H:i') }}</span></p>
    </div>

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-3">{{ __('Items') }}</h3>
        @forelse ($order->items as $item)
            <div class="border-b border-gray-100 py-3 flex items-center justify-between text-sm">
                <p class="font-medium text-gray-900">{{ $item->product?->name }}</p>
                <p class="text-gray-500">{{ $item->quantity }} &times; ${{ number_format($item->price_cents / 100, 2) }}</p>
            </div>
        @empty
            <p class="text-gray-600 text-sm">{{ __('No items.') }}</p>
        @endforelse
    </div>

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-3">{{ __('Shipment') }}</h3>
        @if ($order->shipment)
            <div class="text-sm space-y-1">
                <p><span class="text-gray-500">{{ __('Carrier') }}:</span> {{ $order->shipment->carrier ?? __('Not set') }}</p>
                <p><span class="text-gray-500">{{ __('Tracking number') }}:</span> {{ $order->shipment->tracking_number ?? __('Not set') }}</p>
                <p><span class="text-gray-500">{{ __('Status') }}:</span> {{ ucfirst($order->shipment->status) }}</p>
            </div>
        @else
            <p class="text-gray-600 text-sm">{{ __('No shipment record yet.') }}</p>
        @endif
    </div>
</x-admin-layout>
