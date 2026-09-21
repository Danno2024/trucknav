import L from 'leaflet';
import { initMap, drawRoute, addMarker, addRestrictionMarker, clearMarkers, setView, getMap } from './map/index.js';
import { geocode, reverseGeocode } from './map/geocoding.js';
import { getRoute, formatDistance, formatDuration } from './map/routing.js';
import { checkRouteForRestrictions, pointToLineDistance } from './map/restrictions.js';

let map;
let originMarker = null;
let destinationMarker = null;
let waypointMarkers = [];
let currentRoute = null;
let waypoints = [];
let hazardMode = false;
let hazardPin = null;
let vehicleProfile = {
    type: 'truck',
    weight_kg: null,
    height_m: null,
    width_m: null,
    length_m: null,
};

function recheckRestrictions() {
    if (!currentRoute) return;
    const restrictions = window.__restrictions || [];
    console.log('[TruckNav] Re-checking restrictions with profile:', vehicleProfile);
    console.log('[TruckNav] Restrictions count:', restrictions.length);
    hideRestrictionWarnings();
    const warnings = checkRouteForRestrictions(currentRoute.geometry, restrictions, vehicleProfile);
    console.log('[TruckNav] Warnings found:', warnings.length);
    if (warnings.length > 0) {
        showRestrictionWarnings(warnings);
    } else {
        showNoWarnings();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const mapEl = document.getElementById('map');
    console.log('[TruckNav] DOMContentLoaded, map element:', !!mapEl, 'restrictions:', window.__restrictions ? window.__restrictions.length : 'undefined');
    if (!mapEl) return;

    map = initMap('map', [-25.2744, 133.7751], 5);

    initOriginSearch();
    initDestinationSearch();
    initWaypointSearch();
    initVehicleProfile();
    initMapClick();
    initSaveRoute();
    initHazardReporting();
    initPrintRoute();
    initReviewModal();
    loadRestrictions();
    initVerifyRestrictions();

    if (window.__savedRoute) {
        loadSavedRoute(window.__savedRoute);
    }
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
            recheckRestrictions();
        });
    }

    if (weightInput) {
        weightInput.addEventListener('input', () => {
            vehicleProfile.weight_kg = weightInput.value ? parseInt(weightInput.value) : null;
            recheckRestrictions();
        });
    }

    if (heightInput) {
        heightInput.addEventListener('input', () => {
            vehicleProfile.height_m = heightInput.value ? parseFloat(heightInput.value) : null;
            recheckRestrictions();
        });
    }

    if (widthInput) {
        widthInput.addEventListener('input', () => {
            vehicleProfile.width_m = widthInput.value ? parseFloat(widthInput.value) : null;
            recheckRestrictions();
        });
    }

    if (lengthInput) {
        lengthInput.addEventListener('input', () => {
            vehicleProfile.length_m = lengthInput.value ? parseFloat(lengthInput.value) : null;
            recheckRestrictions();
        });
    }
}

function initMapClick() {
    map.on('click', (e) => {
        if (hazardMode) return;
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

const MAX_AVOID_ATTEMPTS = 3;
const AVOID_OFFSET_METERS = 800;

async function tryAutoRoute() {
    if (!originMarker || !destinationMarker) return;

    const baseWaypoints = [];
    const origin = originMarker.getLatLng();
    const dest = destinationMarker.getLatLng();

    baseWaypoints.push({ lat: origin.lat, lng: origin.lng });
    waypoints.forEach(wp => baseWaypoints.push({ lat: wp.lat, lng: wp.lng }));
    baseWaypoints.push({ lat: dest.lat, lng: dest.lng });

    try {
        showRouteLoading(true);
        hideRestrictionWarnings();

        let routeResult = await getRoute(baseWaypoints, 'car');
        let avoidPoints = [];

        for (let attempt = 0; attempt < MAX_AVOID_ATTEMPTS; attempt++) {
            const restrictions = window.__restrictions || [];
            const warnings = checkRouteForRestrictions(routeResult.geometry, restrictions, vehicleProfile);

            if (warnings.length === 0) break;

            const criticalWarnings = warnings.filter(w => w.severity === 'critical' || w.severity === 'high');
            if (criticalWarnings.length === 0) break;

            console.log(`[TruckNav] Attempt ${attempt + 1}: ${criticalWarnings.length} restrictions on route, rerouting around...`);

            for (const warning of criticalWarnings) {
                const restriction = warning.restriction;
                const avoidPt = calculateAvoidWaypoint(routeResult.geometry, restriction.latitude, restriction.longitude);
                if (avoidPt) {
                    avoidPoints.push(avoidPt);
                    console.log(`[TruckNav]   Adding avoid waypoint at ${avoidPt.lat.toFixed(5)}, ${avoidPt.lng.toFixed(5)} for: ${restriction.address}`);
                }
            }

            const rerouteWaypoints = [];
            rerouteWaypoints.push(baseWaypoints[0]);

            for (const wp of baseWaypoints.slice(1, -1)) {
                rerouteWaypoints.push(wp);
            }

            for (const ap of avoidPoints) {
                rerouteWaypoints.push(ap);
            }

            rerouteWaypoints.push(baseWaypoints[baseWaypoints.length - 1]);

            try {
                routeResult = await getRoute(rerouteWaypoints, 'car');
            } catch {
                console.log('[TruckNav] Reroute failed, keeping previous route');
                break;
            }
        }

        currentRoute = routeResult;
        drawRoute(routeResult.geometry);
        updateRouteSummary(routeResult);
        recheckRestrictions();
        showRouteActions(true);
    } catch (err) {
        console.error('[TruckNav] Routing error:', err);
        showRouteError('Could not calculate route. Please try different locations.');
    } finally {
        showRouteLoading(false);
    }
}

function calculateAvoidWaypoint(routeGeometry, restrictionLat, restrictionLng) {
    let bestSegIdx = 0;
    let bestDist = Infinity;

    for (let i = 0; i < routeGeometry.length - 1; i++) {
        const [lng1, lat1] = routeGeometry[i];
        const [lng2, lat2] = routeGeometry[i + 1];
        const dist = pointToLineDistance(restrictionLat, restrictionLng, lat1, lng1, lat2, lng2);
        if (dist < bestDist) {
            bestDist = dist;
            bestSegIdx = i;
        }
    }

    const [segLng1, segLat1] = routeGeometry[bestSegIdx];
    const [segLng2, segLat2] = routeGeometry[Math.min(bestSegIdx + 1, routeGeometry.length - 1)];

    const dLat = segLat2 - segLat1;
    const dLng = segLng2 - segLng1;
    const len = Math.sqrt(dLat * dLat + dLng * dLng);
    if (len === 0) return null;

    const perpLat = -dLng / len;
    const perpLng = dLat / len;

    const offsetDeg = AVOID_OFFSET_METERS / 111320;

    const dotProduct = perpLat * (restrictionLat - segLat1) + perpLng * (restrictionLng - segLng1);
    const direction = dotProduct >= 0 ? -1 : 1;

    return {
        lat: restrictionLat + direction * perpLat * offsetDeg,
        lng: restrictionLng + direction * perpLng * offsetDeg,
    };
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

function simplifyGeometry(coords, tolerance = 0.001) {
    if (coords.length <= 2) return coords;
    const result = [coords[0]];
    for (let i = 1; i < coords.length - 1; i++) {
        const prev = result[result.length - 1];
        const dx = coords[i][0] - prev[0];
        const dy = coords[i][1] - prev[1];
        if (Math.sqrt(dx * dx + dy * dy) >= tolerance) {
            result.push(coords[i]);
        }
    }
    result.push(coords[coords.length - 1]);
    return result;
}

function initSaveRoute() {
    const saveBtn = document.getElementById('save-route-btn');
    const modal = document.getElementById('save-modal');
    const backdrop = document.getElementById('save-modal-backdrop');
    const cancelBtn = document.getElementById('save-modal-cancel');
    const confirmBtn = document.getElementById('save-modal-confirm');
    const nameInput = document.getElementById('route-name-input');
    const modalError = document.getElementById('save-modal-error');

    console.log('[TruckNav] initSaveRoute:', { saveBtn: !!saveBtn, modal: !!modal, backdrop: !!backdrop });

    if (!saveBtn || !modal) {
        console.warn('[TruckNav] Save button or modal not found in DOM');
        return;
    }

    function openModal() {
        nameInput.value = '';
        modalError.classList.add('hidden');
        modal.classList.remove('hidden');
        nameInput.focus();
    }

    function closeModal() {
        modal.classList.add('hidden');
        nameInput.value = '';
        modalError.classList.add('hidden');
    }

    cancelBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);
    nameInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') confirmBtn.click();
        if (e.key === 'Escape') closeModal();
    });

    saveBtn.addEventListener('click', async () => {
        if (!currentRoute || !originMarker || !destinationMarker) {
            showRouteError('Please plan a route first.');
            return;
        }
        openModal();
    });

    confirmBtn.addEventListener('click', async () => {
        const name = nameInput.value.trim();
        if (!name) {
            modalError.textContent = 'Please enter a route name.';
            modalError.classList.remove('hidden');
            return;
        }

        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Saving...';
        modalError.classList.add('hidden');

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
            route_geometry: simplifyGeometry(currentRoute.geometry),
        };

        try {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const saveUrl = window.__saveUrl || '/planner/save';
            console.log('[TruckNav] Saving to:', saveUrl);
            const response = await fetch(saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            console.log('[TruckNav] Save response status:', response.status, response.statusText);

            if (!response.ok) {
                const text = await response.text();
                console.error('[TruckNav] Save error response:', text.substring(0, 500));
                modalError.textContent = `Server error (${response.status}). Please try again.`;
                modalError.classList.remove('hidden');
                return;
            }

            const data = await response.json();

            if (data.success) {
                closeModal();
                const successEl = document.getElementById('route-success');
                if (successEl) {
                    successEl.textContent = data.message;
                    successEl.classList.remove('hidden');
                    setTimeout(() => successEl.classList.add('hidden'), 3000);
                }
                setTimeout(() => showReviewModal(), 1000);
            } else {
                const errorMsg = data.errors
                    ? Object.values(data.errors).flat().join(', ')
                    : (data.message || 'Unknown error');
                modalError.textContent = 'Save failed: ' + errorMsg;
                modalError.classList.remove('hidden');
            }
        } catch (err) {
            console.error('Save error:', err);
            modalError.textContent = 'Error saving route. Please try again.';
            modalError.classList.remove('hidden');
        } finally {
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Save';
        }
    });
}

function loadRestrictions() {
    const restrictions = window.__restrictions || [];
    console.log('[TruckNav] Restrictions loaded:', restrictions.length, restrictions);
    if (restrictions.length > 0) {
        restrictions.forEach(restriction => {
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

function showNoWarnings() {
    const container = document.getElementById('route-warnings');
    if (!container) return;

    const hasVehicleProfile = vehicleProfile.height_m || vehicleProfile.width_m || vehicleProfile.weight_kg;

    if (hasVehicleProfile) {
        container.innerHTML = `
            <div class="border-l-4 border-green-400 p-3 mb-2 rounded-r-lg bg-green-50 text-green-800">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <div>
                        <div class="font-semibold text-sm">No hazards on route</div>
                        <div class="text-xs mt-0.5">No known restrictions conflict with your vehicle dimensions.</div>
                    </div>
                </div>
            </div>`;
        container.classList.remove('hidden');
    } else {
        container.innerHTML = `
            <div class="border-l-4 border-blue-400 p-3 mb-2 rounded-r-lg bg-blue-50 text-blue-800">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <div class="font-semibold text-sm">Enter vehicle dimensions for hazard warnings</div>
                        <div class="text-xs mt-0.5">Add your vehicle height, width and weight above to check for restrictions.</div>
                    </div>
                </div>
            </div>`;
        container.classList.remove('hidden');
    }
}

function loadSavedRoute(route) {
    console.log('[TruckNav] Loading saved route:', route.name);

    const originIcon = L.divIcon({
        className: 'origin-marker',
        html: `<div style="width:32px;height:32px;background:#16a34a;border:3px solid white;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(0,0,0,0.4);"><span style="color:white;font-size:16px;font-weight:bold;">A</span></div>`,
        iconSize: [32, 32],
        iconAnchor: [16, 16],
        popupAnchor: [0, -18],
    });

    const destIcon = L.divIcon({
        className: 'destination-marker',
        html: `<div style="width:32px;height:32px;background:#dc2626;border:3px solid white;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(0,0,0,0.4);"><span style="color:white;font-size:16px;font-weight:bold;">B</span></div>`,
        iconSize: [32, 32],
        iconAnchor: [16, 16],
        popupAnchor: [0, -18],
    });

    originMarker = L.marker([route.origin_lat, route.origin_lng], { icon: originIcon, draggable: true })
        .bindPopup('<strong>Origin</strong>')
        .addTo(map);

    originMarker.on('dragend', (e) => {
        const pos = e.target.getLatLng();
        reverseGeocode(pos.lat, pos.lng).then((rev) => {
            document.getElementById('origin-search').value = rev.displayName;
        });
    });

    destinationMarker = L.marker([route.destination_lat, route.destination_lng], { icon: destIcon, draggable: true })
        .bindPopup('<strong>Destination</strong>')
        .addTo(map);

    destinationMarker.on('dragend', (e) => {
        const pos = e.target.getLatLng();
        reverseGeocode(pos.lat, pos.lng).then((rev) => {
            document.getElementById('destination-search').value = rev.displayName;
        });
    });

    document.getElementById('origin-search').value = route.origin_address;
    document.getElementById('destination-search').value = route.destination_address;

    if (route.waypoints && Array.isArray(route.waypoints)) {
        const list = document.getElementById('waypoints-list');
        route.waypoints.forEach((wp, i) => {
            waypoints.push({ lat: wp.lat, lng: wp.lng, address: wp.address });

            const wpIcon = L.divIcon({
                className: 'waypoint-marker',
                html: `<div style="width:24px;height:24px;background:#1d4ed8;border:2px solid white;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 4px rgba(0,0,0,0.3);color:white;font-size:12px;font-weight:bold;">${i + 1}</div>`,
                iconSize: [24, 24],
                iconAnchor: [12, 12],
                popupAnchor: [0, -14],
            });

            const marker = L.marker([wp.lat, wp.lng], { icon: wpIcon })
                .bindPopup(`<strong>Stop ${i + 1}</strong><br>${wp.address}`)
                .addTo(map);

            waypointMarkers.push(marker);
        });
        renderWaypointsList(list);
    }

    if (route.vehicle_type) {
        document.getElementById('vehicle-type').value = route.vehicle_type;
        vehicleProfile.type = route.vehicle_type;
    }
    if (route.vehicle_weight_kg) {
        document.getElementById('vehicle-weight').value = route.vehicle_weight_kg;
        vehicleProfile.weight_kg = route.vehicle_weight_kg;
    }
    if (route.vehicle_height_m) {
        document.getElementById('vehicle-height').value = route.vehicle_height_m;
        vehicleProfile.height_m = route.vehicle_height_m;
    }
    if (route.vehicle_width_m) {
        document.getElementById('vehicle-width').value = route.vehicle_width_m;
        vehicleProfile.width_m = route.vehicle_width_m;
    }
    if (route.vehicle_length_m) {
        document.getElementById('vehicle-length').value = route.vehicle_length_m;
        vehicleProfile.length_m = route.vehicle_length_m;
    }

    const allLats = [route.origin_lat, route.destination_lat];
    const allLngs = [route.origin_lng, route.destination_lng];
    if (route.waypoints) {
        route.waypoints.forEach(wp => {
            allLats.push(wp.lat);
            allLngs.push(wp.lng);
        });
    }
    const bounds = L.latLngBounds(allLats.map((lat, i) => [lat, allLngs[i]]));
    map.fitBounds(bounds, { padding: [50, 50] });

    tryAutoRoute();
}

function initHazardReporting() {
    const reportBtn = document.getElementById('report-hazard-btn');
    const hint = document.getElementById('hazard-mode-hint');
    const modal = document.getElementById('hazard-modal');
    const backdrop = document.getElementById('hazard-modal-backdrop');
    const cancelBtn = document.getElementById('hazard-modal-cancel');
    const confirmBtn = document.getElementById('hazard-modal-confirm');
    const addressDisplay = document.getElementById('hazard-address-display');
    const modalError = document.getElementById('hazard-modal-error');
    const modalSuccess = document.getElementById('hazard-modal-success');
    const typeSelect = document.getElementById('hazard-type');
    const severitySelect = document.getElementById('hazard-severity');
    const limitInput = document.getElementById('hazard-limit');
    const descInput = document.getElementById('hazard-description');

    if (!reportBtn || !modal) return;

    reportBtn.addEventListener('click', () => {
        hazardMode = !hazardMode;
        if (hazardMode) {
            reportBtn.classList.remove('bg-amber-600', 'hover:bg-amber-700');
            reportBtn.classList.add('bg-red-600', 'hover:bg-red-700');
            reportBtn.innerHTML = `
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Cancel Reporting`;
            hint.classList.remove('hidden');
            map.getContainer().style.cursor = 'crosshair';
        } else {
            exitHazardMode();
        }
    });

    function exitHazardMode() {
        hazardMode = false;
        reportBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
        reportBtn.classList.add('bg-amber-600', 'hover:bg-amber-700');
        reportBtn.innerHTML = `
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
            Report Hazard`;
        hint.classList.add('hidden');
        map.getContainer().style.cursor = '';
        if (hazardPin) {
            map.removeLayer(hazardPin);
            hazardPin = null;
        }
    }

    map.on('click', function handleHazardClick(e) {
        if (!hazardMode) return;

        if (hazardPin) map.removeLayer(hazardPin);

        const hazardIcon = L.divIcon({
            className: 'hazard-pin',
            html: `<div style="width:30px;height:30px;background:#f59e0b;border:3px solid white;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(0,0,0,0.4);">
                <svg style="width:16px;height:16px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>`,
            iconSize: [30, 30],
            iconAnchor: [15, 15],
            popupAnchor: [0, -17],
        });

        hazardPin = L.marker([e.latlng.lat, e.latlng.lng], { icon: hazardIcon })
            .addTo(map);

        addressDisplay.textContent = 'Loading address...';
        reverseGeocode(e.latlng.lat, e.latlng.lng).then((rev) => {
            addressDisplay.textContent = rev.displayName;
        }).catch(() => {
            addressDisplay.textContent = `${e.latlng.lat.toFixed(5)}, ${e.latlng.lng.toFixed(5)}`;
        });

        openModal();
    });

    function openModal() {
        modalError.classList.add('hidden');
        modalSuccess.classList.add('hidden');
        limitInput.value = '';
        descInput.value = '';
        modal.classList.remove('hidden');
        typeSelect.focus();
    }

    function closeModal() {
        modal.classList.add('hidden');
        exitHazardMode();
    }

    cancelBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);

    confirmBtn.addEventListener('click', async () => {
        if (!hazardPin) {
            modalError.textContent = 'Please click on the map to place a hazard pin.';
            modalError.classList.remove('hidden');
            return;
        }

        const latlng = hazardPin.getLatLng();
        const limitVal = limitInput.value.trim();

        let description = descInput.value.trim();
        if (limitVal) {
            description = limitVal + (description ? ' - ' + description : '');
        }

        const payload = {
            restriction_type: typeSelect.value,
            address: addressDisplay.textContent,
            latitude: latlng.lat,
            longitude: latlng.lng,
            severity: severitySelect.value,
            description: description || null,
        };

        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Submitting...';
        modalError.classList.add('hidden');

        try {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const url = window.__reportRestrictionUrl || '/planner/report-restriction';
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json();

            if (response.ok && data.success) {
                modalSuccess.textContent = 'Hazard reported! Thank you for helping other drivers.';
                modalSuccess.classList.remove('hidden');

                const newRestriction = {
                    id: data.restriction.id,
                    restriction_type: payload.restriction_type,
                    address: payload.address,
                    latitude: payload.latitude,
                    longitude: payload.longitude,
                    severity: payload.severity,
                    description: payload.description,
                    verified: false,
                    verification_count: 1,
                    status: 'active',
                };

                window.__restrictions.push(newRestriction);
                addRestrictionMarker(newRestriction);

                setTimeout(() => {
                    closeModal();
                }, 1500);

                recheckRestrictions();
            } else {
                const errorMsg = data.errors
                    ? Object.values(data.errors).flat().join(', ')
                    : (data.message || 'Failed to submit report.');
                modalError.textContent = errorMsg;
                modalError.classList.remove('hidden');
            }
        } catch (err) {
            console.error('Hazard report error:', err);
            modalError.textContent = 'Error submitting report. Please try again.';
            modalError.classList.remove('hidden');
        } finally {
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Submit Report';
        }
    });
}

function initPrintRoute() {
    const printBtn = document.getElementById('print-route-btn');
    if (!printBtn) return;

    printBtn.addEventListener('click', () => {
        if (!currentRoute) {
            showRouteError('Please plan a route first.');
            return;
        }

        const originInput = document.getElementById('origin-search');
        const destInput = document.getElementById('destination-search');
        const originName = originInput ? originInput.value : 'Origin';
        const destName = destInput ? destInput.value : 'Destination';
        const distance = formatDistance(currentRoute.distance);
        const duration = formatDuration(currentRoute.duration);

        let directions = [];
        if (currentRoute.legs) {
            currentRoute.legs.forEach((leg, legIdx) => {
                leg.steps.forEach(step => {
                    directions.push(step);
                });
            });
        }

        function formatInstruction(type, modifier, name) {
            const turnMap = {
                'turn': modifier ? `Turn ${modifier}` : 'Turn',
                'new name': 'Continue onto',
                'depart': 'Depart',
                'arrive': 'Arrive at destination',
                'merge': modifier ? `Merge ${modifier}` : 'Merge',
                'roundabout': 'At roundabout',
                'exit roundabout': 'Exit roundabout',
                'fork': modifier ? `Keep ${modifier}` : 'Continue',
                'end of road': modifier ? `At end of road, turn ${modifier}` : 'Continue',
                'continue': modifier ? `Continue ${modifier}` : 'Continue',
            };

            let instruction = turnMap[type] || type;
            if (name) {
                instruction += ` onto ${name}`;
            }
            return instruction;
        }

        const warnings = [];
        const warningContainer = document.getElementById('route-warnings');
        if (warningContainer && !warningContainer.classList.contains('hidden')) {
            const warningEls = warningContainer.querySelectorAll('[class*="border-l-4"]');
            warningEls.forEach(el => {
                const text = el.textContent.trim();
                if (text) warnings.push(text);
            });
        }

        let directionsHTML = '';
        directions.forEach((step, i) => {
            const instruction = formatInstruction(step.instruction, step.modifier, step.name);
            const stepDist = formatDistance(step.distance);
            const stepDur = formatDuration(step.duration);
            directionsHTML += `
                <tr class="border-b border-gray-200">
                    <td class="py-2 pr-3 text-sm text-gray-500 w-8 text-center">${i + 1}</td>
                    <td class="py-2 text-sm text-gray-900">${instruction}</td>
                    <td class="py-2 text-sm text-gray-600 text-right whitespace-nowrap">${stepDist}</td>
                    <td class="py-2 text-sm text-gray-600 text-right whitespace-nowrap">${stepDur}</td>
                </tr>`;
        });

        const warningsHTML = warnings.length > 0 ? `
            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded">
                <h4 class="font-bold text-red-800 text-sm mb-1">Hazards on Route</h4>
                <ul class="text-sm text-red-700 list-disc list-inside">${warnings.map(w => `<li>${w}</li>`).join('')}</ul>
            </div>` : '';

        const vehicleParts = [];
        if (vehicleProfile.height_m) vehicleParts.push(`H: ${vehicleProfile.height_m}m`);
        if (vehicleProfile.width_m) vehicleParts.push(`W: ${vehicleProfile.width_m}m`);
        if (vehicleProfile.length_m) vehicleParts.push(`L: ${vehicleProfile.length_m}m`);
        if (vehicleProfile.weight_kg) vehicleParts.push(`${vehicleProfile.weight_kg}kg`);
        const vehicleStr = vehicleParts.length > 0 ? ` (${vehicleParts.join(', ')})` : '';

        const now = new Date();
        const dateStr = now.toLocaleDateString('en-AU', { day: 'numeric', month: 'long', year: 'numeric' });
        const timeStr = now.toLocaleTimeString('en-AU', { hour: '2-digit', minute: '2-digit' });

        const printHTML = `
<!DOCTYPE html>
<html>
<head>
    <title>TruckNav Route Directions</title>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; color: #111; }
        h1 { color: #ab1d44; font-size: 22px; margin-bottom: 4px; }
        .subtitle { color: #666; font-size: 13px; margin-bottom: 20px; }
        .summary { display: flex; gap: 24px; margin-bottom: 20px; padding: 12px; background: #fdf2f8; border-radius: 8px; }
        .summary-item { text-align: center; }
        .summary-value { font-size: 18px; font-weight: bold; color: #ab1d44; }
        .summary-label { font-size: 11px; color: #666; }
        .route-info { margin-bottom: 20px; }
        .route-info p { margin: 4px 0; font-size: 14px; }
        .route-info strong { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th { background: #ab1d44; color: white; text-align: left; padding: 8px; font-size: 13px; }
        .footer { margin-top: 30px; text-align: center; font-size: 11px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
        @media print { body { padding: 10px; } }
    </style>
</head>
<body>
    <h1>TruckNav - Route Directions</h1>
    <div class="subtitle">Printed ${dateStr} at ${timeStr}</div>

    <div class="route-info">
        <p><strong>From:</strong> ${originName}</p>
        <p><strong>To:</strong> ${destName}</p>
        ${vehicleStr ? `<p><strong>Vehicle:</strong> ${vehicleProfile.type}${vehicleStr}</p>` : ''}
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-value">${distance}</div>
            <div class="summary-label">Distance</div>
        </div>
        <div class="summary-item">
            <div class="summary-value">${duration}</div>
            <div class="summary-label">Est. Duration</div>
        </div>
        <div class="summary-item">
            <div class="summary-value">${directions.length}</div>
            <div class="summary-label">Steps</div>
        </div>
    </div>

    ${warningsHTML}

    <table>
        <thead>
            <tr>
                <th class="w-8">#</th>
                <th>Direction</th>
                <th class="text-right">Distance</th>
                <th class="text-right">Duration</th>
            </tr>
        </thead>
        <tbody>
            ${directionsHTML}
        </tbody>
    </table>

    <div class="footer">
        <strong>TruckNav</strong> - Heavy Vehicle Route Planning for Australia<br>
        Drive safe. Check actual road conditions before travel.<br>
        Powered by Moorcam
    </div>
</body>
</html>`;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(printHTML);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
        }, 500);
    });
}

function initVerifyRestrictions() {
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.verify-hazard-btn');
        if (!btn) return;

        const restrictionId = btn.dataset.restrictionId;
        if (!restrictionId) return;

        btn.disabled = true;
        btn.textContent = 'Verifying...';

        try {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const response = await fetch(window.__reportRestrictionUrl || '/planner/report-restriction', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    restriction_id: restrictionId,
                }),
            });

            const data = await response.json();

            if (response.ok && data.success) {
                const restriction = window.__restrictions.find(r => r.id == restrictionId);
                if (restriction) {
                    restriction.verification_count = (restriction.verification_count || 0) + 1;
                    if (restriction.verification_count >= 3) {
                        restriction.verified = true;
                    }
                }
                btn.textContent = 'Verified!';
                btn.classList.remove('bg-green-600', 'hover:bg-green-700');
                btn.classList.add('bg-green-400');
                recheckRestrictions();
            } else {
                btn.textContent = 'Failed';
                btn.disabled = false;
                setTimeout(() => { btn.textContent = 'Verify'; }, 2000);
            }
        } catch (err) {
            console.error('Verify error:', err);
            btn.textContent = 'Error';
            btn.disabled = false;
            setTimeout(() => { btn.textContent = 'Verify'; }, 2000);
        }
    });
}

let selectedRating = 0;

function initReviewModal() {
    const modal = document.getElementById('review-modal');
    const backdrop = document.getElementById('review-modal-backdrop');
    const skipBtn = document.getElementById('review-modal-skip');
    const submitBtn = document.getElementById('review-modal-submit');
    const commentInput = document.getElementById('review-comment');
    const errorEl = document.getElementById('review-modal-error');
    const successEl = document.getElementById('review-modal-success');
    const stars = document.querySelectorAll('.review-star');

    if (!modal || !skipBtn) return;

    stars.forEach(star => {
        star.addEventListener('click', () => {
            selectedRating = parseInt(star.dataset.rating);
            updateStars();
            submitBtn.disabled = false;
        });

        star.addEventListener('mouseenter', () => {
            const rating = parseInt(star.dataset.rating);
            stars.forEach((s, i) => {
                s.classList.toggle('text-yellow-400', i < rating);
                s.classList.toggle('text-gray-300', i >= rating);
            });
        });
    });

    document.getElementById('review-stars').addEventListener('mouseleave', updateStars);

    function updateStars() {
        stars.forEach((s, i) => {
            s.classList.toggle('text-yellow-400', i < selectedRating);
            s.classList.toggle('text-gray-300', i >= selectedRating);
        });
    }

    skipBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        selectedRating = 0;
        updateStars();
        commentInput.value = '';
        submitBtn.disabled = true;
    });

    backdrop.addEventListener('click', () => {
        skipBtn.click();
    });

    submitBtn.addEventListener('click', async () => {
        if (selectedRating === 0) {
            errorEl.textContent = 'Please select a star rating.';
            errorEl.classList.remove('hidden');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';
        errorEl.classList.add('hidden');

        try {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const response = await fetch(window.__reviewUrl || '/reviews', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    rating: selectedRating,
                    comment: commentInput.value.trim() || null,
                    platform: 'app',
                }),
            });

            const data = await response.json();

            if (response.ok && data.success) {
                successEl.textContent = data.message;
                successEl.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    successEl.classList.add('hidden');
                    selectedRating = 0;
                    updateStars();
                    commentInput.value = '';
                    submitBtn.disabled = true;
                }, 1500);
            } else {
                const errorMsg = data.errors
                    ? Object.values(data.errors).flat().join(', ')
                    : (data.message || 'Failed to submit review.');
                errorEl.textContent = errorMsg;
                errorEl.classList.remove('hidden');
            }
        } catch (err) {
            console.error('Review error:', err);
            errorEl.textContent = 'Error submitting review. Please try again.';
            errorEl.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit';
        }
    });
}

function showReviewModal() {
    const modal = document.getElementById('review-modal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}
