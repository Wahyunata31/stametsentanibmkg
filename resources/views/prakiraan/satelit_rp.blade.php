@extends('layouts.main')

@section('title', 'Citra Satelit RP')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Prakiraan</a></li>
    <li class="breadcrumb-item" aria-current="page">Citra Satelit RP</li>
@endsection

@section('page_title', 'Citra Satelit RP')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Citra Satelit Himawari-8 (RP - Papua)</h5>
        </div>
        <div class="card-body text-center">
            <img src="{{ route('citra.stream', ['type' => 'satelit_rp']) }}&t={{ time() }}"
                 class="img-fluid border shadow-sm" style="max-width: 600px; width: 100%; height: auto;">
            <div class="mt-3">
                <p class="text-muted small"><strong>Status:</strong> Terhubung (Live Update).</p>
            </div>

            <div class="mt-4 text-start p-3 bg-light border rounded">
                <h6 class="fw-bold"><i class="bi bi-cloud-rain me-2"></i>Potensi Curah Hujan:</h6>
                <div class="d-flex flex-wrap gap-3 small">
                    <span><i class="bi bi-square-fill text-info"></i> Biru: Ringan</span>
                    <span><i class="bi bi-square-fill text-success"></i> Hijau: Sedang</span>
                    <span><i class="bi bi-square-fill text-warning"></i> Kuning: Lebat</span>
                    <span><i class="bi bi-square-fill text-danger"></i> Merah: Sangat Lebat</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection