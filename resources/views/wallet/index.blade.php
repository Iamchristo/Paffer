<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Wallet') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('Done.') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-sm text-gray-500">{{ __('Current balance') }}</p>
                <p class="text-3xl font-semibold text-gray-900">${{ number_format($wallet->balance_cents / 100, 2) }}</p>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-3">{{ __('Request a top-up') }}</h3>
                <p class="text-xs text-gray-500 mb-3">{{ __('Top-up requests are reviewed and approved by an admin before funds are added to your balance.') }}</p>
                <form method="POST" action="{{ route('wallet.topup') }}" class="flex items-center gap-3">
                    @csrf
                    <input type="number" name="amount" step="0.01" min="1" max="10000" placeholder="{{ __('Amount in USD') }}" class="rounded-md border-gray-300 text-sm" required>
                    <x-primary-button>{{ __('Request') }}</x-primary-button>
                </form>
                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-3">{{ __('Transaction history') }}</h3>
                @forelse ($transactions as $transaction)
                    <div class="border-b border-gray-100 py-3 flex items-center justify-between text-sm">
                        <div>
                            <p class="text-gray-900 capitalize">{{ $transaction->type }}</p>
                            <p class="text-gray-500">{{ $transaction->description ?? '—' }} &middot; {{ $transaction->created_at->format('M j, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium {{ $transaction->amount_cents < 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $transaction->amount_cents < 0 ? '-' : '+' }}${{ number_format(abs($transaction->amount_cents) / 100, 2) }}
                            </p>
                            <span class="text-xs uppercase tracking-wide text-gray-500">{{ $transaction->status }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600 text-sm">{{ __('No transactions yet.') }}</p>
                @endforelse

                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
