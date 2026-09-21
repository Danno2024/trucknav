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

                <div class="mb-4">
                    <button type="button" id="report-hazard-btn"
                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-amber-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        Report Hazard
                    </button>
                    <p id="hazard-mode-hint" class="hidden text-xs text-amber-700 mt-2 text-center font-medium">Click on the map to place a hazard pin</p>
                </div>

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
                    <div class="flex gap-2 mb-2">
                        <button type="button" id="save-route-btn"
                            class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-maroon-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-maroon-800 focus:outline-none focus:ring-2 focus:ring-maroon-500 focus:ring-offset-2 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Save
                        </button>
                        <button type="button" id="print-route-btn"
                            class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print
                        </button>
                    </div>
                </div>

                <p class="text-xs text-gray-400 mt-4 text-center">Tip: Click on the map to set origin and destination points</p>
            </div>
        </div>

        <div class="planner-map">
            <div id="map"></div>
        </div>
    </div>

    <div id="save-modal" class="fixed inset-0 hidden" style="z-index: 10000;">
        <div class="absolute inset-0 bg-black/50" id="save-modal-backdrop"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl p-6 w-full max-w-md" style="z-index: 10001;">
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

    <div id="hazard-modal" class="fixed inset-0 hidden" style="z-index: 10000;">
        <div class="absolute inset-0 bg-black/50" id="hazard-modal-backdrop"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl p-6 w-full max-w-md" style="z-index: 10001;">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Report Hazard</h3>
            <div id="hazard-address-display" class="mb-3 text-sm text-gray-500 italic">Click on the map to set location...</div>
            <div class="mb-3">
                <label for="hazard-type" class="block text-sm font-medium text-gray-700 mb-1">Hazard Type</label>
                <select id="hazard-type" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                    <option value="low_bridge">Low Bridge</option>
                    <option value="weight_limit">Weight Restriction</option>
                    <option value="height_limit">Height Restriction</option>
                    <option value="width_limit">Width Restriction</option>
                    <option value="road_ban">Road Ban</option>
                    <option value="rough_road">Rough Road</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="hazard-severity" class="block text-sm font-medium text-gray-700 mb-1">Severity</label>
                <select id="hazard-severity" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                    <option value="medium" selected>Medium</option>
                    <option value="low">Low</option>
                    <option value="high">High</option>
                    <option value="critical">Critical</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="hazard-limit" class="block text-sm font-medium text-gray-700 mb-1">Limit Value (optional)</label>
                <input type="text" id="hazard-limit" placeholder="e.g. 4.2 (for 4.2m height)"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
            </div>
            <div class="mb-4">
                <label for="hazard-description" class="block text-sm font-medium text-gray-700 mb-1">Additional Details (optional)</label>
                <textarea id="hazard-description" rows="2" placeholder="e.g. Underpass on main road, partially hidden..."
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm"></textarea>
            </div>
            <div id="hazard-modal-error" class="hidden mb-4 bg-red-50 text-red-700 text-sm p-3 rounded-lg"></div>
            <div id="hazard-modal-success" class="hidden mb-4 bg-green-50 text-green-700 text-sm p-3 rounded-lg"></div>
            <div class="flex gap-3">
                <button type="button" id="hazard-modal-cancel"
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-300 transition">
                    Cancel
                </button>
                <button type="button" id="hazard-modal-confirm"
                    class="flex-1 px-4 py-2 bg-amber-600 text-white rounded-lg font-semibold text-sm hover:bg-amber-700 transition">
                    Submit Report
                </button>
            </div>
        </div>
    </div>

    <div id="print-container" class="hidden"></div>

    <div id="review-modal" class="fixed inset-0 hidden" style="z-index: 10000;">
        <div class="absolute inset-0 bg-black/50" id="review-modal-backdrop"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl p-6 w-full max-w-md" style="z-index: 10001;">
            <h3 class="text-lg font-bold text-gray-900 mb-1">How was your experience?</h3>
            <p class="text-sm text-gray-500 mb-4">Your feedback helps us improve TruckNav for all drivers.</p>
            <div id="review-stars" class="flex gap-1 mb-4">
                <button type="button" class="review-star text-3xl text-gray-300 hover:text-yellow-400 transition" data-rating="1">&#9733;</button>
                <button type="button" class="review-star text-3xl text-gray-300 hover:text-yellow-400 transition" data-rating="2">&#9733;</button>
                <button type="button" class="review-star text-3xl text-gray-300 hover:text-yellow-400 transition" data-rating="3">&#9733;</button>
                <button type="button" class="review-star text-3xl text-gray-300 hover:text-yellow-400 transition" data-rating="4">&#9733;</button>
                <button type="button" class="review-star text-3xl text-gray-300 hover:text-yellow-400 transition" data-rating="5">&#9733;</button>
            </div>
            <div class="mb-4">
                <label for="review-comment" class="block text-sm font-medium text-gray-700 mb-1">Comments (optional)</label>
                <textarea id="review-comment" rows="3" placeholder="Tell us what you think..."
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm"></textarea>
            </div>
            <div id="review-modal-error" class="hidden mb-4 bg-red-50 text-red-700 text-sm p-3 rounded-lg"></div>
            <div id="review-modal-success" class="hidden mb-4 bg-green-50 text-green-700 text-sm p-3 rounded-lg"></div>
            <div class="flex gap-3">
                <button type="button" id="review-modal-skip"
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-300 transition">
                    Skip
                </button>
                <button type="button" id="review-modal-submit"
                    class="flex-1 px-4 py-2 bg-maroon-700 text-white rounded-lg font-semibold text-sm hover:bg-maroon-800 transition" disabled>
                    Submit
                </button>
            </div>
        </div>
    </div>

    <script>
        window.__restrictions = {!! json_encode($restrictions->toArray()) !!};
        window.__saveUrl = '{{ url("/planner/save") }}';
        window.__reportRestrictionUrl = '{{ url("/planner/report-restriction") }}';
        window.__reviewUrl = '{{ url("/reviews") }}';
        window.__savedRoute = {!! $savedRoute ? json_encode($savedRoute) : 'null' !!};
        console.log('[TruckNav] Restrictions from server:', window.__restrictions.length, window.__restrictions);
        console.log('[TruckNav] Save URL:', window.__saveUrl);
        console.log('[TruckNav] Saved route to load:', window.__savedRoute ? window.__savedRoute.name : 'none');
    </script>
</x-app-layout>
