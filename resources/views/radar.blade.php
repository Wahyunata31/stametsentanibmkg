@extends('layouts.main')

@section('title', 'radar')

@section('content')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Pengamatan</a></li>
    <li class="breadcrumb-item" aria-current="page">Radar</li>
@endsection

@section('page_title', 'Radar')

@section('page_description')
    Pantauan radar cuaca lokal dan trafik penerbangan wilayah Jayapura secara real-time.
@endsection

{{-- Bagian Peta dengan Spanduk Atas dan Logo BMKG --}}
<div class="card shadow-sm">
    <div class="radar-top-banner">
        <img src="{{ asset('favicon.png') }}" alt="Logo BMKG" class="banner-logo">
        <div class="banner-left-content">
            <h4>Stasiun Meteorologi Kelas I Sentani - Jayapura</h4>
            <p>Cepat, Tepat, Akurat, Luas, & Mudah dipahami</p>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div id="map" style="height: 600px; width: 100%; border-radius: 0 0 8px 8px;"></div>
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
        background: #eeeeee;
    }
    .plane-icon-wrapper {
        transition: transform 0.5s ease-in-out;
    }

    .radar-top-banner {
        background: linear-gradient(to bottom, #0044cc, #ffffff);
        padding: 10px 20px;
        border-bottom: 4px solid #00aa00;
        font-family: Arial, sans-serif;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .banner-logo {
        height: 50px;
        width: auto;
        margin-right: 15px;
    }
    .banner-left-content h4 {
        margin: 0;
        font-size: 20px;
        font-weight: bold;
        color: #ffffff;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
        letter-spacing: 0.5px;
    }
    .banner-left-content p {
        margin: 2px 0 0 0;
        font-size: 12px;
        color: #111;
        font-weight: bold;
    }
    
    .radar-info-box {
        background: rgba(255, 255, 255, 0.95);
        padding: 10px 14px;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        border-radius: 4px;
        font-family: Arial, sans-serif;
        font-size: 12px;
        line-height: 1.4;
        border-left: 5px solid #0044cc;
    }
    .status-update {
        color: green;
        font-weight: bold;
    }

    .leaflet-bottom.leaflet-left {
        width: 100%;
        display: flex;
        justify-content: center;
        pointer-events: none;
        padding: 0 10px;
        box-sizing: border-box;
        margin-bottom: 25px;
    }

    .radar-legend-horizontal {
        background: rgba(255, 255, 255, 0.4);
        padding: 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        border-radius: 6px;
        font-family: Arial, sans-serif;
        width: 100%;            
        max-width: 100%;        
        pointer-events: auto;
        backdrop-filter: blur(2px);
    }
    
    .legend-h-title {
        text-align: center;
        font-weight: bold;
        padding: 5px 0;
        font-size: 13px;
        background: rgba(248, 249, 250, 0.6);
        border-bottom: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 6px 6px 0 0;
        color: #222;
    }

    .legend-h-container {
        display: flex;
        width: 100%;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .legend-h-item {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        border-right: 1px solid rgba(0, 0, 0, 0.15);
    }
    
    .legend-h-item:last-child {
        border-right: none;
    }

    .legend-h-color {
        width: 100%;
        height: 22px; 
        text-align: center;
        font-size: 11px;
        font-weight: bold;
        line-height: 22px;
        color: #fff;
    }

    .legend-h-label {
        font-size: 11px;
        padding: 4px 2px;
        color: #111;
        text-align: center;
        font-weight: bold;
        width: 100%;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.5); 
        border-radius: 0 0 6px 6px;
    }
</style>
@endpush

{{-- Script JS --}}
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. DEFINISI LAYER PETA DASAR (BASEMAPS)
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

        // 2. INISIALISASI MAP (Fokus default di wilayah Sentani, Jayapura)
        const map = L.map('map', {
            center: [-2.573000, 140.485000], 
            zoom: 7, 
            layers: [openStreet]
        });

        const baseMaps = {
            "OpenStreetMap": openStreet,
            "Satelit": satellite,
            "Mode Gelap": darkMode
        };

        // Bounding Box Proyeksi Geografis Gambar Radar Bawaan
        const batasRadarJayapura = [
            [-4.823000, 138.235000], 
            [-0.323000, 142.735000]
        ];

        const batasRadarBiak = [
            [-3.323200, 133.929200], 
            [0.976800, 138.229200]
        ];

        const batasRadarSorong = [
            [-3.037100, 129.141500], 
            [1.262900, 133.441500]
        ];

        const batasRadarWamena = [
            [-6.251100, 136.793800], 
            [-1.951100, 141.093800]
        ];

        const batasRadarTimika = [
            [-6.678400, 134.736900], 
            [-2.378400, 139.036900]
        ];

        const batasRadarMerauke = [
            [-10.650000, 137.950000], 
            [-6.350000, 142.450000]
        ];

        // --- INSTANSI LAYER OVERLAY RADAR ---
        const radarLokalEEC = L.imageOverlay("{{ route('radar.latest') }}?t=" + new Date().getTime(), batasRadarJayapura, {
            opacity: 0.75, 
            alt: "Radar Lokal EEC EDRP9 Jayapura",
            attribution: "Radar Lokal &copy; EEC EDRP9 Jayapura"
        });

        const radarBmkgJayapura = L.imageOverlay("{{ route('radar.sidarma') }}?t=" + new Date().getTime(), batasRadarJayapura, {
            opacity: 0.75, 
            alt: "Radar BMKG Jayapura CMAX",
            attribution: "Data &copy; BMKG"
        });

        const radarBmkgBiak = L.imageOverlay("{{ route('radar.biak') }}?t=" + new Date().getTime(), batasRadarBiak, {
            opacity: 0.75, 
            alt: "Radar BMKG Biak CMAX",
            attribution: "Data &copy; BMKG"
        });

        const radarBmkgSorong = L.imageOverlay("{{ route('radar.sorong') }}?t=" + new Date().getTime(), batasRadarSorong, {
            opacity: 0.75, 
            alt: "Radar BMKG Sorong CMAX",
            attribution: "Data &copy; BMKG"
        });

        const radarBmkgWamena = L.imageOverlay("{{ route('radar.wamena') }}?t=" + new Date().getTime(), batasRadarWamena, {
            opacity: 0.75, 
            alt: "Radar BMKG Wamena CMAX",
            attribution: "Data &copy; BMKG"
        });

        const radarBmkgTimika = L.imageOverlay("{{ route('radar.timika') }}?t=" + new Date().getTime(), batasRadarTimika, {
            opacity: 0.75, 
            alt: "Radar BMKG Timika CMAX",
            attribution: "Data &copy; BMKG"
        });

        const radarBmkgMerauke = L.imageOverlay("{{ route('radar.merauke') }}?t=" + new Date().getTime(), batasRadarMerauke, {
            opacity: 0.75, 
            alt: "Radar BMKG Merauke CMAX",
            attribution: "Data &copy; BMKG"
        });
        
        // ========================================================
        // KUNCI KOORDINAT PERMANEN HASIL KALIBRASI AKURAT ANDA     
        // ========================================================
        const batasSatelitRegion5 = [
            [-15.394000, 129.963000], // Sudut Kiri Bawah (South-West)
            [5.348000, 150.005000]    // Sudut Kanan Atas (North-East)
        ];

        const satelitRegion5 = L.imageOverlay("{{ route('radar.satellite_animation') }}?t=" + new Date().getTime(), batasSatelitRegion5, {
            opacity: 0.50, 
            alt: "Citra Satelit Animasi H08 EH Region 5 BMKG",
            attribution: "Satelit &copy; BMKG Pusat"
        });

        // Aktifkan default layer saat halaman pertama kali dibuka
        radarLokalEEC.addTo(map);
        radarBmkgJayapura.addTo(map);
        satelitRegion5.addTo(map);

        // Menu pilihan centang kontrol layer di kanan atas
        const overlayMaps = {
            "Radar Lokal EEC Edge": radarLokalEEC,
            "Radar BMKG Jayapura (CMAX)": radarBmkgJayapura,
            "Radar BMKG Biak (CMAX)": radarBmkgBiak,
            "Radar BMKG Sorong (CMAX)": radarBmkgSorong,
            "Radar BMKG Wamena (CMAX)": radarBmkgWamena,
            "Radar BMKG Timika (CMAX)": radarBmkgTimika,
            "Radar BMKG Merauke (CMAX)": radarBmkgMerauke,
            "Citra Satelit Animasi H08 (Region V)": satelitRegion5
        };

        L.control.layers(baseMaps, overlayMaps).addTo(map);


        // A. PANEL JUDUL & TIMESTAMP UTAMA (Pojok Kiri Atas)
        const infoControl = L.control({ position: 'topleft' });
        infoControl.onAdd = function (map) {
            const div = L.DomUtil.create('div', 'radar-info-box');
            div.innerHTML = `
                <b>Radar C-band</b><br>
                Status: <span class="status-update">Update</span><br>
                Data Terakhir: <span id="radar-time-now">-</span> WIB<br>
                Timestamp: <span id="radar-time-ts">-</span> WIB
            `;
            return div;
        };
        infoControl.addTo(map);

        function updatePanelTimestamp() {
            const sekarang = new Date();
            const formatWaktu = sekarang.toLocaleDateString('id-ID') + ' (' + 
                                String(sekarang.getHours()).padStart(2, '0') + ':' + 
                                String(sekarang.getMinutes()).padStart(2, '0') + ')';
            
            const waktuTS = new Date(sekarang.getTime() - 10 * 60000);
            const formatTS = waktuTS.toLocaleDateString('id-ID') + ' (' + 
                             String(waktuTS.getHours()).padStart(2, '0') + ':' + 
                             String(waktuTS.getMinutes()).padStart(2, '0') + ')';

            if(document.getElementById('radar-time-now')) document.getElementById('radar-time-now').innerText = formatWaktu;
            if(document.getElementById('radar-time-ts')) document.getElementById('radar-time-ts').innerText = formatTS;
        }
        updatePanelTimestamp();


        // B. PANEL LEGENDA DATA RADAR DBZ (Pojok Kiri Bawah)
        const horizontalLegend = L.control({ position: 'bottomleft' }); 
        horizontalLegend.onAdd = function (map) {
            const div = L.DomUtil.create('div', 'radar-legend-horizontal');
            
            const legendData = [
                { color: '#00ffff', text: '#000', val: '-10.0', label: 'Berawan' },
                { color: '#00ff00', val: '10.0', label: '' },
                { color: '#00cc00', val: '13.0', label: '' },
                { color: '#009900', val: '18.0', label: '' },
                { color: '#006600', val: '23.0', label: 'Hujan Sangat Ringan' },
                { color: '#ffff00', text: '#000', val: '28.0', label: '' },
                { color: '#ffcc00', text: '#000', val: '33.0', label: 'Hujan Ringan' },
                { color: '#ff9900', val: '38.0', label: '' },
                { color: '#ff6600', val: '43.0', label: 'Hujan Sedang' },
                { color: '#ff0000', val: '48.0', label: '' },
                { color: '#cc0000', val: '53.0', label: 'Hujan Lebat' },
                { color: '#ff00ff', val: '58.0', label: '' },
                { color: '#cc00cc', val: '63.0', label: '' },
                { color: '#990099', val: '68.0', label: '' },
                { color: '#660066', val: '73.0', label: '' },
                { color: '#ffffff', text: '#000', border: '1px solid #ccc', val: '78.0', label: 'Hujan Sangat Lebat' }
            ];

            let htmlContent = `<div class="legend-h-title">Radar Legend Panel (dBZ)</div>`;
            htmlContent += `<div class="legend-h-container">`;

            legendData.forEach(item => {
                const textColor = item.text || '#fff';
                const border = item.border || 'none';
                
                htmlContent += `
                    <div class="legend-h-item">
                        <div class="legend-h-color" style="background: ${item.color}; color: ${textColor}; border: ${border};">
                            ${item.val}
                        </div>
                        ${item.label ? `<div class="legend-h-label">${item.label}</div>` : '<div class="legend-h-label" style="background: rgba(245, 245, 245, 0.9);"></div>'}
                    </div>
                `;
            });

            htmlContent += `</div>`;
            div.innerHTML = htmlContent;
            
            L.DomEvent.disableClickPropagation(div);
            return div;
        };
        horizontalLegend.addTo(map);


        // C. MONITORING ADS-B FLIGHT TRAFFIC RECEIVER ENGINE
        let aircraftMarkers = {};

        function updateAircraft() {
            const url = "{{ route('radar.aircraft') }}";

            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error("Gagal mengambil data dari proxy ADSB");
                    return response.json();
                })
                .then(data => {
                    if (!data || typeof data !== 'object') return;

                    let activeFlights = new Set();

                    Object.entries(data).forEach(([hexKey, flightArray]) => {
                        if (Array.isArray(flightArray) && flightArray.length >= 6) {
                            const hex = hexKey.trim();
                            let rawLat = flightArray[1];
                            let rawLon = flightArray[2];
                            
                            const lat = (rawLat !== "" && rawLat !== null) ? parseFloat(rawLat) : 0;
                            const lon = (rawLon !== "" && rawLon !== null) ? parseFloat(rawLon) : 0;
                            
                            const track = parseInt(flightArray[3]) || 0;     
                            const altitude = parseInt(flightArray[4]) || 0;  
                            const speed = parseInt(flightArray[5]) || 0;     
                            const callsign = (flightArray[flightArray.length - 1] || 'N/A').toString().trim();

                            if (lat !== 0 && lon !== 0 && !isNaN(lat) && !isNaN(lon)) {
                                activeFlights.add(hex);

                                const popupContent = `
                                    <div style="font-family: Arial, sans-serif; font-size: 12px; width: 160px;">
                                        <b style="color: #0275d8; font-size: 13px;">Flight: ${callsign}</b><br>
                                        <hr style="margin: 4px 0; border: 0; border-top: 1px solid #ddd;">
                                        <b>ICAO Hex:</b> ${hex.toUpperCase()}<br>
                                        <b>Altitude:</b> ${altitude.toLocaleString()} ft<br>
                                        <b>Speed:</b> ${speed} kts<br>
                                        <b>Heading:</b> ${track}°
                                    </div>
                                `;

                                if (aircraftMarkers[hex]) {
                                    aircraftMarkers[hex].setLatLng([lat, lon]);
                                    aircraftMarkers[hex].setPopupContent(popupContent);
                                    
                                    const element = aircraftMarkers[hex].getElement();
                                    if (element) {
                                        const iconDiv = element.querySelector('.plane-icon-wrapper');
                                        if (iconDiv) {
                                            iconDiv.style.transform = `rotate(${track}deg)`;
                                        }
                                    }
                                } else {
                                    aircraftMarkers[hex] = L.marker([lat, lon], {
                                        icon: L.divIcon({
                                            html: `<div class="plane-icon-wrapper" style="transform: rotate(${track}deg); font-size: 22px; color: #ffcc00; text-shadow: 1px 1px 2px #000; width: 30px; height: 30px; text-align: center; line-height: 30px;">✈</div>`,
                                            className: 'custom-plane-marker',
                                            iconSize: [30, 30],
                                            iconAnchor: [15, 15]
                                        })
                                    }).addTo(map).bindPopup(popupContent);
                                }
                            }
                        }
                    });

                    for (let hex in aircraftMarkers) {
                        if (!activeFlights.has(hex)) {
                            map.removeLayer(aircraftMarkers[hex]);
                            delete aircraftMarkers[hex];
                        }
                    }
                })
                .catch(err => console.error('Pemberitahuan Sistem ADSB:', err));
        }

        setInterval(updateAircraft, 3000);
        updateAircraft();


        // D. AUTO-REFRESH URL SINKRONISASI CITRA OVERLAY (TIAP 5 MENIT)
        setInterval(function() {
            const timestamp = new Date().getTime();
            
            radarLokalEEC.setUrl("{{ route('radar.latest') }}?t=" + timestamp);
            radarBmkgJayapura.setUrl("{{ route('radar.sidarma') }}?t=" + timestamp);
            radarBmkgBiak.setUrl("{{ route('radar.biak') }}?t=" + timestamp);
            radarBmkgSorong.setUrl("{{ route('radar.sorong') }}?t=" + timestamp);
            satelitRegion5.setUrl("{{ route('radar.satellite_animation') }}?t=" + timestamp);

            updatePanelTimestamp();
        }, 300000);
    });
</script>
@endpush

@endsection
