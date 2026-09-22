<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TruckRoute') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
            <div>
                <a href="/" class="flex items-center gap-2">
                    <x-application-logo class="w-12 h-12 text-maroon-700" />
                    <span class="text-2xl font-bold text-maroon-800">TruckRoute</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>

            <p class="mt-4 text-xs text-gray-500">
                <a href="/" class="hover:text-maroon-700 transition-colors">&larr; Back to TruckRoute</a>
            </p>
        </div>
    </body>
</html>
