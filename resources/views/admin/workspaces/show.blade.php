<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $workspace->name }}</h2>
    </x-slot>

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <p class="text-sm text-gray-600">{{ $workspace->description }}</p>
        <p class="text-xs text-gray-500 mt-2">{{ __('Owner') }}: {{ $workspace->owner->name }} &middot; {{ __('Status') }}: {{ $workspace->status }}</p>
    </div>

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-3">{{ __('Members') }}</h3>
        @foreach ($workspace->members as $member)
            <div class="border-b border-gray-100 py-2 flex items-center justify-between text-sm">
                <span class="text-gray-800">{{ $member->name }}</span>
                <span class="text-xs text-gray-500">{{ $member->pivot->role }}</span>
            </div>
        @endforeach
    </div>

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-3">{{ __('Tasks') }}</h3>
        @forelse ($workspace->tasks as $task)
            <div class="border-b border-gray-100 py-2 flex items-center justify-between text-sm">
                <div>
                    <p class="text-gray-900">{{ $task->title }}</p>
                    <p class="text-xs text-gray-500">{{ $task->assignee?->name ?? __('Unassigned') }}</p>
                </div>
                <span class="text-xs uppercase tracking-wide text-gray-500">{{ $task->status }}</span>
            </div>
        @empty
            <p class="text-gray-600 text-sm">{{ __('No tasks.') }}</p>
        @endforelse
    </div>
</x-admin-layout>
