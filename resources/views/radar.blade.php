@extends('layouts.main')

@section('title', 'radar')

@section('content')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Pengamatan</a></li>
    <li class="breadcrumb-item" aria-current="page">Radar</li>
@endsection

@section('page_title', 'Radar')

@section('page_description')
    Pantauan radar cuaca wilayah Papua dan sekitarnya secara real-time.
@endsection

{{-- Bagian Peta --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div id="map" style="height: 600px; width: 100%; border-radius: 8px;"></div>
    </div>
</div>

{{-- CSS Tambahan --}}
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    #map { 
    z-index: 1; 
    height: 600px;
    width: 100%;
    background: #eeeeee
    }
</style>
@endpush

{{-- Script JS --}}
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Definisi Layer Peta
        const openStreet = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        });

        const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        });

        const darkMode = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 20
        });

        // 2. Inisialisasi Map (Koordinat Papua sebagai default)
        const map = L.map('map', {
            center: [-2.333, 140.633], // Koordinat Jayapura/Papua
            zoom: 6,
            layers: [openStreet] // Default layer
        });

        // 3. Kontrol Layer
        const baseMaps = {
            "OpenStreetMap": openStreet,
            "Satelit": satellite,
            "Mode Gelap": darkMode
        };

        //L.control.layers(baseMaps).addTo(map);

        // Tambahkan ini di bawah definisi baseMaps Anda

        // 3. Definisi Layer Radar BMKG (WMS)
        const radarBMKG = L.tileLayer.wms("https://web-services.bmkg.go.id/radar/wms", {
            layers: 'radar_cmax', // Layer standar untuk citra radar
            format: 'image/png',
            transparent: true,
            opacity: 0.7, // Agar transparan dan peta dasar tetap terlihat
            attribution: "Radar Data &copy; BMKG"
        });

        // 4. Update Kontrol Layer agar radar bisa dinyalakan/dimatikan
        const overlayMaps = {
            "Citra Radar BMKG": radarBMKG
        };

        // Tambahkan radarBMKG ke map secara default jika diinginkan
        radarBMKG.addTo(map);

        // Perbarui baris L.control.layers untuk menyertakan overlayMaps
        L.control.layers(baseMaps, overlayMaps).addTo(map);

        // Optional: Tambahkan marker atau overlay radar di sini
        let aircraftMarkers = {};

        // Gunakan icon pesawat agar lebih jelas
        const planeIcon = L.divIcon({
            html: '<i class="fas fa-plane" style="color: #ff5722; font-size: 18px; transform: rotate(0deg);"></i>',
            className: 'custom-plane-icon'
        });

        function updateAircraft() {
            // Mengambil data pesawat di wilayah Papua (Bounding Box)
            // Format: lamin, lomin, lamax, lomax
            const url = 'https://opensky-network.org/api/states/all?lamin=-12.0&lomin=129.0&lamax=1.0&lomax=151.0';
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error("Rate limit exceeded (OpenSky API)");
                    return response.json();
                })
                .then(data => {
                    if (!data.states) return;

                    data.states.forEach(flight => {
                        const icao24 = flight[0].trim();
                        const callsign = flight[1] ? flight[1].trim() : 'N/A';
                        const lat = flight[6];
                        const lon = flight[5];
                        const track = flight[10] || 0; // Arah pesawat
                        
                        if (lat && lon) {
                            if (aircraftMarkers[icao24]) {
                                // Update posisi jika pesawat sudah ada
                                aircraftMarkers[icao24].setLatLng([lat, lon]);
                            } else {
                            // Tambahkan marker baru
                                aircraftMarkers[icao24] = L.marker([lat, lon], {
                                    icon: L.divIcon({
                                        html: `<div style="transform: rotate(${track}deg); font-size: 20px; color: yellow; text-shadow: 1px 1px #000;">✈</div>`,
                                        className: 'plane-div-icon'
                                    })
                                }).addTo(map)
                                .bindPopup(`<b>Flight: ${callsign}</b><br>Alt: ${Math.round(flight[7])}m`);
                            }
                        }
                    });
                })
                .catch(err => console.error('Gagal memuat data pesawat:', err));
        }
        // Update setiap 1 detik
        setInterval(updateAircraft, 10000);
        updateAircraft();
    });
</script>
@endpush

@endsection