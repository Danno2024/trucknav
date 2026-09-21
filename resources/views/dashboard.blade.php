<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Welcome back, {{ Auth::user()->name }}!</h3>
                            <p class="text-sm text-gray-500">Plan your next safe route or manage your saved routes.</p>
                        </div>
                        <a href="{{ route('planner') }}" class="inline-flex items-center px-4 py-2 bg-maroon-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-maroon-800 focus:outline-none focus:ring-2 focus:ring-maroon-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            New Route
                        </a>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div class="bg-maroon-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-maroon-700">0</div>
                                <div class="text-sm text-gray-600">Saved Routes</div>
                            </div>
                            <div class="bg-maroon-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-maroon-700">0 km</div>
                                <div class="text-sm text-gray-600">Total Distance</div>
                            </div>
                            <div class="bg-maroon-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-maroon-700">0</div>
                                <div class="text-sm text-gray-600">Hazards Reported</div>
                            </div>
                        </div>

                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No saved routes yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by planning your first route.</p>
                            <div class="mt-6">
                                <a href="{{ route('planner') }}" class="inline-flex items-center px-4 py-2 bg-maroon-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-maroon-800 transition">
                                    Open Route Planner
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
