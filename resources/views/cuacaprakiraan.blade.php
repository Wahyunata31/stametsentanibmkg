@extends('layouts.main')

@section('title', 'Prakiraan Cuaca - ' . ($namaWilayahSelected ?? 'Papua'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Prakiraan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Prakiraan Cuaca</li>
@endsection

@section('page_title', 'Prakiraan Cuaca')

@section('page_description')
    Prakiraan cuaca wilayah Kabupaten Jayapura, Kota Jayapura, dan Sekitarnya.
@endsection

@section('content')
<div class="container-fluid py-4">
    {{-- Selector Lokasi --}}
    <div class="row mb-4 g-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <form action="{{ url('/cuaca-prakiraan') }}" method="GET">
                        <label class="form-label fw-bold text-muted small text-uppercase mb-2">
                            <i class="bi bi-geo-alt me-1 text-primary"></i> Pilih Lokasi Wilayah
                        </label>
                        <select name="kode_wilayah" class="form-select form-select-lg border-0 bg-light" onchange="this.form.submit()" style="border-radius: 10px;">
                            @foreach($wilayahList as $kode => $nama)
                                <option value="{{ $kode }}" {{ ($kodeWilayah ?? '') == $kode ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-primary text-white h-100" style="border-radius: 15px;">
                <div class="card-body d-flex align-items-center justify-content-between p-3">
                    <div>
                        <small class="text-white-50 text-uppercase fw-bold">Lokasi Terpilih</small>
                        <h5 class="fw-bold mb-0 text-truncate" style="max-width: 320px;">
                            {{ $namaWilayahSelected ?? 'Kabupaten Jayapura' }}
                        </h5>
                    </div>
                    <i class="bi bi-pin-map-fill fs-1 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Pengelompokan Data Cuaca Berdasarkan Tanggal secara Aman --}}
    @php
        $groupedCuaca = [];
        if (!empty($cuaca) && is_array($cuaca)) {
            foreach ($cuaca as $item) {
                if (is_array($item) || is_object($item)) {
                    $itemArr = (array) $item;
                    $dt = $itemArr['local_datetime'] ?? $itemArr['datetime'] ?? null;
                    $dateKey = $dt ? substr($dt, 0, 10) : 'Lainnya';
                    $groupedCuaca[$dateKey][] = $itemArr;
                }
            }
        }
    @endphp

    {{-- Tampilan Cuaca --}}
    @if(empty($groupedCuaca))
        <div class="alert alert-warning text-center rounded-4 shadow-sm py-5 my-4">
            <i class="bi bi-exclamation-triangle-fill fs-1 d-block mb-3 text-warning"></i>
            <h5 class="fw-bold mb-1">Data Prakiraan Cuaca Belum Tersedia</h5>
            <p class="mb-0 text-muted small">Silakan pilih lokasi wilayah lain atau coba muat ulang beberapa saat lagi.</p>
        </div>
    @else
        @foreach($groupedCuaca as $dateKey => $jamList)
            @php
                $headerDate = ($dateKey !== 'Lainnya')
                    ? \Carbon\Carbon::parse($dateKey)->translatedFormat('l, d F Y')
                    : 'Prakiraan Cuaca';
            @endphp

            <div class="mb-4">
                {{-- Header Tanggal --}}
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary rounded-circle p-2 text-white me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-calendar-event small"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-0">{{ $headerDate }}</h5>
                </div>

                {{-- Grid Kartu Cuaca Per Jam --}}
                <div class="row g-3">
                    @foreach($jamList as $jamData)
                        @php
                            $datetime = $jamData['local_datetime'] ?? $jamData['datetime'] ?? null;
                            
                            $timeFormatted = '-';
                            if ($datetime) {
                                try {
                                    $timeFormatted = \Carbon\Carbon::parse($datetime)->format('H:i') . ' WIT';
                                } catch (\Exception $e) {
                                    $timeFormatted = $datetime;
                                }
                            }

                            $cuacaText = $jamData['weather_desc'] ?? $jamData['weather_desc_en'] ?? $jamData['weather'] ?? 'N/A';
                            $iconUrl = $jamData['image'] ?? null;
                            $temp = $jamData['t'] ?? $jamData['temp'] ?? '-';
                            $kelembapan = $jamData['hu'] ?? $jamData['humidity'] ?? '-';
                            $kecepatanAngin = $jamData['ws'] ?? $jamData['wind_speed'] ?? '-';
                            $arahAngin = $jamData['wd'] ?? $jamData['wind_direction'] ?? '-';
                        @endphp

                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card weather-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="card-body text-center p-3">
                                    <span class="badge bg-soft-primary text-primary fw-bold mb-2 p-2 w-100" style="font-size: 11px;">
                                        <i class="bi bi-clock me-1"></i>{{ $timeFormatted }}
                                    </span>
                                    
                                    <div class="my-2 d-flex align-items-center justify-content-center" style="height: 55px;">
                                        @if($iconUrl)
                                            <img src="{{ $iconUrl }}" alt="{{ $cuacaText }}" class="img-fluid h-100 object-fit-contain">
                                        @else
                                            <i class="bi bi-cloud-sun fs-1 text-primary"></i>
                                        @endif
                                    </div>

                                    <h6 class="fw-bold mb-1 text-dark text-truncate" title="{{ $cuacaText }}">{{ $cuacaText }}</h6>
                                    <h3 class="fw-black text-primary mb-3">{{ $temp }}°C</h3>

                                    <div class="row g-0 pt-2 border-top text-center" style="font-size: 11px;">
                                        <div class="col-4">
                                            <p class="text-muted mb-0 x-small">Lembap</p>
                                            <p class="fw-bold mb-0 text-dark">{{ $kelembapan }}%</p>
                                        </div>
                                        <div class="col-4 border-start border-end">
                                            <p class="text-muted mb-0 x-small">Angin</p>
                                            <p class="fw-bold mb-0 text-dark">{{ $kecepatanAngin }} <span style="font-size: 9px;">km/h</span></p>
                                        </div>
                                        <div class="col-4">
                                            <p class="text-muted mb-0 x-small">Arah</p>
                                            <p class="fw-bold mb-0 text-dark">{{ $arahAngin }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        {{-- Footer Sumber --}}
        <div class="mt-4 p-3 bg-light rounded-3 text-center">
            <p class="small text-muted mb-0">Sumber Data: <strong>BMKG (Badan Meteorologi, Klimatologi, dan Geofisika)</strong></p>
            <p class="text-muted mb-0" style="font-size: 11px;">Data diperbarui otomatis dari server pusat BMKG</p>
        </div>
    @endif
</div>

<style>
    .bg-soft-primary { background-color: #eef6ff; color: #0d6efd; }
    .weather-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .weather-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
    .fw-black { font-weight: 900; }
    .x-small { font-size: 10px; }
</style>
@endsection