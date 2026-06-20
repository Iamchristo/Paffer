<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Moderation') }}</h2>
    </x-slot>

    @if (session('status'))
        <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('Action completed.') }}</div>
    @endif

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-3">{{ __('Recent Posts') }}</h3>
        @forelse ($posts as $post)
            <div class="border-b border-gray-100 py-3 flex items-center justify-between text-sm">
                <div>
                    <p class="text-gray-900">{{ \Illuminate\Support\Str::limit($post->body, 80) }}</p>
                    <p class="text-gray-500">{{ __('By') }} {{ $post->user->name }}</p>
                </div>
                <form method="POST" action="{{ route('admin.moderation.posts.destroy', $post) }}" onsubmit="return confirm('{{ __('Delete this post?') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500">{{ __('Delete') }}</button>
                </form>
            </div>
        @empty
            <p class="text-gray-600 text-sm">{{ __('No posts.') }}</p>
        @endforelse
    </div>

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-3">{{ __('Groups') }}</h3>
        @forelse ($groups as $group)
            <div class="border-b border-gray-100 py-3 flex items-center justify-between text-sm">
                <div>
                    <p class="font-medium text-gray-900">{{ $group->name }}</p>
                    <p class="text-gray-500">{{ __('Owner') }}: {{ $group->owner->name }}</p>
                </div>
                <form method="POST" action="{{ route('admin.moderation.groups.destroy', $group) }}" onsubmit="return confirm('{{ __('Delete this group?') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500">{{ __('Delete') }}</button>
                </form>
            </div>
        @empty
            <p class="text-gray-600 text-sm">{{ __('No groups.') }}</p>
        @endforelse
    </div>

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-3">{{ __('Events') }}</h3>
        @forelse ($events as $event)
            <div class="border-b border-gray-100 py-3 flex items-center justify-between text-sm">
                <div>
                    <p class="font-medium text-gray-900">{{ $event->title }}</p>
                    <p class="text-gray-500">{{ __('Organizer') }}: {{ $event->organizer->name }}</p>
                </div>
                <form method="POST" action="{{ route('admin.moderation.events.destroy', $event) }}" onsubmit="return confirm('{{ __('Delete this event?') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500">{{ __('Delete') }}</button>
                </form>
            </div>
        @empty
            <p class="text-gray-600 text-sm">{{ __('No events.') }}</p>
        @endforelse
    </div>

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-3">{{ __('Rides') }}</h3>
        @forelse ($rides as $ride)
            <div class="border-b border-gray-100 py-3 flex items-center justify-between text-sm">
                <div>
                    <p class="font-medium text-gray-900">{{ $ride->origin }} &rarr; {{ $ride->destination }}</p>
                    <p class="text-gray-500">{{ __('Driver') }}: {{ $ride->driver->name }}</p>
                </div>
                <form method="POST" action="{{ route('admin.moderation.rides.destroy', $ride) }}" onsubmit="return confirm('{{ __('Delete this ride?') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500">{{ __('Delete') }}</button>
                </form>
            </div>
        @empty
            <p class="text-gray-600 text-sm">{{ __('No rides.') }}</p>
        @endforelse
    </div>

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-3">{{ __('Stores') }}</h3>
        @forelse ($stores as $store)
            <div class="border-b border-gray-100 py-3 flex items-center justify-between text-sm">
                <div>
                    <p class="font-medium text-gray-900">{{ $store->name }}</p>
                    <p class="text-gray-500">{{ $store->user->name }} &middot; {{ __('Status') }}: {{ $store->status }}</p>
                </div>
                @if ($store->status === 'suspended')
                    <form method="POST" action="{{ route('admin.moderation.stores.unsuspend', $store) }}">
                        @csrf
                        <button type="submit" class="text-green-600">{{ __('Unsuspend') }}</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.moderation.stores.suspend', $store) }}">
                        @csrf
                        <button type="submit" class="text-red-500">{{ __('Suspend') }}</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-gray-600 text-sm">{{ __('No stores.') }}</p>
        @endforelse
    </div>

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-3">{{ __('Products') }}</h3>
        @forelse ($products as $product)
            <div class="border-b border-gray-100 py-3 flex items-center justify-between text-sm">
                <div>
                    <p class="font-medium text-gray-900">{{ $product->name }}</p>
                    <p class="text-gray-500">{{ $product->store->name }} &middot; {{ __('Status') }}: {{ $product->status }}</p>
                </div>
                <form method="POST" action="{{ route('admin.moderation.products.toggle', $product) }}">
                    @csrf
                    <button type="submit" class="{{ $product->isActive() ? 'text-red-500' : 'text-green-600' }}">
                        {{ $product->isActive() ? __('Deactivate') : __('Activate') }}
                    </button>
                </form>
            </div>
        @empty
            <p class="text-gray-600 text-sm">{{ __('No products.') }}</p>
        @endforelse
    </div>

    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-3">{{ __('Courses') }}</h3>
        @forelse ($courses as $course)
            <div class="border-b border-gray-100 py-3 flex items-center justify-between text-sm">
                <div>
                    <p class="font-medium text-gray-900">{{ $course->title }}</p>
                    <p class="text-gray-500">{{ $course->tutor->name }} &middot; {{ __('Status') }}: {{ $course->status }}</p>
                </div>
                @if ($course->status === 'approved')
                    <form method="POST" action="{{ route('admin.moderation.courses.suspend', $course) }}">
                        @csrf
                        <button type="submit" class="text-red-500">{{ __('Suspend') }}</button>
                    </form>
                @elseif ($course->status === 'rejected')
                    <form method="POST" action="{{ route('admin.moderation.courses.unsuspend', $course) }}">
                        @csrf
                        <button type="submit" class="text-green-600">{{ __('Reapprove') }}</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-gray-600 text-sm">{{ __('No courses.') }}</p>
        @endforelse
    </div>
</x-admin-layout>
