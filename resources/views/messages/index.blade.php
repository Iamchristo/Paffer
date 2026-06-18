<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Messages') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @forelse ($conversations as $conversation)
                @php
                    $otherUser = $conversation->otherUser($user);
                    $unread = $conversation->unreadCountFor($user);
                @endphp
                <a href="{{ route('messages.show', $otherUser) }}" class="block bg-white shadow-sm sm:rounded-lg p-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900">{{ $otherUser->name }}</p>
                            <p class="text-sm text-gray-600 truncate">{{ $conversation->latestMessage?->body }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs text-gray-400">{{ $conversation->last_message_at?->diffForHumans() }}</p>
                            @if ($unread > 0)
                                <span class="inline-flex items-center justify-center mt-1 px-2 py-0.5 text-xs font-semibold text-white bg-indigo-600 rounded-full">{{ $unread }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-600">
                    {{ __('No conversations yet. Visit a profile in People and send a message to get started.') }}
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
