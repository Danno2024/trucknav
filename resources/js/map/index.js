import L from 'leaflet';

let map;
let routeLayer;
let markersLayer;

export function initMap(containerId = 'map', center = [-25.2744, 133.7751], zoom = 5) {
    map = L.map(containerId, {
        center,
        zoom,
        zoomControl: true,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);

    routeLayer = L.layerGroup().addTo(map);
    markersLayer = L.layerGroup().addTo(map);

    return map;
}

export function getMap() {
    return map;
}

export function clearRoute() {
    routeLayer.clearLayers();
}

export function clearMarkers() {
    markersLayer.clearLayers();
}

export function drawRoute(geometry, options = {}) {
    clearRoute();

    const coords = geometry.map(coord => [coord[1], coord[0]]);

    const defaultStyle = {
        color: '#ab1d44',
        weight: 5,
        opacity: 0.8,
        dashArray: null,
    };

    const polyline = L.polyline(coords, { ...defaultStyle, ...options }).addTo(routeLayer);

    if (coords.length > 0) {
        map.fitBounds(polyline.getBounds(), { padding: [50, 50] });
    }

    return polyline;
}

export function addMarker(lat, lng, options = {}) {
    const {
        title = '',
        icon = null,
        popup = '',
        draggable = false,
    } = options;

    const markerOptions = { title, draggable };

    if (icon) {
        markerOptions.icon = icon;
    }

    const marker = L.marker([lat, lng], markerOptions).addTo(markersLayer);

    if (popup) {
        marker.bindPopup(popup);
    }

    return marker;
}

export function addRestrictionMarker(restriction) {
    const colors = {
        critical: '#dc2626',
        high: '#ea580c',
        medium: '#d97706',
        low: '#65a30d',
    };

    const icons = {
        low_bridge: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z',
        weight_limit: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z',
        height_limit: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z',
        width_limit: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z',
        road_ban: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z',
        rough_road: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z',
        other: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z',
    };

    const color = colors[restriction.severity] || colors.medium;

    const icon = L.divIcon({
        className: 'restriction-marker',
        html: `<div style="
            width: 28px;
            height: 28px;
            background: ${color};
            border: 2px solid white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            cursor: pointer;
        ">
            <span style="color: white; font-size: 14px; font-weight: bold;">!</span>
        </div>`,
        iconSize: [28, 28],
        iconAnchor: [14, 14],
        popupAnchor: [0, -16],
    });

    const typeLabels = {
        low_bridge: 'Low Bridge',
        weight_limit: 'Weight Limit',
        height_limit: 'Height Limit',
        width_limit: 'Width Limit',
        road_ban: 'Road Ban',
        rough_road: 'Rough Road',
        other: 'Other',
    };

    const popupContent = `
        <div style="min-width: 180px;">
            <strong style="color: ${color};">${typeLabels[restriction.restriction_type] || restriction.restriction_type}</strong>
            <br><small style="color: #666;">${restriction.address}</small>
            <br><small>${restriction.description || 'No description'}</small>
            <br><small style="color: ${restriction.verified ? '#16a34a' : '#d97706'};">
                ${restriction.verified ? 'Verified' : 'Unverified'}
                (${restriction.verification_count} confirmation${restriction.verification_count !== 1 ? 's' : ''})
            </small>
            ${!restriction.verified ? `<br><button class="verify-hazard-btn mt-2 px-3 py-1 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700 cursor-pointer" data-restriction-id="${restriction.id}">Verify</button>` : ''}
        </div>
    `;

    const marker = L.marker([restriction.latitude, restriction.longitude], { icon })
        .bindPopup(popupContent)
        .addTo(markersLayer);

    return marker;
}

export function setView(lat, lng, zoom = 13) {
    map.setView([lat, lng], zoom);
}

export function fitBounds(bounds, padding = [50, 50]) {
    map.fitBounds(bounds, { padding });
}
