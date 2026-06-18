<x-install-layout subtitle="Installation Complete">
    <h2 class="text-lg font-semibold mb-4">PAFFAR is ready</h2>
    <p class="text-sm text-gray-600 mb-6">
        Your database is set up{{ $demoDataImported ? ' with demo content' : '' }}
        and your admin account is ready. You're already logged in as the
        admin you just created.
    </p>

    <a href="{{ route('admin.dashboard') }}"
       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700">
        Go to Admin Dashboard
    </a>
</x-install-layout>
