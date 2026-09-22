<nav x-data="{ mobileOpen: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <svg class="h-8 w-8 text-maroon-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" role="img" aria-label="TruckRoute logo">
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h1"/>
                        <path d="M15 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 13.52 8H14"/>
                        <circle cx="7.5" cy="18.5" r="2.5"/>
                        <circle cx="17.5" cy="18.5" r="2.5"/>
                    </svg>
                    <span class="text-xl font-bold text-maroon-800">TruckNav</span>
                </a>
            </div>

            <div class="hidden md:flex md:items-center md:space-x-6">
                <a href="{{ url('/#features') }}" class="text-sm font-medium text-gray-600 hover:text-maroon-700 transition-colors">Features</a>
                <a href="{{ url('/#how-it-works') }}" class="text-sm font-medium text-gray-600 hover:text-maroon-700 transition-colors">How It Works</a>
                <a href="{{ url('/#about') }}" class="text-sm font-medium text-gray-600 hover:text-maroon-700 transition-colors">About</a>
                <a href="{{ url('/#support') }}" class="text-sm font-medium text-gray-600 hover:text-maroon-700 transition-colors">Support</a>
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-maroon-700 transition-colors">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-maroon-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-maroon-800 focus:outline-none focus:ring-2 focus:ring-maroon-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Get Started Free
                    </a>
                @endif
            </div>

            <div class="flex items-center md:hidden">
                <button @click="mobileOpen = !mobileOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out" aria-label="Toggle navigation menu" :aria-expanded="mobileOpen">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileOpen, 'inline-flex': !mobileOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !mobileOpen, 'inline-flex': mobileOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': mobileOpen, 'hidden': !mobileOpen}" class="hidden md:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ url('/#features') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 hover:text-maroon-700 hover:bg-gray-50">Features</a>
            <a href="{{ url('/#how-it-works') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 hover:text-maroon-700 hover:bg-gray-50">How It Works</a>
            <a href="{{ url('/#about') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 hover:text-maroon-700 hover:bg-gray-50">About</a>
            <a href="{{ url('/#support') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 hover:text-maroon-700 hover:bg-gray-50">Support</a>
        </div>
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="space-y-1">
                <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 hover:text-maroon-700 hover:bg-gray-50">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-maroon-700 hover:bg-gray-50">Register</a>
                @endif
            </div>
        </div>
    </div>
</nav>
