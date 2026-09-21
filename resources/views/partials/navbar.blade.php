<nav x-data="{ mobileOpen: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="/" class="flex items-center gap-2">
                    <svg class="h-8 w-8 text-maroon-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 16H9m10 0h3v-3.15a1 1 0 0 0-.84-.99L16 11l-2.7-6.08A1 1 0 0 0 12.38 4H5.04a1 1 0 0 0-.96.71L2 11l2.9.97a1 1 0 0 1 .6.91V16"/>
                        <circle cx="6.5" cy="16" r="2.5"/>
                        <circle cx="17.5" cy="16" r="2.5"/>
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
                <button @click="mobileOpen = !mobileOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
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
