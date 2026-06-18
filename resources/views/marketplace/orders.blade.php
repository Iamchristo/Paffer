<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('My Orders') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @forelse ($orders as $order)
                <a href="{{ route('orders.show', $order) }}" class="bg-white shadow-sm sm:rounded-lg p-6 flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $order->store->name }}</p>
                        <p class="text-xs text-gray-500">{{ $order->created_at->format('M j, Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">${{ number_format($order->total_cents / 100, 2) }}</p>
                        <span class="text-xs uppercase tracking-wide text-gray-500">{{ $order->status }}</span>
                    </div>
                </a>
            @empty
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-600">{{ __('No orders yet.') }}</div>
            @endforelse

            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
