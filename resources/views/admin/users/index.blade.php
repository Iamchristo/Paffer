<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Users') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('admin._nav')

            @if (session('status'))
                <div class="bg-green-50 text-green-700 text-sm rounded-md p-3">{{ __('Action completed.') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
                    <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Search by name or email') }}" class="w-full rounded-md border-gray-300 shadow-sm" />
                </form>

                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-100">
                            <th class="py-2">{{ __('Name') }}</th>
                            <th class="py-2">{{ __('Email') }}</th>
                            <th class="py-2">{{ __('Role') }}</th>
                            <th class="py-2">{{ __('Seller') }}</th>
                            <th class="py-2">{{ __('Tutor') }}</th>
                            <th class="py-2">{{ __('Status') }}</th>
                            <th class="py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b border-gray-50">
                                <td class="py-2">{{ $user->name }}</td>
                                <td class="py-2">{{ $user->email }}</td>
                                <td class="py-2">{{ $user->role }}</td>
                                <td class="py-2">{{ $user->is_seller ? $user->seller_status : '—' }}</td>
                                <td class="py-2">{{ $user->is_tutor ? $user->tutor_status : '—' }}</td>
                                <td class="py-2">{{ $user->is_suspended ? __('Suspended') : __('Active') }}</td>
                                <td class="py-2 text-right">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600">{{ __('Manage') }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
