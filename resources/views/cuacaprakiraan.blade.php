@extends('layouts.main')

@section('title', 'Prakiraan Cuaca - Kabupaten Jayapura')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Prakiraan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Prakiraan Cuaca</li>
@endsection

@section('page_title', 'Prakiraan Cuaca')

@section('page_description')
    Prakiraan cuaca wilayah Kabupaten, Kota Jayapura, dan Sekitarnya.
@endsection


@section('title', 'Prakiraan Cuaca - Papua')

@section('content')
<div class="container-fluid py-4">
    {{-- Selector Lokasi --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <form action="{{ url('/cuaca-prakiraan') }}" method="GET">
                        <label class="form-label fw-bold text-muted small uppercase">PILIH LOKASI WILAYAH</label>
                        <select name="kode_wilayah" class="form-select form-select-lg border-0 bg-light" onchange="this.form.submit()" style="border-radius: 10px;">
                            @foreach($wilayahList as $kode => $nama)
                                <option value="{{ $kode }}" {{ $selectedAdm == $kode ? 'selected' : '' }}>{{ $nama }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($data['data'][0]['cuaca']))
        @php $lokasi = $data['lokasi']; @endphp
        
        <h4 class="fw-bold mb-3">Prakiraan Cuaca di {{ $lokasi['desa'] }}, {{ $lokasi['kecamatan'] }}</h4>

        <div class="row">
            @foreach($data['data'][0]['cuaca'] as $indexHari => $hari)
                {{-- Judul per Hari --}}
                <div class="col-12 mt-4 mb-2">
                    <h5 class="text-primary fw-bold">Hari ke-{{ $indexHari + 1 }}</h5>
                </div>

                @foreach($hari as $jam)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; overflow: hidden;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-soft-primary text-primary px-3 py-2" style="background-color: #e7f1ff; border-radius: 10px;">
                                    <i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($jam['local_datetime'])->format('H:i') }} WIT
                                </span>
                                <span class="text-muted small">{{ \Carbon\Carbon::parse($jam['local_datetime'])->format('d M Y') }}</span>
                            </div>

                            <div class="d-flex align-items-center">
                                {{-- Ikon Cuaca --}}
                                <div class="shrink-0 me-3">
                                    <img src="{{ str_replace(' ', '%20', $jam['image']) }}" alt="icon" style="width: 70px; height: 70px; object-fit: contain;">
                                </div>
                                
                                {{-- Suhu & Deskripsi --}}
                                <div>
                                    <h2 class="mb-0 fw-bold">{{ $jam['t'] }}°C</h2>
                                    <p class="text-muted mb-0 fw-semibold">{{ $jam['weather_desc'] }}</p>
                                </div>
                            </div>

                            <hr class="my-3 opacity-25">

                            {{-- Detail Parameter --}}
                            <div class="row text-center">
                                <div class="col-4">
                                    <p class="text-muted small mb-1">Lembap</p>
                                    <p class="fw-bold mb-0 small">{{ $jam['hu'] }}%</p>
                                </div>
                                <div class="col-4 border-start border-end">
                                    <p class="text-muted small mb-1">Angin</p>
                                    <p class="fw-bold mb-0 small">{{ $jam['ws'] }} <span style="font-size: 8px;">km/j</span></p>
                                </div>
                                <div class="col-4">
                                    <p class="text-muted small mb-1">Arah</p>
                                    <p class="fw-bold mb-0 small">{{ $jam['wd'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endforeach
        </div>

        <div class="mt-5 p-3 bg-light rounded-3 text-center">
            <p class="small text-muted mb-0">Sumber Data: <strong>BMKG (Badan Meteorologi, Klimatologi, dan Geofisika)</strong></p>
            <p class="x-small text-muted" style="font-size: 10px;">Data diperbarui otomatis dari server pusat BMKG</p>
        </div>
    @endif
</div>

<style>
    .bg-soft-primary { background-color: #f0f7ff; color: #007bff; }
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-5px); }
</style>
@endsection