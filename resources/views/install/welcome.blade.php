<x-install-layout subtitle="Step 1 of 4 — Welcome">
    <h2 class="text-lg font-semibold mb-4">Welcome to PAFFAR</h2>
    <p class="text-sm text-gray-600 mb-6">
        This wizard connects PAFFAR to your MySQL database, creates your admin
        account, and lets you optionally load demo content so you can see the
        platform in action before inviting real users.
    </p>

    <ul class="space-y-2 mb-6">
        @foreach ($checks as $label => $passed)
            <li class="flex items-center gap-2 text-sm">
                <span class="{{ $passed ? 'text-green-600' : 'text-red-600' }}">{{ $passed ? '✓' : '✗' }}</span>
                <span class="{{ $passed ? 'text-gray-700' : 'text-red-700 font-medium' }}">{{ $label }}</span>
            </li>
        @endforeach
    </ul>

    @unless ($allPassed)
        <p class="text-sm text-red-700 mb-6">
            Some requirements above aren't met. Fix them on the server before continuing.
        </p>
    @endunless

    <a href="{{ route('install.database') }}"
       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700">
        Get Started
    </a>
</x-install-layout>
