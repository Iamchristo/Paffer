<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Orders') }}</h2>
    </x-slot>

    @if (session('status'))
        <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('Action completed.') }}</div>
    @endif

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <form method="GET" class="flex flex-wrap gap-3 mb-4">
            <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Search by buyer or store...') }}" class="flex-1 min-w-[12rem] rounded-md border-gray-300 text-sm">
            <select name="status" class="rounded-md border-gray-300 text-sm">
                <option value="">{{ __('All statuses') }}</option>
                @foreach (['pending', 'processing', 'completed', 'cancelled'] as $option)
                    <option value="{{ $option }}" @selected($status === $option)>{{ ucfirst($option) }}</option>
                @endforeach
            </select>
            <x-primary-button>{{ __('Filter') }}</x-primary-button>
        </form>

        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="py-2 font-medium">{{ __('Order') }}</th>
                    <th class="py-2 font-medium">{{ __('Buyer') }}</th>
                    <th class="py-2 font-medium">{{ __('Store') }}</th>
                    <th class="py-2 font-medium">{{ __('Total') }}</th>
                    <th class="py-2 font-medium">{{ __('Status') }}</th>
                    <th class="py-2 font-medium">{{ __('Placed') }}</th>
                    <th class="py-2 font-medium"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-b border-gray-100">
                        <td class="py-3 font-medium text-gray-900">#{{ $order->id }}</td>
                        <td class="py-3 text-gray-700">{{ $order->buyer?->name }}</td>
                        <td class="py-3 text-gray-700">{{ $order->store?->name }}</td>
                        <td class="py-3 text-gray-700">${{ number_format($order->total_cents / 100, 2) }}</td>
                        <td class="py-3">
                            <span class="inline-block rounded-full px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-700">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td class="py-3 text-gray-500">{{ $order->created_at->diffForHumans() }}</td>
                        <td class="py-3 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600">{{ __('View') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center text-gray-500">{{ __('No orders found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $orders->links() }}
    </div>
</x-admin-layout>
