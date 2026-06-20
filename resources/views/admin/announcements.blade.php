<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Announcements') }}</h2>
    </x-slot>

    @if (session('status') === 'announcement-sent')
        <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('Announcement sent.') }}</div>
    @endif

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-4">{{ __('Compose Announcement') }}</h3>

        <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="audience" :value="__('Audience')" />
                <select id="audience" name="audience" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="all">{{ __('All users') }}</option>
                    <option value="sellers">{{ __('Approved sellers') }}</option>
                    <option value="tutors">{{ __('Approved tutors') }}</option>
                    <option value="members">{{ __('Members (not sellers or tutors)') }}</option>
                </select>
                <x-input-error :messages="$errors->get('audience')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="subject" :value="__('Subject')" />
                <x-text-input id="subject" name="subject" type="text" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('subject')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="body" :value="__('Message')" />
                <textarea id="body" name="body" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                <x-input-error :messages="$errors->get('body')" class="mt-2" />
            </div>

            <x-primary-button>{{ __('Send Announcement') }}</x-primary-button>
        </form>
    </div>

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-3">{{ __('History') }}</h3>
        @forelse ($announcements as $announcement)
            <div class="border-b border-gray-100 py-3 text-sm">
                <p class="font-medium text-gray-900">{{ $announcement->subject }}</p>
                <p class="text-gray-500">
                    {{ __('To') }} {{ $announcement->audience }} &middot;
                    {{ $announcement->recipient_count }} {{ __('recipients') }} &middot;
                    {{ $announcement->created_at->diffForHumans() }}
                </p>
            </div>
        @empty
            <p class="text-gray-600 text-sm">{{ __('No announcements sent yet.') }}</p>
        @endforelse

        {{ $announcements->links() }}
    </div>
</x-admin-layout>
