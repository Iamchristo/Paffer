<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Ads') }}</h2>
    </x-slot>

    @if (session('status'))
        <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('Action completed.') }}</div>
    @endif

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <form method="GET" class="flex flex-wrap gap-3 mb-4">
            <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Search by title or advertiser...') }}" class="flex-1 min-w-[12rem] rounded-md border-gray-300 text-sm">
            <select name="status" class="rounded-md border-gray-300 text-sm">
                <option value="">{{ __('All statuses') }}</option>
                @foreach (['pending', 'approved', 'rejected'] as $option)
                    <option value="{{ $option }}" @selected($status === $option)>{{ ucfirst($option) }}</option>
                @endforeach
            </select>
            <x-primary-button>{{ __('Filter') }}</x-primary-button>
        </form>

        @forelse ($ads as $ad)
            <div class="border-b border-gray-100 py-3 flex items-center justify-between text-sm">
                <div>
                    <p class="font-medium text-gray-900">{{ $ad->title }}</p>
                    <p class="text-gray-500">{{ __('By') }} {{ $ad->advertiser?->name }} &middot; <span class="capitalize">{{ $ad->status }}</span></p>
                </div>
                <div class="flex items-center gap-3">
                    @if ($ad->status !== 'approved')
                        <form method="POST" action="{{ route('admin.ads.approve', $ad) }}">
                            @csrf
                            <button type="submit" class="text-green-600">{{ __('Approve') }}</button>
                        </form>
                    @endif
                    @if ($ad->status !== 'rejected')
                        <form method="POST" action="{{ route('admin.ads.reject', $ad) }}">
                            @csrf
                            <button type="submit" class="text-red-500">{{ __('Reject') }}</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.ads.destroy', $ad) }}" onsubmit="return confirm('{{ __('Delete this ad?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-500">{{ __('Delete') }}</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-600 text-sm">{{ __('No ads found.') }}</p>
        @endforelse

        {{ $ads->links() }}
    </div>
</x-admin-layout>
