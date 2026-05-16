@extends('layouts.main')

@section('title', 'Peringatan Dini')

@section('page_title', 'Peringatan Dini')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Prakiraan</a></li>
    <li class="breadcrumb-item" aria-current="page">Peringatan Dini</li>
@endsection

@section('page_description')
    Peringatan dini hujan sedang hingga lebat yang dapat disertai petir dan angin kencang di wilayah Papua.
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 500px; width: 100%; border-radius: 8px; border: 1px solid #ddd; }
    .alert-item { cursor: pointer; transition: 0.2s; border-left: 4px solid transparent; }
    .alert-item:hover { background-color: #f0f7ff; border-left: 4px solid #007bff; }
    .scroll-box { max-height: 500px; overflow-y: auto; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white fw-bold">
                Daftar Peringatan Aktif (BMKG)
            </div>
            <div class="card-body p-0 scroll-box">
                <div class="list-group list-group-flush">
                    @forelse($alerts as $alert)
                        <div class="list-group-item alert-item" onclick="loadMap('{{ $alert['link'] }}')">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 fw-bold text-primary">{{ $alert['title'] }}</h6>
                            </div>
                            <small class="text-muted d-block mb-1"><i class="bi bi-clock"></i> {{ $alert['pubDate'] }}</small>
                            <p class="mb-1 small text-truncate">{{ \Illuminate\Support\Str::limit($alert['description'], 100) }}</p>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">
                            <i class="bi bi-info-circle d-block mb-2"></i>
                            Tidak ada peringatan dini aktif saat ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div id="map" class="shadow-sm"></div>
        <div id="info-detail" class="mt-3 card card-body border-warning bg-light d-none shadow-sm">
            <h5 id="detail-headline" class="text-danger fw-bold"></h5>
            <p id="detail-area" class="mb-0"></p>
            <hr>
            <small class="text-muted text-end">Sumber Data: <strong>BMKG</strong></small>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Koordinat awal (Tengah Indonesia atau Papua sesuai keinginan)
    let map = L.map('map').setView([-2.5489, 140.7181], 6); 
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors | Sumber: BMKG'
    }).addTo(map);

    let polygonGroup = L.featureGroup().addTo(map);

    // ... bagian script lainnya tetap sama ...

    function loadMap(capUrl) {
        // Tampilkan indikator loading sederhana
        const infoBox = document.getElementById('info-detail');
        infoBox.classList.add('d-none');

        fetch(`/peringatan-dini/detail?url=${encodeURIComponent(capUrl)}`)
            .then(res => res.json())
            .then(data => {
                polygonGroup.clearLayers();
            
                if (data.polygons && data.polygons.length > 0) {
                    data.polygons.forEach(coords => {
                        L.polygon(coords, {
                            color: '#d9534f', // Merah peringatan
                            fillColor: '#f0ad4e', // Orange sesuai standar 
                            fillOpacity: 0.4,
                            weight: 2
                        }).addTo(polygonGroup);
                    });
                
                    // Zoom otomatis ke area poligon
                    map.fitBounds(polygonGroup.getBounds(), { padding: [20, 20] });
                
                    // Update Detail Box
                    infoBox.classList.remove('d-none');
                    document.getElementById('detail-headline').innerText = data.headline;
                    document.getElementById('detail-area').innerHTML = `
                        <strong>Wilayah:</strong> ${data.area}<br>
                        <p class="mt-2 small text-dark">${data.description}</p>
                    `;
                } else {
                    alert("Detail lokasi spasial (poligon) tidak ditemukan untuk peringatan ini.");
                }
            })
            .catch(err => {
                console.error("Error loading CAP:", err);
                alert("Gagal mengambil detail data dari server BMKG.");
            });
    }
</script>
@endpush