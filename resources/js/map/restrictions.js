const RESTRICTION_RADIUS_METERS = 1000;

function haversineDistance(lat1, lng1, lat2, lng2) {
    const R = 6371000;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLng / 2) * Math.sin(dLng / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

export function pointToLineDistance(pointLat, pointLng, lineStartLat, lineStartLng, lineEndLat, lineEndLng) {
    const d1 = haversineDistance(pointLat, pointLng, lineStartLat, lineStartLng);
    const d2 = haversineDistance(pointLat, pointLng, lineEndLat, lineEndLng);
    const lineLength = haversineDistance(lineStartLat, lineStartLng, lineEndLat, lineEndLng);

    if (lineLength === 0) return d1;

    if (d1 * d1 >= lineLength * lineLength + d2 * d2) return d2;
    if (d2 * d2 >= lineLength * lineLength + d1 * d1) return d1;

    const s = (lineLength + d1 + d2) / 2;
    const area = Math.sqrt(s * (s - lineLength) * (s - d1) * (s - d2));
    return (2 * area) / lineLength;
}

function isRouteNearRestriction(routeGeometry, restrictionLat, restrictionLng) {
    for (let i = 0; i < routeGeometry.length - 1; i++) {
        const [lng1, lat1] = routeGeometry[i];
        const [lng2, lat2] = routeGeometry[i + 1];

        const dist = pointToLineDistance(
            restrictionLat, restrictionLng,
            lat1, lng1,
            lat2, lng2
        );

        if (dist < RESTRICTION_RADIUS_METERS) {
            return true;
        }
    }
    return false;
}

function doesRestrictionApply(restriction, vehicleProfile) {
    if (!vehicleProfile) return false;

    switch (restriction.restriction_type) {
        case 'low_bridge':
        case 'height_limit': {
            if (vehicleProfile.height_m == null) return false;
            const limit = parseFloat(restriction.description);
            if (isNaN(limit)) return true;
            return vehicleProfile.height_m > limit;
        }

        case 'width_limit': {
            if (vehicleProfile.width_m == null) return false;
            const limit = parseFloat(restriction.description);
            if (isNaN(limit)) return true;
            return vehicleProfile.width_m > limit;
        }

        case 'weight_limit': {
            if (vehicleProfile.weight_kg == null) return false;
            const limitTonnes = parseFloat(restriction.description);
            if (isNaN(limitTonnes)) return true;
            const vehicleTonnes = vehicleProfile.weight_kg / 1000;
            return vehicleTonnes > limitTonnes;
        }

        case 'road_ban':
        case 'rough_road':
        case 'other':
        default:
            return true;
    }
}

export function checkRouteForRestrictions(routeGeometry, restrictions, vehicleProfile) {
    const warnings = [];

    if (!restrictions || restrictions.length === 0) return warnings;
    if (!routeGeometry || routeGeometry.length === 0) return warnings;

    for (const restriction of restrictions) {
        if (restriction.status !== 'active') continue;

        const nearRoute = isRouteNearRestriction(
            routeGeometry,
            restriction.latitude,
            restriction.longitude
        );

        if (nearRoute && doesRestrictionApply(restriction, vehicleProfile)) {
            const typeLabels = {
                low_bridge: 'Low Bridge',
                weight_limit: 'Weight Limit',
                height_limit: 'Height Limit',
                width_limit: 'Width Limit',
                road_ban: 'Road Ban',
                rough_road: 'Rough Road',
                other: 'Road Restriction',
            };

            warnings.push({
                restriction,
                label: typeLabels[restriction.restriction_type] || 'Restriction',
                message: `${typeLabels[restriction.restriction_type] || 'Restriction'} detected near route: ${restriction.address}`,
                description: restriction.description || 'No details available',
                severity: restriction.severity,
                distance: Math.round(
                    haversineDistance(
                        routeGeometry[0][1], routeGeometry[0][0],
                        restriction.latitude, restriction.longitude
                    )
                ),
            });
        }
    }

    warnings.sort((a, b) => {
        const severityOrder = { critical: 0, high: 1, medium: 2, low: 3 };
        return (severityOrder[a.severity] || 2) - (severityOrder[b.severity] || 2);
    });

    return warnings;
}
