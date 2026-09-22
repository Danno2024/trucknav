@php
    $currentRoute = request()->route()->getName();
@endphp
<div class="min-h-screen bg-gray-100">
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 shrink-0">
                        <svg class="h-8 w-8 text-maroon-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" role="img" aria-label="TruckNav logo">
                            <path d="M14 16H9m10 0h3v-3.15a1 1 0 0 0-.84-.99L16 11l-2.7-6.08A1 1 0 0 0 12.38 4H5.04a1 1 0 0 0-.96.71L2 11l2.9.97a1 1 0 0 1 .6.91V16"/>
                            <circle cx="6.5" cy="16" r="2.5"/><circle cx="17.5" cy="16" r="2.5"/>
                        </svg>
                        <span class="text-lg font-bold text-maroon-800">TruckNav</span>
                        <span class="ml-1 px-2 py-0.5 bg-maroon-100 text-maroon-700 text-xs font-semibold rounded-full">Admin</span>
                    </a>

                    <div class="hidden sm:flex sm:items-center sm:ms-8 sm:space-x-1">
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 text-sm font-medium rounded-lg {{ $currentRoute === 'admin.dashboard' ? 'bg-maroon-50 text-maroon-700' : 'text-gray-600 hover:text-maroon-700 hover:bg-gray-50' }} transition">Dashboard</a>
                        <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-sm font-medium rounded-lg {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-maroon-50 text-maroon-700' : 'text-gray-600 hover:text-maroon-700 hover:bg-gray-50' }} transition">Users</a>
                        <a href="{{ route('admin.restrictions.index') }}" class="px-3 py-2 text-sm font-medium rounded-lg {{ str_starts_with($currentRoute, 'admin.restrictions') ? 'bg-maroon-50 text-maroon-700' : 'text-gray-600 hover:text-maroon-700 hover:bg-gray-50' }} transition">Restrictions</a>
                        <a href="{{ route('admin.routes.index') }}" class="px-3 py-2 text-sm font-medium rounded-lg {{ str_starts_with($currentRoute, 'admin.routes') ? 'bg-maroon-50 text-maroon-700' : 'text-gray-600 hover:text-maroon-700 hover:bg-gray-50' }} transition">Routes</a>
                        <a href="{{ route('admin.donations.index') }}" class="px-3 py-2 text-sm font-medium rounded-lg {{ str_starts_with($currentRoute, 'admin.donations') ? 'bg-maroon-50 text-maroon-700' : 'text-gray-600 hover:text-maroon-700 hover:bg-gray-50' }} transition">Donations</a>
                        <a href="{{ route('admin.reviews.index') }}" class="px-3 py-2 text-sm font-medium rounded-lg {{ str_starts_with($currentRoute, 'admin.reviews') ? 'bg-maroon-50 text-maroon-700' : 'text-gray-600 hover:text-maroon-700 hover:bg-gray-50' }} transition">Reviews</a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-maroon-700 transition hidden sm:inline">← Back to App</a>
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition" aria-haspopup="true">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
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
</div>
