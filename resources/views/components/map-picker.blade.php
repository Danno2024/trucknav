@props([
    // 'single' = one pin (restrictions) | 'dual' = origin + destination pins (routes)
    'mode' => 'single',
    'id' => 'map-picker',
    // single mode
    'latName' => 'latitude',
    'lngName' => 'longitude',
    'lat' => null,
    'lng' => null,
    // dual mode (fixed field names matching admin route validation)
    'originLat' => null,
    'originLng' => null,
    'destinationLat' => null,
    'destinationLng' => null,
    'center' => [-28.0, 134.0],
    'zoom' => 5,
    'height' => '320px',
])

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div>
    <div id="{{ $id }}" class="relative z-0 rounded-lg border border-gray-300 overflow-hidden" style="height: {{ $height }};"></div>

    @if($mode === 'dual')
        <div class="flex items-center gap-2 mt-2">
            <span class="text-xs text-gray-500">Placing:</span>
            <button type="button" id="{{ $id }}-btn-origin" class="px-3 py-1 text-xs font-medium rounded-full bg-green-600 text-white">A · Origin</button>
            <button type="button" id="{{ $id }}-btn-destination" class="px-3 py-1 text-xs font-medium rounded-full bg-gray-200 text-gray-600">B · Destination</button>
            <span id="{{ $id }}-hint" class="text-xs text-gray-400 ml-1">Click the map to place the origin pin, then the destination pin. Drag pins to adjust.</span>
        </div>
        <input type="hidden" name="origin_lat" id="{{ $id }}-origin-lat" value="{{ $originLat }}">
        <input type="hidden" name="origin_lng" id="{{ $id }}-origin-lng" value="{{ $originLng }}">
        <input type="hidden" name="destination_lat" id="{{ $id }}-destination-lat" value="{{ $destinationLat }}">
        <input type="hidden" name="destination_lng" id="{{ $id }}-destination-lng" value="{{ $destinationLng }}">
        <p id="{{ $id }}-readout" class="text-xs text-gray-500 mt-1"></p>
    @else
        <p id="{{ $id }}-hint" class="text-xs text-gray-400 mt-2">Click the map to place the pin. Drag the pin to adjust.</p>
        <input type="hidden" name="{{ $latName }}" id="{{ $id }}-lat" value="{{ $lat }}">
        <input type="hidden" name="{{ $lngName }}" id="{{ $id }}-lng" value="{{ $lng }}">
        <p id="{{ $id }}-readout" class="text-xs text-gray-500 mt-1"></p>
    @endif
</div>

<script>
(function () {
    function boot() {
        var mapId = @json($id);
        var mode = @json($mode);
        var map = L.map(mapId).setView(@json($center), @json($zoom));

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        function dotIcon(color, label) {
            return L.divIcon({
                className: '',
                html: '<div style="width:30px;height:30px;border-radius:50%;background:' + color + ';color:#fff;'
                    + 'display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;'
                    + 'border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,0.4);">' + label + '</div>',
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });
        }

        function fmt(v) { return (Math.round(v * 100000) / 100000).toFixed(5); }

        if (mode === 'dual') {
            var originLatEl = document.getElementById(mapId + '-origin-lat');
            var originLngEl = document.getElementById(mapId + '-origin-lng');
            var destLatEl = document.getElementById(mapId + '-destination-lat');
            var destLngEl = document.getElementById(mapId + '-destination-lng');
            var readout = document.getElementById(mapId + '-readout');
            var btnOrigin = document.getElementById(mapId + '-btn-origin');
            var btnDest = document.getElementById(mapId + '-btn-destination');
            var placing = 'origin';
            var originMarker = null;
            var destMarker = null;

            function setPlacing(which) {
                placing = which;
                var on = ['px-3', 'py-1', 'text-xs', 'font-medium', 'rounded-full'];
                if (which === 'origin') {
                    btnOrigin.className = on.join(' ') + ' bg-green-600 text-white';
                    btnDest.className = on.join(' ') + ' bg-gray-200 text-gray-600';
                } else {
                    btnDest.className = on.join(' ') + ' bg-red-600 text-white';
                    btnOrigin.className = on.join(' ') + ' bg-gray-200 text-gray-600';
                }
            }

            function updateReadout() {
                var parts = [];
                if (originLatEl.value && originLngEl.value) parts.push('A: ' + originLatEl.value + ', ' + originLngEl.value);
                if (destLatEl.value && destLngEl.value) parts.push('B: ' + destLatEl.value + ', ' + destLngEl.value);
                readout.textContent = parts.join('  ·  ');
            }

            function place(which, latlng) {
                if (which === 'origin') {
                    if (!originMarker) {
                        originMarker = L.marker(latlng, { draggable: true, icon: dotIcon('#16a34a', 'A') }).addTo(map);
                        originMarker.on('dragend', function () {
                            var p = originMarker.getLatLng();
                            originLatEl.value = fmt(p.lat);
                            originLngEl.value = fmt(p.lng);
                            updateReadout();
                        });
                    } else {
                        originMarker.setLatLng(latlng);
                    }
                    originLatEl.value = fmt(latlng.lat);
                    originLngEl.value = fmt(latlng.lng);
                    setPlacing('destination');
                } else {
                    if (!destMarker) {
                        destMarker = L.marker(latlng, { draggable: true, icon: dotIcon('#dc2626', 'B') }).addTo(map);
                        destMarker.on('dragend', function () {
                            var p = destMarker.getLatLng();
                            destLatEl.value = fmt(p.lat);
                            destLngEl.value = fmt(p.lng);
                            updateReadout();
                        });
                    } else {
                        destMarker.setLatLng(latlng);
                    }
                    destLatEl.value = fmt(latlng.lat);
                    destLngEl.value = fmt(latlng.lng);
                }
                updateReadout();
            }

            btnOrigin.addEventListener('click', function () { setPlacing('origin'); });
            btnDest.addEventListener('click', function () { setPlacing('destination'); });
            map.on('click', function (e) { place(placing, e.latlng); });

            // Restore values after a validation failure
            if (originLatEl.value && originLngEl.value) {
                var o = L.latLng(parseFloat(originLatEl.value), parseFloat(originLngEl.value));
                place('origin', o);
                setPlacing('origin');
            }
            if (destLatEl.value && destLngEl.value) {
                var d = L.latLng(parseFloat(destLatEl.value), parseFloat(destLngEl.value));
                place('destination', d);
            }
            if ((originLatEl.value && originLngEl.value) || (destLatEl.value && destLngEl.value)) {
                var bounds = [];
                if (originMarker) bounds.push(originMarker.getLatLng());
                if (destMarker) bounds.push(destMarker.getLatLng());
                map.fitBounds(L.latLngBounds(bounds).pad(0.5));
            }
            updateReadout();
        } else {
            var latEl = document.getElementById(mapId + '-lat');
            var lngEl = document.getElementById(mapId + '-lng');
            var readoutSingle = document.getElementById(mapId + '-readout');
            var marker = null;

            function updateSingle() {
                readoutSingle.textContent = (latEl.value && lngEl.value)
                    ? 'Pin: ' + latEl.value + ', ' + lngEl.value
                    : '';
            }

            function placeSingle(latlng) {
                if (!marker) {
                    marker = L.marker(latlng, { draggable: true, icon: dotIcon('#7c2d12', '!') }).addTo(map);
                    marker.on('dragend', function () {
                        var p = marker.getLatLng();
                        latEl.value = fmt(p.lat);
                        lngEl.value = fmt(p.lng);
                        updateSingle();
                    });
                } else {
                    marker.setLatLng(latlng);
                }
                latEl.value = fmt(latlng.lat);
                lngEl.value = fmt(latlng.lng);
                updateSingle();
            }

            map.on('click', function (e) { placeSingle(e.latlng); });

            // Restore value after a validation failure
            if (latEl.value && lngEl.value) {
                var initial = L.latLng(parseFloat(latEl.value), parseFloat(lngEl.value));
                placeSingle(initial);
                map.setView(initial, 13);
            }
            updateSingle();
        }
    }

    if (window.L) {
        boot();
    } else {
        var s = document.createElement('script');
        s.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
        s.onload = boot;
        document.head.appendChild(s);
    }
})();
</script>
