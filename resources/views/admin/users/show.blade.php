<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $user->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('admin._nav')

            @if (session('status'))
                <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('Action completed.') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <div>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    <p class="text-sm text-gray-500">{{ __('Role') }}: {{ $user->role }} &middot; {{ $user->is_suspended ? __('Suspended') : __('Active') }}</p>
                </div>

                <div class="flex gap-3 text-sm">
                    <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}">
                        @csrf
                        <button type="submit" class="text-indigo-600">
                            {{ $user->isAdmin() ? __('Demote to member') : __('Promote to admin') }}
                        </button>
                    </form>

                    @if ($user->is_suspended)
                        <form method="POST" action="{{ route('admin.users.unsuspend', $user) }}">
                            @csrf
                            <button type="submit" class="text-green-600">{{ __('Unsuspend account') }}</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                            @csrf
                            <button type="submit" class="text-red-500">{{ __('Suspend account') }}</button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-3">{{ __('Seller Status') }}</h3>
                <form method="POST" action="{{ route('admin.users.seller-status', $user) }}" class="flex items-center gap-3">
                    @csrf
                    <select name="seller_status" class="rounded-md border-gray-300 shadow-sm text-sm">
                        @foreach (['none', 'pending', 'approved', 'rejected'] as $status)
                            <option value="{{ $status }}" @selected($user->seller_status === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                </form>
                @if ($user->store)
                    <p class="text-sm text-gray-500 mt-3">{{ __('Store') }}: {{ $user->store->name }} ({{ $user->store->status }}) &middot; {{ $user->store->products->count() }} {{ __('products') }}</p>
                @endif
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-3">{{ __('Tutor Status') }}</h3>
                <form method="POST" action="{{ route('admin.users.tutor-status', $user) }}" class="flex items-center gap-3">
                    @csrf
                    <select name="tutor_status" class="rounded-md border-gray-300 shadow-sm text-sm">
                        @foreach (['none', 'pending', 'approved', 'rejected'] as $status)
                            <option value="{{ $status }}" @selected($user->tutor_status === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                </form>
                <p class="text-sm text-gray-500 mt-3">{{ $user->courses->count() }} {{ __('courses') }}</p>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-3">{{ __('Activity') }}</h3>
                <p class="text-sm text-gray-500">{{ $user->posts->count() }} {{ __('posts') }} &middot;
                    {{ $user->orders->count() }} {{ __('orders placed') }} &middot;
                    {{ $user->ownedGroups->count() }} {{ __('groups owned') }} &middot;
                    {{ $user->organizedEvents->count() }} {{ __('events organized') }} &middot;
                    {{ $user->ridesOffered->count() }} {{ __('rides offered') }} &middot;
                    {{ $user->ads->count() }} {{ __('ads') }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
