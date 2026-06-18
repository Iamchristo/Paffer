<x-install-layout subtitle="Step 3 of 4 — Admin Account">
    <h2 class="text-lg font-semibold mb-4">Create your admin account</h2>
    <p class="text-sm text-gray-600 mb-6">
        This account gets full access to the admin dashboard and the
        seller/tutor verification queue. You'll be logged in as this user
        once installation finishes.
    </p>

    <form method="POST" action="{{ route('install.admin.store') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" value="Name" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}" required autofocus />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email') }}" required />
        </div>

        <div>
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirm Password" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
        </div>

        <x-primary-button>Create Admin Account</x-primary-button>
    </form>
</x-install-layout>
