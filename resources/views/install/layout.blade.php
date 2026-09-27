<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TruckRoute Installer — @yield('step', 'Setup')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 min-h-screen font-sans">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
        <div class="flex items-center gap-2 mb-6">
            <svg class="h-10 w-10 text-maroon-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h1"/>
                <path d="M15 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 13.52 8H14"/>
                <circle cx="7.5" cy="18.5" r="2.5"/><circle cx="17.5" cy="18.5" r="2.5"/>
            </svg>
            <div>
                <div class="text-xl font-bold text-maroon-800 leading-none">TruckRoute</div>
                <div class="text-xs text-gray-500 mt-0.5">Installation Wizard</div>
            </div>
        </div>

        @php
            $steps = ['Requirements', 'Database', 'Settings', 'Admin', 'Done'];
            $current = $step ?? 1;
        @endphp
        <div class="flex items-center gap-1.5 mb-6">
            @foreach($steps as $i => $label)
                @php $n = $i + 1; @endphp
                <div class="flex items-center gap-1.5">
                    <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $n < $current ? 'bg-green-100 text-green-700' : ($n === $current ? 'bg-maroon-700 text-white' : 'bg-gray-200 text-gray-500') }}">
                        <span>{{ $n }}</span>
                        <span class="hidden sm:inline">{{ $label }}</span>
                    </div>
                    @if($n < count($steps))
                        <div class="w-4 h-px bg-gray-300"></div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="w-full max-w-xl">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 sm:p-8">
                @yield('content')
            </div>
            <p class="text-center text-xs text-gray-400 mt-6">Powered by TruckRoute</p>
        </div>
    </div>
</body>
</html>
