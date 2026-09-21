import { initMap, drawRoute, addMarker, addRestrictionMarker, clearMarkers, setView } from './map/index.js';
import { geocode, reverseGeocode } from './map/geocoding.js';
import { getRoute, formatDistance, formatDuration } from './map/routing.js';

let map;
let originMarker = null;
let destinationMarker = null;
let currentRoute = null;
let waypoints = [];
let vehicleProfile = {
    type: 'truck',
    weight_kg: null,
    height_m: null,
    width_m: null,
    length_m: null,
};

document.addEventListener('DOMContentLoaded', () => {
    if (!document.getElementById('map')) return;

    map = initMap('map', [-25.2744, 133.7751], 5);

    initOriginSearch();
    initDestinationSearch();
    initWaypointSearch();
    initVehicleProfile();
    initMapClick();
    initSaveRoute();
    loadRestrictions();
});

function initOriginSearch() {
    const input = document.getElementById('origin-search');
    const suggestions = document.getElementById('origin-suggestions');
    if (!input || !suggestions) return;

    let debounceTimer;

    input.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        const query = input.value.trim();

        if (query.length < 3) {
            suggestions.classList.add('hidden');
            return;
        }

        debounceTimer = setTimeout(async () => {
            try {
                const results = await geocode(query);
                renderSuggestions(suggestions, results, (result) => {
                    input.value = result.displayName;
                    suggestions.classList.add('hidden');

                    if (originMarker) map.removeLayer(originMarker);
                    originMarker = addMarker(result.lat, result.lng, {
                        title: 'Origin',
                        draggable: true,
                        popup: `<strong>Origin</strong><br>${result.displayName}`,
                    });

                    originMarker.on('dragend', (e) => {
                        const pos = e.target.getLatLng();
                        reverseGeocode(pos.lat, pos.lng).then((rev) => {
                            input.value = rev.displayName;
                        });
                    });

                    setView(result.lat, result.lng, 13);
                    tryAutoRoute();
                });
            } catch (err) {
                console.error('Geocoding error:', err);
            }
        }, 300);
    });

    input.addEventListener('blur', () => {
        setTimeout(() => suggestions.classList.add('hidden'), 200);
    });
}

function initDestinationSearch() {
    const input = document.getElementById('destination-search');
    const suggestions = document.getElementById('destination-suggestions');
    if (!input || !suggestions) return;

    let debounceTimer;

    input.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        const query = input.value.trim();

        if (query.length < 3) {
            suggestions.classList.add('hidden');
            return;
        }

        debounceTimer = setTimeout(async () => {
            try {
                const results = await geocode(query);
                renderSuggestions(suggestions, results, (result) => {
                    input.value = result.displayName;
                    suggestions.classList.add('hidden');

                    if (destinationMarker) map.removeLayer(destinationMarker);
                    destinationMarker = addMarker(result.lat, result.lng, {
                        title: 'Destination',
                        draggable: true,
                        popup: `<strong>Destination</strong><br>${result.displayName}`,
                    });

                    destinationMarker.on('dragend', (e) => {
                        const pos = e.target.getLatLng();
                        reverseGeocode(pos.lat, pos.lng).then((rev) => {
                            input.value = rev.displayName;
                        });
                    });

                    setView(result.lat, result.lng, 13);
                    tryAutoRoute();
                });
            } catch (err) {
                console.error('Geocoding error:', err);
            }
        }, 300);
    });

    input.addEventListener('blur', () => {
        setTimeout(() => suggestions.classList.add('hidden'), 200);
    });
}

function initWaypointSearch() {
    const input = document.getElementById('waypoint-search');
    const suggestions = document.getElementById('waypoint-suggestions');
    const list = document.getElementById('waypoints-list');
    if (!input || !suggestions) return;

    let debounceTimer;

    input.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        const query = input.value.trim();

        if (query.length < 3) {
            suggestions.classList.add('hidden');
            return;
        }

        debounceTimer = setTimeout(async () => {
            try {
                const results = await geocode(query);
                renderSuggestions(suggestions, results, (result) => {
                    waypoints.push({
                        lat: result.lat,
                        lng: result.lng,
                        address: result.displayName,
                    });

                    input.value = '';
                    suggestions.classList.add('hidden');

                    renderWaypointsList(list);
                    tryAutoRoute();
                });
            } catch (err) {
                console.error('Geocoding error:', err);
            }
        }, 300);
    });

    input.addEventListener('blur', () => {
        setTimeout(() => suggestions.classList.add('hidden'), 200);
    });
}

function renderSuggestions(container, results, onSelect) {
    if (results.length === 0) {
        container.classList.add('hidden');
        return;
    }

    container.innerHTML = results.map((r, i) => `
        <div class="px-3 py-2 hover:bg-maroon-50 cursor-pointer text-sm border-b border-gray-100 last:border-b-0 suggestion-item" data-index="${i}">
            ${r.displayName}
        </div>
    `).join('');

    container.classList.remove('hidden');

    container.querySelectorAll('.suggestion-item').forEach((item) => {
        item.addEventListener('click', () => {
            const index = parseInt(item.dataset.index);
            onSelect(results[index]);
        });
    });
}

function renderWaypointsList(container) {
    if (!container) return;

    if (waypoints.length === 0) {
        container.innerHTML = '<p class="text-sm text-gray-500 italic">No stops added</p>';
        return;
    }

    container.innerHTML = waypoints.map((wp, i) => `
        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-lg mb-2">
            <div class="flex items-center gap-2">
                <span class="text-xs font-medium text-maroon-700 bg-maroon-100 rounded-full w-6 h-6 flex items-center justify-center">${i + 1}</span>
                <span class="text-sm text-gray-700 truncate max-w-[200px]">${wp.address}</span>
            </div>
            <button type="button" class="text-red-500 hover:text-red-700 text-sm remove-waypoint" data-index="${i}">&times;</button>
        </div>
    `).join('');

    container.querySelectorAll('.remove-waypoint').forEach((btn) => {
        btn.addEventListener('click', () => {
            const index = parseInt(btn.dataset.index);
            waypoints.splice(index, 1);
            renderWaypointsList(container);
            tryAutoRoute();
        });
    });
}

function initVehicleProfile() {
    const typeSelect = document.getElementById('vehicle-type');
    const weightInput = document.getElementById('vehicle-weight');
    const heightInput = document.getElementById('vehicle-height');
    const widthInput = document.getElementById('vehicle-width');
    const lengthInput = document.getElementById('vehicle-length');

    if (typeSelect) {
        typeSelect.addEventListener('change', () => {
            vehicleProfile.type = typeSelect.value;
        });
    }

    if (weightInput) {
        weightInput.addEventListener('input', () => {
            vehicleProfile.weight_kg = weightInput.value ? parseInt(weightInput.value) : null;
        });
    }

    if (heightInput) {
        heightInput.addEventListener('input', () => {
            vehicleProfile.height_m = heightInput.value ? parseFloat(heightInput.value) : null;
        });
    }

    if (widthInput) {
        widthInput.addEventListener('input', () => {
            vehicleProfile.width_m = widthInput.value ? parseFloat(widthInput.value) : null;
        });
    }

    if (lengthInput) {
        lengthInput.addEventListener('input', () => {
            vehicleProfile.length_m = lengthInput.value ? parseFloat(lengthInput.value) : null;
        });
    }
}

function initMapClick() {
    map.on('click', (e) => {
        const { lat, lng } = e.latlng;

        if (!originMarker) {
            originMarker = addMarker(lat, lng, {
                title: 'Origin',
                draggable: true,
                popup: '<strong>Origin</strong>',
            });

            reverseGeocode(lat, lng).then((rev) => {
                const input = document.getElementById('origin-search');
                if (input) input.value = rev.displayName;
            });

            originMarker.on('dragend', (ev) => {
                const pos = ev.target.getLatLng();
                reverseGeocode(pos.lat, pos.lng).then((rev) => {
                    const input = document.getElementById('origin-search');
                    if (input) input.value = rev.displayName;
                });
            });
        } else if (!destinationMarker) {
            destinationMarker = addMarker(lat, lng, {
                title: 'Destination',
                draggable: true,
                popup: '<strong>Destination</strong>',
            });

            reverseGeocode(lat, lng).then((rev) => {
                const input = document.getElementById('destination-search');
                if (input) input.value = rev.displayName;
            });

            destinationMarker.on('dragend', (ev) => {
                const pos = ev.target.getLatLng();
                reverseGeocode(pos.lat, pos.lng).then((rev) => {
                    const input = document.getElementById('destination-search');
                    if (input) input.value = rev.displayName;
                });
            });

            tryAutoRoute();
        }
    });
}

async function tryAutoRoute() {
    if (!originMarker || !destinationMarker) return;

    const allWaypoints = [];
    const origin = originMarker.getLatLng();
    const dest = destinationMarker.getLatLng();

    allWaypoints.push({ lat: origin.lat, lng: origin.lng });
    waypoints.forEach(wp => allWaypoints.push({ lat: wp.lat, lng: wp.lng }));
    allWaypoints.push({ lat: dest.lat, lng: dest.lng });

    try {
        showRouteLoading(true);

        const routeResult = await getRoute(allWaypoints, 'car');

        currentRoute = routeResult;

        drawRoute(routeResult.geometry);

        updateRouteSummary(routeResult);

        showRouteActions(true);
    } catch (err) {
        console.error('Routing error:', err);
        showRouteError('Could not calculate route. Please try different locations.');
    } finally {
        showRouteLoading(false);
    }
}

function updateRouteSummary(route) {
    const distanceEl = document.getElementById('route-distance');
    const durationEl = document.getElementById('route-duration');

    if (distanceEl) distanceEl.textContent = formatDistance(route.distance);
    if (durationEl) durationEl.textContent = formatDuration(route.duration);

    const summaryEl = document.getElementById('route-summary');
    if (summaryEl) summaryEl.classList.remove('hidden');
}

function showRouteLoading(show) {
    const loader = document.getElementById('route-loading');
    const summary = document.getElementById('route-summary');

    if (loader) loader.classList.toggle('hidden', !show);
    if (show && summary) summary.classList.add('hidden');
}

function showRouteError(message) {
    const errorEl = document.getElementById('route-error');
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.classList.remove('hidden');
        setTimeout(() => errorEl.classList.add('hidden'), 5000);
    }
}

function showRouteActions(show) {
    const actions = document.getElementById('route-actions');
    if (actions) actions.classList.toggle('hidden', !show);
}

function initSaveRoute() {
    const saveBtn = document.getElementById('save-route-btn');
    if (!saveBtn) return;

    saveBtn.addEventListener('click', async () => {
        if (!currentRoute || !originMarker || !destinationMarker) {
            alert('Please plan a route first');
            return;
        }

        const name = prompt('Enter a name for this route:');
        if (!name) return;

        const origin = originMarker.getLatLng();
        const dest = destinationMarker.getLatLng();

        const originInput = document.getElementById('origin-search');
        const destInput = document.getElementById('destination-search');

        const payload = {
            name,
            origin_address: originInput ? originInput.value : '',
            origin_lat: origin.lat,
            origin_lng: origin.lng,
            destination_address: destInput ? destInput.value : '',
            destination_lat: dest.lat,
            destination_lng: dest.lng,
            waypoints: waypoints,
            vehicle_type: vehicleProfile.type,
            vehicle_weight_kg: vehicleProfile.weight_kg,
            vehicle_height_m: vehicleProfile.height_m,
            vehicle_width_m: vehicleProfile.width_m,
            vehicle_length_m: vehicleProfile.length_m,
            total_distance_km: Math.round(currentRoute.distance / 1000 * 100) / 100,
            total_duration_minutes: Math.round(currentRoute.duration / 60),
            route_geometry: currentRoute.geometry,
        };

        try {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const response = await fetch('/planner/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json();

            if (data.success) {
                alert('Route saved successfully!');
            } else {
                alert('Error saving route: ' + (data.message || 'Unknown error'));
            }
        } catch (err) {
            console.error('Save error:', err);
            alert('Error saving route. Please try again.');
        }
    });
}

function loadRestrictions() {
    if (typeof window.__restrictions !== 'undefined' && window.__restrictions.length > 0) {
        window.__restrictions.forEach(restriction => {
            addRestrictionMarker(restriction);
        });
    }
}
