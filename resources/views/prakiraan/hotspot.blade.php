@extends('layouts.main')
@section('title', 'Sebaran Hotspot')
@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="card-title mb-0">Sebaran Titik Panas (Hotspot)</h5>
        </div>
        <div class="card-body text-center">
            <img src="{{ route('citra.stream', ['type' => 'hotspot']) }}&t={{ time() }}"
                 alt="Hotspot Papua" class="img-fluid border shadow-sm" style="max-width: 600px; width: 100%; height: auto;">
            <div class="mt-3">
                <p class="text-muted small"><strong>Status:</strong> Terhubung (Live Update).</p>
            </div>

            <div class="mt-4 text-start p-3 bg-light border rounded">
                <h6 class="fw-bold text-danger"><i class="bi bi-fire me-2"></i>Keterangan Hotspot:</h6>
                <ul class="small mb-0">
                    <li><strong>Titik Merah:</strong> Mengindikasikan adanya anomali suhu panas yang terdeteksi sensor MODIS/VIIRS pada satelit.</li>
                    <li><strong>Tingkat Kepercayaan:</strong> Data ini merupakan indikasi awal kebakaran lahan. Verifikasi lapangan diperlukan untuk memastikan adanya api.</li>
                    <li><strong>Update:</strong> Data diperbarui berdasarkan *overpass* satelit pada waktu yang tertera di peta.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection