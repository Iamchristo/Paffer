<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Network Feed') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                @auth
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <textarea name="body" rows="3" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="{{ __('Share an update with your network...') }}">{{ old('body') }}</textarea>
                            <x-input-error :messages="$errors->get('body')" />
                            <div class="flex items-center justify-between">
                                <input type="file" name="image" class="text-sm text-gray-600">
                                <x-primary-button>{{ __('Post') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="bg-white shadow-sm sm:rounded-lg p-6 flex items-center justify-between">
                        <p class="text-gray-600 text-sm">{{ __("You're browsing as a guest. Log in to post, like, and comment.") }}</p>
                        <a href="{{ route('login') }}" class="ms-4 shrink-0"><x-primary-button>{{ __('Log in') }}</x-primary-button></a>
                    </div>
                @endauth

                @forelse ($posts as $post)
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <a href="{{ route('people.show', $post->user) }}" class="font-semibold text-gray-900">{{ $post->user->name }}</a>
                            <span class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                        @if ($post->user->profile?->headline)
                            <p class="text-xs text-gray-500">{{ $post->user->profile->headline }}</p>
                        @endif
                        <p class="mt-3 text-gray-800 whitespace-pre-line">{{ $post->body }}</p>
                        @if ($post->image_path)
                            <img src="{{ Storage::url($post->image_path) }}" class="mt-3 rounded-lg max-h-96 object-cover" alt="">
                        @endif

                        <div class="mt-4 flex items-center space-x-4 text-sm">
                            @auth
                                <form method="POST" action="{{ route('posts.like', $post) }}">
                                    @csrf
                                    <button type="submit" class="{{ $post->isLikedBy(Auth::user()) ? 'text-indigo-600 font-medium' : 'text-gray-500' }}">
                                        {{ __('Like') }} ({{ $post->likes->count() }})
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-500">{{ __('Like') }} ({{ $post->likes->count() }})</span>
                            @endauth
                            <span class="text-gray-400">{{ $post->comments->count() }} {{ __('comments') }}</span>
                            @auth
                                @if ($post->user_id === Auth::id())
                                    <form method="POST" action="{{ route('posts.destroy', $post) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500">{{ __('Delete') }}</button>
                                    </form>
                                @endif
                            @endauth
                        </div>

                        <div class="mt-4 space-y-2">
                            @foreach ($post->comments as $comment)
                                <div class="text-sm bg-gray-50 rounded-md p-2">
                                    <span class="font-medium text-gray-800">{{ $comment->user->name }}</span>
                                    <span class="text-gray-600">{{ $comment->body }}</span>
                                </div>
                            @endforeach
                            @auth
                                <form method="POST" action="{{ route('posts.comments.store', $post) }}" class="flex gap-2">
                                    @csrf
                                    <input type="text" name="body" class="flex-1 rounded-md border-gray-300 text-sm" placeholder="{{ __('Write a comment...') }}">
                                    <button type="submit" class="text-sm text-indigo-600">{{ __('Reply') }}</button>
                                </form>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-600">
                        {{ __('No posts yet. Follow more people or share your first update.') }}
                    </div>
                @endforelse

                {{ $posts->links() }}
            </div>

            <div class="space-y-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 mb-3">{{ __('People you may know') }}</h3>
                    <div class="space-y-3">
                        @foreach ($suggestions as $person)
                            <div class="flex items-center justify-between">
                                <a href="{{ route('people.show', $person) }}" class="text-sm text-gray-800">{{ $person->name }}</a>
                                @auth
                                    <form method="POST" action="{{ route('connections.store', $person) }}">
                                        @csrf
                                        <button type="submit" class="text-xs text-indigo-600">{{ __('Follow') }}</button>
                                    </form>
                                @endauth
                            </div>
                        @endforeach
                    </div>
                </div>

                @foreach ($ads as $ad)
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <p class="text-xs text-gray-400 uppercase mb-2">{{ __('Sponsored') }}</p>
                        @if ($ad->image_path)
                            <img src="{{ Storage::url($ad->image_path) }}" class="rounded-lg mb-3 max-h-40 w-full object-cover" alt="">
                        @endif
                        <a href="{{ $ad->target_url ?? '#' }}" class="font-semibold text-gray-900">{{ $ad->title }}</a>
                        @if ($ad->body)
                            <p class="text-sm text-gray-600 mt-1">{{ $ad->body }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
