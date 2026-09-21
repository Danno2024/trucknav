const OSRM_BASE = 'https://router.project-osrm.org';

export async function getRoute(waypoints, profile = 'car') {
    if (waypoints.length < 2) {
        throw new Error('At least 2 waypoints required');
    }

    const coords = waypoints
        .map(wp => `${wp.lng},${wp.lat}`)
        .join(';');

    const params = new URLSearchParams({
        geometries: 'geojson',
        overview: 'full',
        steps: 'true',
        annotations: 'true',
    });

    const url = `${OSRM_BASE}/route/v1/${profile}/${coords}?${params}`;

    const response = await fetch(url);

    if (!response.ok) {
        throw new Error('Routing request failed');
    }

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
                name: step.name,
                distance: step.distance,
                duration: step.duration,
                geometry: step.geometry.coordinates,
            })),
        })),
    };
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
