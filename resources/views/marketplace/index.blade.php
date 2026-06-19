<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Marketplace') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="GET" class="bg-white shadow-sm sm:rounded-lg p-4 flex gap-3">
                <input type="text" name="q" value="{{ $search }}" placeholder="{{ __('Search stores...') }}" class="flex-1 rounded-md border-gray-300">
                <x-primary-button>{{ __('Search') }}</x-primary-button>
            </form>

            @if ($ads->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach ($ads as $ad)
                        <a href="{{ $ad->target_url ?? '#' }}" class="bg-white shadow-sm sm:rounded-lg p-4 hover:shadow-md transition">
                            <p class="text-xs text-gray-400 uppercase mb-1">{{ __('Sponsored') }}</p>
                            <p class="font-semibold text-gray-900">{{ $ad->title }}</p>
                            @if ($ad->body)
                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $ad->body }}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($stores as $store)
                    <a href="{{ route('marketplace.store', $store) }}" class="bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                        <h3 class="font-semibold text-gray-900">{{ $store->name }}</h3>
                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $store->description }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $store->products_count }} {{ __('products') }}</p>
                    </a>
                @empty
                    <p class="text-gray-600">{{ __('No stores available yet.') }}</p>
                @endforelse
            </div>

            {{ $stores->links() }}
        </div>
    </div>
</x-app-layout>
