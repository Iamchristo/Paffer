<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $group->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-gray-600 whitespace-pre-line">{{ $group->description }}</p>
                        <p class="text-xs text-gray-500 mt-2">
                            {{ __('Started by') }} {{ $group->owner->name }} · {{ $membersCount }} {{ __('members') }}
                        </p>
                    </div>

                    @auth
                        @if ($isMember)
                            @if ($group->owner_id !== Auth::id())
                                <form method="POST" action="{{ route('groups.leave', $group) }}">
                                    @csrf
                                    @method('DELETE')
                                    <x-secondary-button>{{ __('Leave') }}</x-secondary-button>
                                </form>
                            @endif
                        @else
                            <form method="POST" action="{{ route('groups.join', $group) }}">
                                @csrf
                                <x-primary-button>{{ __('Join') }}</x-primary-button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>

            @auth
                @if ($isMember)
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <form method="POST" action="{{ route('group-posts.store', $group) }}" class="space-y-3">
                            @csrf
                            <textarea name="body" rows="3" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="{{ __('Start a discussion...') }}">{{ old('body') }}</textarea>
                            <x-input-error :messages="$errors->get('body')" />
                            <x-primary-button>{{ __('Post') }}</x-primary-button>
                        </form>
                    </div>
                @endif
            @endauth

            <div class="space-y-4">
                @forelse ($posts as $post)
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-gray-900">{{ $post->user->name }}</span>
                            <span class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mt-3 text-gray-800 whitespace-pre-line">{{ $post->body }}</p>

                        <div class="mt-4 space-y-2">
                            @foreach ($post->comments as $comment)
                                <div class="text-sm bg-gray-50 rounded-md p-2">
                                    <span class="font-medium text-gray-800">{{ $comment->user->name }}</span>
                                    <span class="text-gray-600">{{ $comment->body }}</span>
                                </div>
                            @endforeach

                            @auth
                                @if ($isMember)
                                    <form method="POST" action="{{ route('group-posts.comments.store', $post) }}" class="flex gap-2">
                                        @csrf
                                        <input type="text" name="body" class="flex-1 rounded-md border-gray-300 text-sm" placeholder="{{ __('Write a comment...') }}">
                                        <button type="submit" class="text-sm text-indigo-600">{{ __('Reply') }}</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-600">
                        {{ __('No discussions yet.') }}
                    </div>
                @endforelse

                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
