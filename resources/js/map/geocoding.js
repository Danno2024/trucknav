const NOMINATIM_BASE = 'https://nominatim.openstreetmap.org';

export async function geocode(address) {
    const params = new URLSearchParams({
        q: address,
        format: 'json',
        limit: 5,
        countrycodes: 'au',
        addressdetails: 1,
    });

    const response = await fetch(`${NOMINATIM_BASE}/search?${params}`, {
        headers: {
            'Accept': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Geocoding request failed');
    }

    const results = await response.json();

    return results.map(result => ({
        displayName: result.display_name,
        lat: parseFloat(result.lat),
        lng: parseFloat(result.lon),
        type: result.type,
        importance: result.importance,
    }));
}

export async function reverseGeocode(lat, lng) {
    const params = new URLSearchParams({
        lat,
        lon: lng,
        format: 'json',
        addressdetails: 1,
    });

    const response = await fetch(`${NOMINATIM_BASE}/reverse?${params}`, {
        headers: {
            'Accept': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Reverse geocoding request failed');
    }

    const result = await response.json();

    return {
        displayName: result.display_name,
        lat: parseFloat(result.lat),
        lng: parseFloat(result.lon),
        address: result.address || {},
    };
}
