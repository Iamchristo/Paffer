<x-install-layout subtitle="Step 2 of 4 — Database">
    <h2 class="text-lg font-semibold mb-4">Connect your MySQL database</h2>
    <p class="text-sm text-gray-600 mb-6">
        Enter the MySQL connection details from your hosting control panel
        (cPanel &rarr; MySQL Databases). PAFFAR will create all of its tables
        in this database, so use an empty database or one from a previous
        PAFFAR install.
    </p>

    <form method="POST" action="{{ route('install.database.store') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="host" value="Database Host" />
            <x-text-input id="host" name="host" type="text" class="mt-1 block w-full" value="{{ old('host', $values['host']) }}" required autofocus />
        </div>

        <div>
            <x-input-label for="port" value="Database Port" />
            <x-text-input id="port" name="port" type="text" class="mt-1 block w-full" value="{{ old('port', $values['port']) }}" required />
        </div>

        <div>
            <x-input-label for="database" value="Database Name" />
            <x-text-input id="database" name="database" type="text" class="mt-1 block w-full" value="{{ old('database', $values['database']) }}" required />
        </div>

        <div>
            <x-input-label for="username" value="Database Username" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" value="{{ old('username', $values['username']) }}" required />
        </div>

        <div>
            <x-input-label for="password" value="Database Password" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
        </div>

        <x-primary-button>Test Connection &amp; Continue</x-primary-button>
    </form>
</x-install-layout>
