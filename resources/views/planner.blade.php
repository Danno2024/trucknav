<x-app-layout>
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

    @push('scripts')
    @vite(['resources/js/planner.js'])
    @endpush

    <style>
        .planner-layout {
            display: flex;
            height: calc(100vh - 64px);
        }
        .planner-sidebar {
            width: 400px;
            min-width: 400px;
            overflow-y: auto;
            background: white;
            border-right: 1px solid #e5e7eb;
        }
        .planner-map {
            flex: 1;
            position: relative;
        }
        #map {
            width: 100%;
            height: 100%;
        }
        .search-wrapper {
            position: relative;
        }
        .suggestions-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .restriction-marker {
            background: transparent;
            border: none;
        }
        @media (max-width: 768px) {
            .planner-layout {
                flex-direction: column;
            }
            .planner-sidebar {
                width: 100%;
                min-width: 100%;
                max-height: 40vh;
            }
            .planner-map {
                min-height: 60vh;
            }
        }
    </style>

    <div class="planner-layout">
        <div class="planner-sidebar">
            <div class="p-4">
                <h1 class="text-lg font-bold text-gray-900 mb-4">Route Planner</h1>

                <div class="mb-4">
                    <label for="origin-search" class="block text-sm font-medium text-gray-700 mb-1">Origin</label>
                    <div class="search-wrapper">
                        <input type="text" id="origin-search" placeholder="Search origin address..."
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                        <div id="origin-suggestions" class="suggestions-dropdown hidden"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="destination-search" class="block text-sm font-medium text-gray-700 mb-1">Destination</label>
                    <div class="search-wrapper">
                        <input type="text" id="destination-search" placeholder="Search destination address..."
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                        <div id="destination-suggestions" class="suggestions-dropdown hidden"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="waypoint-search" class="block text-sm font-medium text-gray-700 mb-1">Add Stop (Optional)</label>
                    <div class="search-wrapper">
                        <input type="text" id="waypoint-search" placeholder="Search stop address..."
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                        <div id="waypoint-suggestions" class="suggestions-dropdown hidden"></div>
                    </div>
                    <div id="waypoints-list" class="mt-2">
                        <p class="text-sm text-gray-500 italic">No stops added</p>
                    </div>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Vehicle Profile</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-2">
                            <label for="vehicle-type" class="block text-xs font-medium text-gray-600 mb-1">Type</label>
                            <select id="vehicle-type" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                                <option value="truck">Truck</option>
                                <option value="bus">Bus</option>
                                <option value="coach">Coach</option>
                            </select>
                        </div>
                        <div>
                            <label for="vehicle-weight" class="block text-xs font-medium text-gray-600 mb-1">Weight (kg)</label>
                            <input type="number" id="vehicle-weight" placeholder="e.g. 42500" min="0"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                        </div>
                        <div>
                            <label for="vehicle-height" class="block text-xs font-medium text-gray-600 mb-1">Height (m)</label>
                            <input type="number" id="vehicle-height" placeholder="e.g. 4.6" min="0" step="0.1"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                        </div>
                        <div>
                            <label for="vehicle-width" class="block text-xs font-medium text-gray-600 mb-1">Width (m)</label>
                            <input type="number" id="vehicle-width" placeholder="e.g. 2.5" min="0" step="0.1"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                        </div>
                        <div>
                            <label for="vehicle-length" class="block text-xs font-medium text-gray-600 mb-1">Length (m)</label>
                            <input type="number" id="vehicle-length" placeholder="e.g. 16.5" min="0" step="0.1"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div id="route-summary" class="hidden mb-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Route Summary</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-maroon-50 rounded-lg p-3">
                            <div class="text-lg font-bold text-maroon-700" id="route-distance">--</div>
                            <div class="text-xs text-gray-600">Distance</div>
                        </div>
                        <div class="bg-maroon-50 rounded-lg p-3">
                            <div class="text-lg font-bold text-maroon-700" id="route-duration">--</div>
                            <div class="text-xs text-gray-600">Duration</div>
                        </div>
                    </div>
                </div>

                <div id="route-loading" class="hidden mb-4 text-center py-4">
                    <div class="inline-flex items-center gap-2 text-sm text-gray-600">
                        <svg class="animate-spin h-4 w-4 text-maroon-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Calculating route...
                    </div>
                </div>

                <div id="route-error" class="hidden mb-4 bg-red-50 text-red-700 text-sm p-3 rounded-lg"></div>

                <div id="route-warnings" class="hidden mb-4"></div>

                <div id="route-success" class="hidden mb-4 bg-green-50 text-green-700 text-sm p-3 rounded-lg"></div>

                <div id="route-actions" class="hidden">
                    <button type="button" id="save-route-btn"
                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-maroon-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-maroon-800 focus:outline-none focus:ring-2 focus:ring-maroon-500 focus:ring-offset-2 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Save Route
                    </button>
                </div>

                <p class="text-xs text-gray-400 mt-4 text-center">Tip: Click on the map to set origin and destination points</p>
            </div>
        </div>

        <div class="planner-map">
            <div id="map"></div>
        </div>
    </div>

    <div id="save-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" id="save-modal-backdrop"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl p-6 w-full max-w-md">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Save Route</h3>
            <div class="mb-4">
                <label for="route-name-input" class="block text-sm font-medium text-gray-700 mb-1">Route Name</label>
                <input type="text" id="route-name-input" placeholder="e.g. Melbourne to Bendigo"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
            </div>
            <div id="save-modal-error" class="hidden mb-4 bg-red-50 text-red-700 text-sm p-3 rounded-lg"></div>
            <div class="flex gap-3">
                <button type="button" id="save-modal-cancel"
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-300 transition">
                    Cancel
                </button>
                <button type="button" id="save-modal-confirm"
                    class="flex-1 px-4 py-2 bg-maroon-700 text-white rounded-lg font-semibold text-sm hover:bg-maroon-800 transition">
                    Save
                </button>
            </div>
        </div>
    </div>

    <script>
        window.__restrictions = @json($restrictions);
    </script>
</x-app-layout>
