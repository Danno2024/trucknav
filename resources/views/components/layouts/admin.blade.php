<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TruckNav Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>
        .admin-nav-link { @apply px-3 py-2 text-sm font-medium rounded-lg transition; }
        .admin-nav-link-active { @apply bg-maroon-50 text-maroon-700; }
        .admin-nav-link-inactive { @apply text-gray-600 hover:text-maroon-700 hover:bg-gray-50; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
    @php
        $currentRoute = request()->route()->getName();
    @endphp

    <nav x-data="{ profileOpen: false, mobileOpen: false }" class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-6">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 shrink-0">
                        <svg class="h-8 w-8 text-maroon-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 16H9m10 0h3v-3.15a1 1 0 0 0-.84-.99L16 11l-2.7-6.08A1 1 0 0 0 12.38 4H5.04a1 1 0 0 0-.96.71L2 11l2.9.97a1 1 0 0 1 .6.91V16"/>
                            <circle cx="6.5" cy="16" r="2.5"/><circle cx="17.5" cy="16" r="2.5"/>
                        </svg>
                        <span class="text-lg font-bold text-maroon-800">TruckNav</span>
                        <span class="px-2 py-0.5 bg-maroon-100 text-maroon-700 text-xs font-semibold rounded-full">Admin</span>
                    </a>

                    <div class="hidden sm:flex sm:items-center sm:space-x-1">
                        <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ $currentRoute === 'admin.dashboard' ? 'admin-nav-link-active' : 'admin-nav-link-inactive' }}">Dashboard</a>
                        <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ str_starts_with($currentRoute, 'admin.users') ? 'admin-nav-link-active' : 'admin-nav-link-inactive' }}">Users</a>
                        <a href="{{ route('admin.restrictions.index') }}" class="admin-nav-link {{ str_starts_with($currentRoute, 'admin.restrictions') ? 'admin-nav-link-active' : 'admin-nav-link-inactive' }}">Restrictions</a>
                        <a href="{{ route('admin.routes.index') }}" class="admin-nav-link {{ str_starts_with($currentRoute, 'admin.routes') ? 'admin-nav-link-active' : 'admin-nav-link-inactive' }}">Routes</a>
                        <a href="{{ route('admin.donations.index') }}" class="admin-nav-link {{ str_starts_with($currentRoute, 'admin.donations') ? 'admin-nav-link-active' : 'admin-nav-link-inactive' }}">Donations</a>
                        <a href="{{ route('admin.reviews.index') }}" class="admin-nav-link {{ str_starts_with($currentRoute, 'admin.reviews') ? 'admin-nav-link-active' : 'admin-nav-link-inactive' }}">Reviews</a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-maroon-700 transition hidden sm:inline">&larr; Back to App</a>

                    <div class="relative">
                        <button @click="profileOpen = !profileOpen" @keydown.escape="profileOpen = false" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-maroon-700 rounded-lg hover:bg-gray-50 transition" aria-haspopup="true">
                            {{ Auth::user()->name }}
                            <svg class="ml-1 h-4 w-4 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                        <div x-show="profileOpen" @click.away="profileOpen = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Dashboard</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                            <hr class="my-1 border-gray-100">
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Log Out</button>
                            </form>
                        </div>
                    </div>

                    <button @click="mobileOpen = !mobileOpen" class="sm:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100" aria-label="Toggle navigation menu" :aria-expanded="mobileOpen">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileOpen" x-transition class="sm:hidden border-t border-gray-100">
            <div class="py-2 px-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ $currentRoute === 'admin.dashboard' ? 'bg-maroon-50 text-maroon-700' : 'text-gray-600 hover:bg-gray-50' }}">Dashboard</a>
                <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-maroon-50 text-maroon-700' : 'text-gray-600 hover:bg-gray-50' }}">Users</a>
                <a href="{{ route('admin.restrictions.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ str_starts_with($currentRoute, 'admin.restrictions') ? 'bg-maroon-50 text-maroon-700' : 'text-gray-600 hover:bg-gray-50' }}">Restrictions</a>
                <a href="{{ route('admin.routes.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ str_starts_with($currentRoute, 'admin.routes') ? 'bg-maroon-50 text-maroon-700' : 'text-gray-600 hover:bg-gray-50' }}">Routes</a>
                <a href="{{ route('admin.donations.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ str_starts_with($currentRoute, 'admin.donations') ? 'bg-maroon-50 text-maroon-700' : 'text-gray-600 hover:bg-gray-50' }}">Donations</a>
                <a href="{{ route('admin.reviews.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ str_starts_with($currentRoute, 'admin.reviews') ? 'bg-maroon-50 text-maroon-700' : 'text-gray-600 hover:bg-gray-50' }}">Reviews</a>
                <hr class="border-gray-100">
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">&larr; Back to App</a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm" role="alert">
                {{ $errors->first() }}
            </div>
        @endif
        {{ $slot }}
    </main>
</body>
</html>
