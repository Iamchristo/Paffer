<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('people.show', $otherUser) }}" class="hover:underline">{{ $otherUser->name }}</a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                @forelse ($messages as $chatMessage)
                    <div class="flex {{ $chatMessage->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-sm px-4 py-2 rounded-lg {{ $chatMessage->sender_id === Auth::id() ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-800' }}">
                            <p class="whitespace-pre-line">{{ $chatMessage->body }}</p>
                            <p class="text-xs mt-1 {{ $chatMessage->sender_id === Auth::id() ? 'text-indigo-100' : 'text-gray-500' }}">{{ $chatMessage->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600">{{ __('Say hello to start the conversation.') }}</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('messages.store', $otherUser) }}" class="bg-white shadow-sm sm:rounded-lg p-4 flex gap-3 items-start">
                @csrf
                <div class="flex-1">
                    <textarea name="body" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm text-sm" placeholder="{{ __('Write a message...') }}" required>{{ old('body') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('body')" />
                </div>
                <x-primary-button>{{ __('Send') }}</x-primary-button>
            </form>
        </div>
    </div>
</x-app-layout>
