<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Store Orders') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ session('status') }}</div>
            @endif

            @forelse ($orders as $order)
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">#{{ $order->id }} &mdash; {{ $order->buyer->name }}</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('M j, Y') }}</p>
                        </div>
                        <p class="font-medium text-gray-900">${{ number_format($order->total_cents / 100, 2) }}</p>
                    </div>

                    <div class="mt-3 divide-y divide-gray-100">
                        @foreach ($order->items as $item)
                            <div class="py-1 flex justify-between text-sm text-gray-600">
                                <span>{{ $item->product->name }} &times; {{ $item->quantity }}</span>
                                <span>${{ number_format($item->price_cents * $item->quantity / 100, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <form method="POST" action="{{ route('seller.orders.update', $order) }}" class="mt-4 flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="rounded-md border-gray-300 text-sm">
                            @foreach (['pending', 'processing', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="text-sm text-indigo-600">{{ __('Update') }}</button>
                    </form>

                    <form method="POST" action="{{ route('seller.orders.shipment.update', $order) }}" class="mt-3 pt-3 border-t border-gray-100 flex items-end gap-2 flex-wrap">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label :value="__('Carrier')" />
                            <input type="text" name="carrier" value="{{ $order->shipment->carrier ?? '' }}" class="mt-1 block w-32 rounded-md border-gray-300 text-sm">
                        </div>
                        <div>
                            <x-input-label :value="__('Tracking #')" />
                            <input type="text" name="tracking_number" value="{{ $order->shipment->tracking_number ?? '' }}" class="mt-1 block w-40 rounded-md border-gray-300 text-sm">
                        </div>
                        <div>
                            <x-input-label :value="__('Shipment status')" />
                            <select name="status" class="mt-1 rounded-md border-gray-300 text-sm">
                                @foreach (['pending', 'preparing', 'shipped', 'in_transit', 'delivered'] as $status)
                                    <option value="{{ $status }}" @selected(($order->shipment->status ?? 'pending') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="text-sm text-indigo-600">{{ __('Save Shipment') }}</button>
                    </form>
                </div>
            @empty
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-600">{{ __('No orders yet.') }}</div>
            @endforelse

            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
