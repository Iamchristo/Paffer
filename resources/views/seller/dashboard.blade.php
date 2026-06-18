<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Seller Dashboard') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'application-submitted')
                <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('Your application has been submitted and is pending review.') }}</div>
            @endif

            @if (! $store)
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-600">{{ __("You don't have a store yet.") }}</div>
            @elseif ($store->status === 'pending')
                <div class="bg-yellow-50 text-yellow-700 text-sm rounded-md p-3">{{ __('Your store application is pending admin approval.') }}</div>
            @elseif ($store->status === 'rejected')
                <div class="bg-red-50 text-red-700 text-sm rounded-md p-3">{{ __('Your store application was rejected.') }}</div>
            @else
                <div class="bg-white shadow-sm sm:rounded-lg p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $store->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $store->products_count }} {{ __('products') }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('seller.store.edit') }}" class="text-sm text-indigo-600">{{ __('Edit Store') }}</a>
                        <a href="{{ route('seller.products.index') }}" class="text-sm text-indigo-600">{{ __('Manage Products') }}</a>
                        <a href="{{ route('seller.orders.index') }}" class="text-sm text-indigo-600">{{ __('View Orders') }}</a>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 mb-3">{{ __('Recent Orders') }}</h3>
                    @forelse ($store->orders as $order)
                        <div class="border-b border-gray-100 py-2 flex items-center justify-between text-sm">
                            <span>#{{ $order->id }} &mdash; {{ $order->buyer->name }}</span>
                            <span class="uppercase text-xs text-gray-500">{{ $order->status }}</span>
                            <span class="font-medium text-gray-900">${{ number_format($order->total_cents / 100, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-gray-600 text-sm">{{ __('No orders yet.') }}</p>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
