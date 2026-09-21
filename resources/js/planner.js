import L from 'leaflet';
import { initMap, drawRoute, addMarker, addRestrictionMarker, clearMarkers, setView } from './map/index.js';
import { geocode, reverseGeocode } from './map/geocoding.js';
import { getRoute, formatDistance, formatDuration } from './map/routing.js';
import { checkRouteForRestrictions } from './map/restrictions.js';

let map;
let originMarker = null;
let destinationMarker = null;
let waypointMarkers = [];
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
                        popup: '<strong>Origin</strong>',
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
                        popup: '<strong>Destination</strong>',
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
                    const index = waypoints.length + 1;

                    waypoints.push({
                        lat: result.lat,
                        lng: result.lng,
                        address: result.displayName,
                    });

                    const wpMarker = addMarker(result.lat, result.lng, {
                        title: `Stop ${index}`,
                        popup: `<strong>Stop ${index}</strong><br>${result.displayName}`,
                    });

                    const wpIcon = L.divIcon({
                        className: 'waypoint-marker',
                        html: `<div style="
                            width: 24px;
                            height: 24px;
                            background: #1d4ed8;
                            border: 2px solid white;
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
                            color: white;
                            font-size: 12px;
                            font-weight: bold;
                        ">${index}</div>`,
                        iconSize: [24, 24],
                        iconAnchor: [12, 12],
                        popupAnchor: [0, -14],
                    });

                    const marker = L.marker([result.lat, result.lng], { icon: wpIcon })
                        .bindPopup(`<strong>Stop ${index}</strong><br>${result.displayName}`)
                        .addTo(map);

                    waypointMarkers.push(marker);

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
                <span class="text-xs font-medium text-white bg-blue-600 rounded-full w-6 h-6 flex items-center justify-center">${i + 1}</span>
                <span class="text-sm text-gray-700 truncate max-w-[200px]">${wp.address}</span>
            </div>
            <button type="button" class="text-red-500 hover:text-red-700 text-sm font-bold remove-waypoint" data-index="${i}">&times;</button>
        </div>
    `).join('');

    container.querySelectorAll('.remove-waypoint').forEach((btn) => {
        btn.addEventListener('click', () => {
            const index = parseInt(btn.dataset.index);
            waypoints.splice(index, 1);

            if (waypointMarkers[index]) {
                map.removeLayer(waypointMarkers[index]);
                waypointMarkers.splice(index, 1);
            }

            renumberWaypointMarkers();
            renderWaypointsList(container);
            tryAutoRoute();
        });
    });
}

function renumberWaypointMarkers() {
    waypointMarkers.forEach((marker, i) => {
        const index = i + 1;
        const wpIcon = L.divIcon({
            className: 'waypoint-marker',
            html: `<div style="
                width: 24px;
                height: 24px;
                background: #1d4ed8;
                border: 2px solid white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 4px rgba(0,0,0,0.3);
                color: white;
                font-size: 12px;
                font-weight: bold;
            ">${index}</div>`,
            iconSize: [24, 24],
            iconAnchor: [12, 12],
            popupAnchor: [0, -14],
        });
        marker.setIcon(wpIcon);
        marker.setPopupContent(`<strong>Stop ${index}</strong><br>${waypoints[i].address}`);
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
            const icon = L.divIcon({
                className: 'origin-marker',
                html: `<div style="
                    width: 32px;
                    height: 32px;
                    background: #16a34a;
                    border: 3px solid white;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 2px 6px rgba(0,0,0,0.4);
                "><span style="color:white;font-size:16px;font-weight:bold;">A</span></div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16],
                popupAnchor: [0, -18],
            });

            originMarker = L.marker([lat, lng], { icon, draggable: true })
                .bindPopup('<strong>Origin</strong>')
                .addTo(map);

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
            const icon = L.divIcon({
                className: 'destination-marker',
                html: `<div style="
                    width: 32px;
                    height: 32px;
                    background: #dc2626;
                    border: 3px solid white;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 2px 6px rgba(0,0,0,0.4);
                "><span style="color:white;font-size:16px;font-weight:bold;">B</span></div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16],
                popupAnchor: [0, -18],
            });

            destinationMarker = L.marker([lat, lng], { icon, draggable: true })
                .bindPopup('<strong>Destination</strong>')
                .addTo(map);

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
        hideRestrictionWarnings();

        const routeResult = await getRoute(allWaypoints, 'car');

        currentRoute = routeResult;

        drawRoute(routeResult.geometry);

        updateRouteSummary(routeResult);

        const restrictions = window.__restrictions || [];
        const warnings = checkRouteForRestrictions(routeResult.geometry, restrictions, vehicleProfile);

        if (warnings.length > 0) {
            showRestrictionWarnings(warnings);
        }

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
            showRouteError('Please plan a route first.');
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
            origin_address: originInput ? originInput.value : 'Origin',
            origin_lat: origin.lat,
            origin_lng: origin.lng,
            destination_address: destInput ? destInput.value : 'Destination',
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

        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving...';

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
                showRouteError('');
                const successEl = document.getElementById('route-success');
                if (successEl) {
                    successEl.textContent = data.message;
                    successEl.classList.remove('hidden');
                    setTimeout(() => successEl.classList.add('hidden'), 3000);
                }
            } else {
                const errorMsg = data.errors
                    ? Object.values(data.errors).flat().join(', ')
                    : (data.message || 'Unknown error');
                showRouteError('Save failed: ' + errorMsg);
            }
        } catch (err) {
            console.error('Save error:', err);
            showRouteError('Error saving route. Please try again.');
        } finally {
            saveBtn.disabled = false;
            saveBtn.textContent = 'Save Route';
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

function showRestrictionWarnings(warnings) {
    const container = document.getElementById('route-warnings');
    if (!container) return;

    const severityColors = {
        critical: 'bg-red-100 border-red-400 text-red-800',
        high: 'bg-orange-100 border-orange-400 text-orange-800',
        medium: 'bg-yellow-100 border-yellow-400 text-yellow-800',
        low: 'bg-blue-100 border-blue-400 text-blue-800',
    };

    const severityIcons = {
        critical: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z',
        high: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z',
        medium: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z',
        low: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    };

    container.innerHTML = warnings.map(w => `
        <div class="border-l-4 p-3 mb-2 rounded-r-lg ${severityColors[w.severity] || severityColors.medium}">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="${severityIcons[w.severity] || severityIcons.medium}" />
                </svg>
                <div>
                    <div class="font-semibold text-sm">${w.label}</div>
                    <div class="text-xs mt-0.5">${w.restriction.address}</div>
                    ${w.description && w.description !== 'No details available' ? `<div class="text-xs mt-1 opacity-75">${w.description}</div>` : ''}
                </div>
            </div>
        </div>
    `).join('');

    container.classList.remove('hidden');
}

function hideRestrictionWarnings() {
    const container = document.getElementById('route-warnings');
    if (container) {
        container.innerHTML = '';
        container.classList.add('hidden');
    }
}
