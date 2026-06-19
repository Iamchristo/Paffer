<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('My Ads') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ session('status') }}</div>
            @endif

            <div class="flex justify-end">
                <a href="{{ route('ads.create') }}">
                    <x-primary-button>{{ __('New Ad') }}</x-primary-button>
                </a>
            </div>

            @forelse ($ads as $ad)
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900">{{ $ad->title }}</h3>
                        <span class="text-xs uppercase font-medium {{ $ad->status === 'approved' ? 'text-green-600' : ($ad->status === 'rejected' ? 'text-red-500' : 'text-gray-500') }}">{{ $ad->status }}</span>
                    </div>
                    @if ($ad->body)
                        <p class="text-sm text-gray-600 mt-1">{{ $ad->body }}</p>
                    @endif
                    @if ($ad->target_url)
                        <p class="text-xs text-gray-500 mt-1">{{ $ad->target_url }}</p>
                    @endif

                    <form method="POST" action="{{ route('ads.destroy', $ad) }}" class="mt-3" onsubmit="return confirm('{{ __('Delete this ad?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-500">{{ __('Delete') }}</button>
                    </form>
                </div>
            @empty
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-600">{{ __('No ads yet.') }}</div>
            @endforelse

            {{ $ads->links() }}
        </div>
    </div>
</x-app-layout>
