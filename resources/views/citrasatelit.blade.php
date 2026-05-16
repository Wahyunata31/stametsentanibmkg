@extends('layouts.main')

@section('title', 'Citra Satelit Wilayah Papua')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Prakiraan</a></li>
    <li class="breadcrumb-item" aria-current="page">Citra Satelit Wilayah Papua</li>
@endsection

@section('page_title', 'Citra Satelit')

@section('page_description')
    Citra Satelit Wilayah Papua.
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Citra Satelit Himawari-8 (Papua)</h5>
        </div>
        <div class="card-body text-center">
            
            {{-- Memanggil route stream dengan timestamp agar selalu update --}}
            <img src="{{ route('citra.stream', ['type' => 'satelit_eh']) }}&t={{ time() }}"
                alt="Citra Satelit Papua EH" 
                class="img-fluid border shadow-sm" 
                style="max-width: 70%; height: auto;"
                onerror="this.onerror=null; this.src='https://via.placeholder.com/800x600?text=Server+Pusat+Offline';">

            <div class="mt-3">
                <p class="text-muted small">
                    <strong>Status:</strong> Terhubung.
                </p>
            </div>

            <div class="mt-4 text-start p-3 bg-light border rounded">
                <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Keterangan Warna (Suhu Puncak Awan):</h6>
                <ul class="small mb-0">
                    <li><strong>Putih/Abu-abu Terang:</strong> Suhu sangat dingin (< -60°C), mengindikasikan awan Cumulonimbus yang sangat tebal dan potensi hujan sangat lebat.</li>
                    <li><strong>Merah/Jingga:</strong> Suhu dingin (-50°C s/d -60°C), awan tebal dengan potensi hujan sedang hingga lebat.</li>
                    <li><strong>Kuning/Hijau:</strong> Suhu cukup dingin (-30°C s/d -50°C), terdapat awan dengan potensi hujan ringan.</li>
                    <li><strong>Biru/Hitam:</strong> Area cerah atau awan rendah yang tidak berpotensi hujan signifikan.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection