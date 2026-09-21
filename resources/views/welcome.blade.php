<x-layouts.public>
    <x-slot name="title">TruckNav - Free Route Planning for Heavy Vehicles</x-slot>
    <x-slot name="metaDescription">Free route planning for Australian truck, bus and coach drivers. Plan safe routes avoiding low bridges, weight restrictions and road hazards.</x-slot>

    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-br from-maroon-900 via-maroon-800 to-maroon-700 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 1200 600" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14 16H9m10 0h3v-3.15a1 1 0 0 0-.84-.99L16 11l-2.7-6.08A1 1 0 0 0 12.38 4H5.04a1 1 0 0 0-.96.71L2 11l2.9.97a1 1 0 0 1 .6.91V16" stroke="white" stroke-width="1.5" transform="translate(500, 250) scale(12)" opacity="0.3"/>
                <circle cx="6.5" cy="16" r="2.5" stroke="white" stroke-width="1.5" transform="translate(500, 250) scale(12)" opacity="0.3"/>
                <circle cx="17.5" cy="16" r="2.5" stroke="white" stroke-width="1.5" transform="translate(500, 250) scale(12)" opacity="0.3"/>
            </svg>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    Plan Safe Routes for<br>
                    <span class="text-maroon-300">Heavy Vehicles</span>
                </h1>
                <p class="text-lg md:text-xl text-maroon-200 mb-8 max-w-2xl mx-auto">
                    The free route planner built for truck, bus and coach drivers across Australia.
                    Avoid low bridges, weight restrictions, and road hazards before you hit the road.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 bg-white text-maroon-800 font-semibold rounded-lg hover:bg-maroon-50 transition-colors text-lg">
                        Get Started Free
                    </a>
                    <a href="/#features" class="inline-flex items-center justify-center px-8 py-3 border-2 border-white text-white font-semibold rounded-lg hover:bg-white/10 transition-colors text-lg">
                        See Features
                    </a>
                </div>
                <p class="mt-6 text-sm text-maroon-300">No credit card required. Free forever for Australian drivers.</p>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Everything You Need to Plan Safe Routes</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Built specifically for heavy vehicle operators, with features that matter most to you.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-maroon-100 rounded-lg flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-maroon-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Truck-Safe Routing</h3>
                    <p class="text-gray-600">Routes calculated with your vehicle dimensions in mind. Avoid low bridges, weight-restricted roads, and narrow passages automatically.</p>
                </div>

                {{-- Feature 2 --}}
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-maroon-100 rounded-lg flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-maroon-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Multi-Stop Planning</h3>
                    <p class="text-gray-600">Add multiple stops and let TruckNav optimise the order for the most efficient route. Save time and fuel on every trip.</p>
                </div>

                {{-- Feature 3 --}}
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-maroon-100 rounded-lg flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-maroon-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">User-Reported Hazards</h3>
                    <p class="text-gray-600">Real road restrictions reported by real drivers. Know about hazards before you encounter them, and report new ones to help others.</p>
                </div>

                {{-- Feature 4 --}}
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-maroon-100 rounded-lg flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-maroon-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Save & Load Routes</h3>
                    <p class="text-gray-600">Create an account to save your favourite routes. Load them anytime, share with colleagues, or print for your records.</p>
                </div>

                {{-- Feature 5 --}}
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-maroon-100 rounded-lg flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-maroon-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Interactive Map</h3>
                    <p class="text-gray-600">Full-screen interactive map powered by OpenStreetMap. Click anywhere to report hazards, view restrictions, and plan routes visually.</p>
                </div>

                {{-- Feature 6 --}}
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-maroon-100 rounded-lg flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-maroon-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Vehicle Profiles</h3>
                    <p class="text-gray-600">Enter your truck, bus or coach dimensions once. TruckNav remembers and applies restrictions automatically for every route.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works Section --}}
    <section id="how-it-works" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">How TruckNav Works</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Three simple steps to plan your next safe heavy vehicle route.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-maroon-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Enter Your Vehicle</h3>
                    <p class="text-gray-600">Input your truck or bus dimensions — height, weight, width, and length. TruckNav uses these to find safe routes.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-maroon-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Plan Your Route</h3>
                    <p class="text-gray-600">Enter your origin, destination, and any stops. TruckNav calculates the safest, most efficient route for your vehicle.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-maroon-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Hit the Road</h3>
                    <p class="text-gray-600">Review your route, check for any hazard warnings, and drive with confidence knowing your route is truck-safe.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- About Section --}}
    <section id="about" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Built by Drivers, for Drivers</h2>
                    <p class="text-lg text-gray-600 mb-4">
                        TruckNav was created to solve a real problem: finding safe routes for heavy vehicles in Australia shouldn't be difficult or expensive.
                    </p>
                    <p class="text-lg text-gray-600 mb-4">
                        We believe every truck, bus, and coach driver deserves access to route planning that accounts for their vehicle's specific restrictions. That's why TruckNav is free to use.
                    </p>
                    <p class="text-lg text-gray-600 mb-6">
                        Our road restriction data comes from the community — drivers like you who report low bridges, weight limits, and other hazards they encounter on the road.
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-maroon-700">100%</div>
                            <div class="text-sm text-gray-500">Free to Use</div>
                        </div>
                        <div class="w-px h-12 bg-gray-300"></div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-maroon-700">Community</div>
                            <div class="text-sm text-gray-500">Driven Data</div>
                        </div>
                        <div class="w-px h-12 bg-gray-300"></div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-maroon-700">Australia</div>
                            <div class="text-sm text-gray-500">Wide Coverage</div>
                        </div>
                    </div>
                </div>
                <div class="bg-maroon-100 rounded-2xl p-8 flex items-center justify-center min-h-[400px]">
                    <svg class="w-48 h-48 text-maroon-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <path d="M14 16H9m10 0h3v-3.15a1 1 0 0 0-.84-.99L16 11l-2.7-6.08A1 1 0 0 0 12.38 4H5.04a1 1 0 0 0-.96.71L2 11l2.9.97a1 1 0 0 1 .6.91V16"/>
                        <circle cx="6.5" cy="16" r="2.5"/>
                        <circle cx="17.5" cy="16" r="2.5"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    {{-- Support / Donate Section --}}
    <section id="support" class="py-20 bg-maroon-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Support TruckNav</h2>
            <p class="text-lg text-maroon-200 max-w-2xl mx-auto mb-8">
                TruckNav is free and always will be. If it's helped you plan a safer route, consider leaving a review or making a small donation to help keep the project running.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('donate') }}" class="inline-flex items-center justify-center px-8 py-3 bg-white text-maroon-800 font-semibold rounded-lg hover:bg-maroon-50 transition-colors text-lg">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106z"/>
                    </svg>
                    Make a Donation
                </a>
                <a href="{{ url('/#features') }}" class="inline-flex items-center justify-center px-8 py-3 border-2 border-white text-white font-semibold rounded-lg hover:bg-white/10 transition-colors text-lg">
                    Leave a Review
                </a>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Ready to Plan Your First Safe Route?</h2>
            <p class="text-lg text-gray-600 mb-8">Join Australian drivers who are already using TruckNav to plan safer, more efficient routes.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 bg-maroon-700 text-white font-semibold rounded-lg hover:bg-maroon-800 transition-colors text-lg">
                Get Started Free
            </a>
        </div>
    </section>
</x-layouts.public>
