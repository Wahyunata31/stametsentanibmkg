@extends('layouts.main')
@section('title', 'Presipitasi 24 Jam')
@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0">Presipitasi GSMaP (24 Jam)</h5>
        </div>
        <div class="card-body text-center">
            <img src="{{ route('citra.stream', ['type' => 'presipitasi']) }}&t={{ time() }}"
                 alt="Presipitasi Papua" class="img-fluid border shadow-sm" style="max-width: 600px; width: 100%; height: auto;">
            <div class="mt-3">
                <p class="text-muted small"><strong>Status:</strong> Terhubung (Live Update).</p>
            </div>

            <div class="mt-4 text-start p-3 bg-light border rounded">
                <h6 class="fw-bold">Legenda Akumulasi Curah Hujan (24 Jam):</h6>
                <p class="small text-muted mb-2">Skala warna menunjukkan total curah hujan dalam milimeter (mm):</p>
                <ul class="small mb-0">
                    <li><strong>Hijau ke Biru:</strong> Wilayah dengan curah hujan rendah hingga menengah.</li>
                    <li><strong>Kuning ke Jingga:</strong> Wilayah dengan curah hujan tinggi (waspada kondisi tanah jenuh).</li>
                    <li><strong>Merah ke Ungu:</strong> Wilayah dengan curah hujan sangat ekstrem (potensi banjir tinggi).</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection