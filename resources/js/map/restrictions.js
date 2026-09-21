const RESTRICTION_RADIUS_METERS = 100;

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

function pointToLineDistance(pointLat, pointLng, lineStartLat, lineStartLng, lineEndLat, lineEndLng) {
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
    if (!vehicleProfile) return true;

    const typeMap = {
        height_limit: vehicleProfile.height_m,
        low_bridge: vehicleProfile.height_m,
        width_limit: vehicleProfile.width_m,
        weight_limit: vehicleProfile.weight_kg ? vehicleProfile.weight_kg / 1000 : null,
    };

    const vehicleValue = typeMap[restriction.restriction_type];
    if (vehicleValue === null || vehicleValue === undefined) return true;

    const restrictionValue = parseFloat(restriction.description);
    if (isNaN(restrictionValue)) return true;

    if (restriction.restriction_type === 'weight_limit') {
        return vehicleValue > restrictionValue;
    }

    return vehicleValue > restrictionValue;
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
