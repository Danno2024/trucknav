<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TruckNav Admin Login</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center font-sans">
    <div class="w-full max-w-md px-6">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="flex flex-col items-center mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="h-10 w-10 text-maroon-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 16H9m10 0h3v-3.15a1 1 0 0 0-.84-.99L16 11l-2.7-6.08A1 1 0 0 0 12.38 4H5.04a1 1 0 0 0-.96.71L2 11l2.9.97a1 1 0 0 1 .6.91V16"/>
                        <circle cx="6.5" cy="16" r="2.5"/><circle cx="17.5" cy="16" r="2.5"/>
                    </svg>
                    <span class="text-xl font-bold text-maroon-800">TruckNav</span>
                </div>
                <span class="px-3 py-1 bg-maroon-100 text-maroon-700 text-sm font-semibold rounded-full">Admin Panel</span>
            </div>

            @if(session('status'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-maroon-600 shadow-sm focus:ring-maroon-500">
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full px-4 py-2.5 bg-maroon-700 text-white rounded-lg text-sm font-semibold hover:bg-maroon-800 focus:outline-none focus:ring-2 focus:ring-maroon-500 focus:ring-offset-2 transition">
                    Sign in to Admin
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">Powered by TruckNav</p>
    </div>
</body>
</html>
