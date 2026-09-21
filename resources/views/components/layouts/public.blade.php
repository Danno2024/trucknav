<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="{{ $metaDescription ?? 'Free route planning for heavy vehicle operators. Plan truck, bus and coach routes avoiding low bridges, weight restrictions and road hazards across Australia.' }}">

        <title>{{ $title ?? config('app.name', 'TruckNav') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-white text-gray-900">
        @include('partials.navbar')

        <main>
            {{ $slot }}
        </main>

        @include('partials.footer')

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('a[href*="/#"]').forEach(function (link) {
                    link.addEventListener('click', function (e) {
                        var hash = this.getAttribute('href').split('/#')[1];
                        if (!hash) return;

                        var target = document.getElementById(hash);
                        if (!target) return;

                        if (window.location.pathname === '/') {
                            e.preventDefault();
                            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            history.pushState(null, '', '#' + hash);
                        }
                    });
                });
            });
        </script>

        @stack('scripts')
    </body>
</html>
