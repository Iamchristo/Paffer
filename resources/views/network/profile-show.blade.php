<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $profileUser->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $profileUser->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $profileUser->profile?->headline }}</p>
                        @if ($profileUser->profile?->business_name)
                            <p class="text-sm text-gray-500">{{ $profileUser->profile->business_name }}</p>
                        @endif
                        @if ($profileUser->profile?->industry)
                            <p class="text-xs text-gray-500 mt-1">{{ __('Industry') }}: {{ $profileUser->profile->industry }}</p>
                        @endif
                        @if ($profileUser->profile?->location)
                            <p class="text-xs text-gray-500">{{ __('Location') }}: {{ $profileUser->profile->location }}</p>
                        @endif
                        @if ($profileUser->profile?->website)
                            <p class="text-xs text-indigo-600"><a href="{{ $profileUser->profile->website }}" target="_blank">{{ $profileUser->profile->website }}</a></p>
                        @endif
                        <p class="text-xs text-gray-500 mt-2">{{ $followersCount }} {{ __('followers') }} · {{ $followingCount }} {{ __('following') }}</p>
                    </div>

                    @auth
                        @if ($profileUser->id !== Auth::id())
                            <div class="flex items-center gap-2">
                                <a href="{{ route('messages.show', $profileUser) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                                    {{ __('Message') }}
                                </a>
                                @if ($isFollowing)
                                    <form method="POST" action="{{ route('connections.destroy', $profileUser) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-secondary-button>{{ __('Unfollow') }}</x-secondary-button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('connections.store', $profileUser) }}">
                                        @csrf
                                        <x-primary-button>{{ __('Follow') }}</x-primary-button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @endauth
                </div>

                @if ($profileUser->profile?->bio)
                    <p class="mt-4 text-gray-700 whitespace-pre-line">{{ $profileUser->profile->bio }}</p>
                @endif

                @if ($profileUser->profile?->skills->isNotEmpty())
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($profileUser->profile->skills as $skill)
                            <span class="text-xs bg-indigo-50 text-indigo-700 px-2 py-1 rounded-full">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="mt-4 flex gap-4 text-sm">
                    @if ($profileUser->isApprovedSeller())
                        <a href="{{ route('marketplace.store', $profileUser->store) }}" class="text-indigo-600">{{ __('Visit Store') }} &rarr;</a>
                    @endif
                    @if ($profileUser->isApprovedTutor())
                        <a href="{{ route('learn.index') }}" class="text-indigo-600">{{ __('View Courses') }} &rarr;</a>
                    @endif
                </div>
            </div>

            <div class="space-y-4">
                @forelse ($profileUser->posts as $post)
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                        <p class="mt-2 text-gray-800 whitespace-pre-line">{{ $post->body }}</p>
                    </div>
                @empty
                    <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-600">{{ __('No posts yet.') }}</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
