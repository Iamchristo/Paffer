<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Wallets') }}</h2>
    </x-slot>

    @if (session('status'))
        <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('Action completed.') }}</div>
    @endif

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-3">{{ __('Pending top-up requests') }}</h3>
        @forelse ($pendingTopups as $transaction)
            <div class="border-b border-gray-100 py-3 flex items-center justify-between text-sm">
                <div>
                    <p class="font-medium text-gray-900">{{ $transaction->wallet->user->name }}</p>
                    <p class="text-gray-500">${{ number_format($transaction->amount_cents / 100, 2) }} &middot; {{ $transaction->created_at->diffForHumans() }}</p>
                </div>
                <div class="flex gap-3">
                    <form method="POST" action="{{ route('admin.wallets.topups.approve', $transaction) }}">
                        @csrf
                        <button type="submit" class="text-green-600">{{ __('Approve') }}</button>
                    </form>
                    <form method="POST" action="{{ route('admin.wallets.topups.reject', $transaction) }}">
                        @csrf
                        <button type="submit" class="text-red-500">{{ __('Reject') }}</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-600 text-sm">{{ __('No pending top-ups.') }}</p>
        @endforelse
    </div>

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <form method="GET" class="flex gap-3 mb-4">
            <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Search by name or email...') }}" class="flex-1 rounded-md border-gray-300 text-sm">
            <x-primary-button>{{ __('Search') }}</x-primary-button>
        </form>

        @forelse ($wallets as $user)
            <div class="border-b border-gray-100 py-3 flex items-center justify-between text-sm">
                <div>
                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                    <p class="text-gray-500">{{ $user->email }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="font-medium text-gray-900">${{ number_format(($user->wallet->balance_cents ?? 0) / 100, 2) }}</span>
                    <form method="POST" action="{{ route('admin.wallets.adjust', $user) }}" class="flex items-center gap-2">
                        @csrf
                        <input type="number" name="amount" step="0.01" placeholder="{{ __('+/- amount') }}" class="w-28 rounded-md border-gray-300 text-xs">
                        <button type="submit" class="text-xs text-indigo-600">{{ __('Adjust') }}</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-600 text-sm">{{ __('No wallets found.') }}</p>
        @endforelse

        {{ $wallets->links() }}
    </div>
</x-admin-layout>
