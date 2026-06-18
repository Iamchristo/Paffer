<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Install PAFFAR</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col items-center pt-10 pb-10 px-4">
            <h1 class="text-2xl font-bold text-gray-800">PAFFAR Installation</h1>
            @isset($subtitle)
                <p class="text-sm text-gray-500 mt-1 mb-6">{{ $subtitle }}</p>
            @endisset

            <div class="w-full max-w-2xl bg-white shadow-md rounded-lg p-8 mt-4">
                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </body>
</html>
