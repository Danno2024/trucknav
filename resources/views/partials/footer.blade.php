<footer class="bg-gray-900 text-gray-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="h-8 w-8 text-maroon-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 16H9m10 0h3v-3.15a1 1 0 0 0-.84-.99L16 11l-2.7-6.08A1 1 0 0 0 12.38 4H5.04a1 1 0 0 0-.96.71L2 11l2.9.97a1 1 0 0 1 .6.91V16"/>
                        <circle cx="6.5" cy="16" r="2.5"/>
                        <circle cx="17.5" cy="16" r="2.5"/>
                    </svg>
                    <span class="text-xl font-bold text-white">TruckNav</span>
                </div>
                <p class="text-sm text-gray-400 max-w-md">
                    Free route planning for heavy vehicle operators across Australia. Plan safe routes that avoid low bridges, weight restrictions, and road hazards.
                </p>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ url('/#features') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Features</a></li>
                    <li><a href="{{ url('/#how-it-works') }}" class="text-sm text-gray-400 hover:text-white transition-colors">How It Works</a></li>
                    <li><a href="{{ url('/#about') }}" class="text-sm text-gray-400 hover:text-white transition-colors">About</a></li>
                    <li><a href="{{ url('/#support') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Support</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Account</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Log in</a></li>
                    @if (Route::has('register'))
                        <li><a href="{{ route('register') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Register</a></li>
                    @endif
                    <li><a href="{{ url('/dashboard') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Dashboard</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-8 pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center">
            <p class="text-sm text-gray-500">
                &copy; {{ date('Y') }} <a href="https://www.moorcam.com.au" target="_blank">Moorcam Development</a>, Australia. All rights reserved.
            </p>
            <p class="text-sm text-gray-500 mt-2 md:mt-0">
                Powered by <a href="https://www.moorcam.com.au" target="_blank">Moorcam Development</a>
            </p>
        </div>
    </div>
</footer>
