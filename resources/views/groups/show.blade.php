<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $group->name }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                <div class="h-24 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                <div class="p-6 -mt-10">
                    <div class="flex items-start justify-between gap-4 flex-wrap">
                        <div class="flex items-start gap-4">
                            <div class="h-16 w-16 rounded-xl bg-white shadow flex items-center justify-center text-indigo-600 border border-gray-100 shrink-0">
                                <x-icon name="user-group" class="h-8 w-8" />
                            </div>
                            <div class="pt-8">
                                <h1 class="text-lg font-bold text-gray-900">{{ $group->name }}</h1>
                                <p class="text-sm text-gray-600 mt-1 whitespace-pre-line">{{ $group->description }}</p>
                                <p class="text-xs text-gray-500 mt-2 inline-flex items-center gap-3">
                                    <span class="inline-flex items-center gap-1"><x-icon name="star" class="h-3.5 w-3.5" /> {{ __('Started by') }} {{ $group->owner->name }}</span>
                                    <span class="inline-flex items-center gap-1"><x-icon name="users" class="h-3.5 w-3.5" /> {{ $membersCount }} {{ __('members') }}</span>
                                </p>
                            </div>
                        </div>

                        @auth
                            <div class="pt-8">
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
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            @auth
                @if ($isMember)
                    <div class="bg-white shadow-sm rounded-xl p-6">
                        <form method="POST" action="{{ route('group-posts.store', $group) }}" class="space-y-3">
                            @csrf
                            <div class="flex items-start gap-3">
                                <x-avatar :user="Auth::user()" size="10" class="shrink-0" />
                                <textarea name="body" rows="3" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="{{ __('Start a discussion...') }}">{{ old('body') }}</textarea>
                            </div>
                            <x-input-error :messages="$errors->get('body')" />
                            <div class="flex justify-end">
                                <x-primary-button>{{ __('Post') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                @endif
            @endauth

            <div class="space-y-4">
                @forelse ($posts as $post)
                    <div class="bg-white shadow-sm rounded-xl p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3 min-w-0">
                                <x-avatar :user="$post->user" size="9" class="shrink-0" />
                                <span class="font-semibold text-gray-900 truncate">{{ $post->user->name }}</span>
                            </div>
                            <span class="text-xs text-gray-500 shrink-0">{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mt-3 text-gray-800 whitespace-pre-line">{{ $post->body }}</p>

                        <div class="mt-4 space-y-2">
                            @foreach ($post->comments as $comment)
                                <div class="flex items-start gap-2 text-sm bg-gray-50 rounded-lg p-2">
                                    <x-avatar :user="$comment->user" size="6" class="shrink-0 mt-0.5" />
                                    <p>
                                        <span class="font-medium text-gray-800">{{ $comment->user->name }}</span>
                                        <span class="text-gray-600">{{ $comment->body }}</span>
                                    </p>
                                </div>
                            @endforeach

                            @auth
                                @if ($isMember)
                                    <form method="POST" action="{{ route('group-posts.comments.store', $post) }}" class="flex gap-2">
                                        @csrf
                                        <input type="text" name="body" class="flex-1 rounded-md border-gray-300 text-sm" placeholder="{{ __('Write a comment...') }}">
                                        <button type="submit" class="text-sm text-indigo-600 font-medium">{{ __('Reply') }}</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="bg-white shadow-sm rounded-xl p-10 text-center text-gray-600">
                        {{ __('No discussions yet.') }}
                    </div>
                @endforelse

                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
