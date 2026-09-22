const VALHALLA_BASE = 'https://valhalla1.openstreetmap.de';
const OSRM_BASE = 'https://router.project-osrm.org';

function decodePolyline(encoded) {
    const coords = [];
    let index = 0;
    let lat = 0;
    let lng = 0;

    while (index < encoded.length) {
        let b;
        let shift = 0;
        let result = 0;
        do {
            b = encoded.charCodeAt(index++) - 63;
            result |= (b & 0x1f) << shift;
            shift += 5;
        } while (b >= 0x20);
        const dlat = (result & 1) ? ~(result >> 1) : (result >> 1);
        lat += dlat;

        shift = 0;
        result = 0;
        do {
            b = encoded.charCodeAt(index++) - 63;
            result |= (b & 0x1f) << shift;
            shift += 5;
        } while (b >= 0x20);
        const dlng = (result & 1) ? ~(result >> 1) : (result >> 1);
        lng += dlng;

        coords.push([lng / 1e6, lat / 1e6]);
    }
    return coords;
}

export function hasTruckDimensions(vehicleProfile) {
    if (!vehicleProfile) return false;
    return vehicleProfile.height_m != null || vehicleProfile.width_m != null ||
           vehicleProfile.weight_kg != null || vehicleProfile.length_m != null;
}

function buildValhallaRequest(waypoints, vehicleProfile) {
    const locations = waypoints.map((wp, i) => {
        const loc = { lat: wp.lat, lon: wp.lng, type: 'break' };
        if (i === 0) loc.heading = 0;
        return loc;
    });

    const truckOpts = {};
    if (vehicleProfile.height_m != null) truckOpts.height = vehicleProfile.height_m;
    if (vehicleProfile.width_m != null) truckOpts.width = vehicleProfile.width_m;
    if (vehicleProfile.length_m != null) truckOpts.length = vehicleProfile.length_m;
    if (vehicleProfile.weight_kg != null) truckOpts.weight = vehicleProfile.weight_kg / 1000;

    return {
        locations,
        costing: 'truck',
        costing_options: { truck: truckOpts },
        directions_options: { units: 'kilometers', language: 'en-US' },
        shape_format: 'polyline6',
    };
}

async function getValhallaRoute(waypoints, vehicleProfile) {
    const body = buildValhallaRequest(waypoints, vehicleProfile);
    const response = await fetch(`${VALHALLA_BASE}/route`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body),
    });

    if (!response.ok) {
        throw new Error(`Valhalla routing request failed: ${response.status}`);
    }

    const data = await response.json();

    if (data.error) {
        throw new Error(data.error || 'No route found');
    }

    const trip = data.trip;
    if (!trip || !trip.legs || trip.legs.length === 0) {
        throw new Error('No route found');
    }

    const geometry = [];
    trip.legs.forEach((leg, i) => {
        const legCoords = decodePolyline(leg.shape);
        if (i > 0 && legCoords.length > 0) {
            legCoords.shift();
        }
        geometry.push(...legCoords);
    });

    const legs = trip.legs.map(leg => ({
        distance: leg.summary.length * 1000,
        duration: leg.summary.time,
        steps: leg.maneuvers.map(maneuver => ({
            instruction: maneuver.type,
            modifier: null,
            name: maneuver.street_names ? maneuver.street_names[0] : '',
            street_names: maneuver.street_names || [],
            distance: maneuver.length * 1000,
            duration: maneuver.time,
            type: maneuver.type,
            text: maneuver.instruction,
        })),
    }));

    return {
        geometry,
        distance: trip.summary.length * 1000,
        duration: trip.summary.time,
        legs,
        engine: 'valhalla',
    };
}

async function getOsrmRoute(waypoints) {
    const coords = waypoints.map(wp => `${wp.lng},${wp.lat}`).join(';');
    const params = new URLSearchParams({
        geometries: 'geojson',
        overview: 'full',
        steps: 'true',
        annotations: 'true',
    });

    const response = await fetch(`${OSRM_BASE}/route/v1/car/${coords}?${params}`);
    if (!response.ok) throw new Error('Routing request failed');

    const data = await response.json();
    if (data.code !== 'Ok' || !data.routes || data.routes.length === 0) {
        throw new Error(data.message || 'No route found');
    }

    const route = data.routes[0];

    return {
        geometry: route.geometry.coordinates,
        distance: route.distance,
        duration: route.duration,
        legs: route.legs.map(leg => ({
            distance: leg.distance,
            duration: leg.duration,
            steps: leg.steps.map(step => ({
                instruction: step.maneuver.type,
                modifier: step.maneuver.modifier || null,
                name: step.name,
                street_names: step.ref ? [step.ref] : (step.name ? [step.name] : []),
                distance: step.distance,
                duration: step.duration,
                geometry: step.geometry.coordinates,
                type: null,
                text: null,
            })),
        })),
        engine: 'osrm',
    };
}

export async function getRoute(waypoints, profile = 'car', vehicleProfile = null) {
    if (waypoints.length < 2) {
        throw new Error('At least 2 waypoints required');
    }

    if (hasTruckDimensions(vehicleProfile)) {
        try {
            return await getValhallaRoute(waypoints, vehicleProfile);
        } catch (e) {
            console.warn('[TruckNav] Valhalla failed, falling back to OSRM:', e.message);
            return await getOsrmRoute(waypoints);
        }
    }

    return await getOsrmRoute(waypoints);
}

export function formatDistance(meters) {
    if (meters >= 1000) {
        return `${(meters / 1000).toFixed(1)} km`;
    }
    return `${Math.round(meters)} m`;
}

export function formatDuration(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);

    if (hours > 0) {
        return `${hours}h ${minutes}m`;
    }
    return `${minutes}m`;
}
