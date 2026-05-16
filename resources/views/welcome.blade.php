@extends('layouts.main')

@push('styles')
<style>
    /* Membuat area scroll lebih halus */
    .overflow-auto {
        display: flex;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }

    /* Mempercantik tampilan Scrollbar agar user tahu bisa di-geser */
    .overflow-auto::-webkit-scrollbar {
        height: 8px; /* Tinggi scrollbar horizontal */
    }

    .overflow-auto::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }

    .overflow-auto::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3); /* Warna scrollbar */
        border-radius: 10px;
    }

    .overflow-auto::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
</style>
@endpush

@section('title', 'Halaman Beranda')

@section('content')
<div class="container py-4">
    {{-- 1. BAGIAN PRAKIRAAN CUACA --}}
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="fw-bold mb-3" style="color: #2c3e50;">Prakiraan Cuaca Sentani</h5>
            <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #1e3c72, #2a5298); border-radius: 15px;">
                <div class="card-body p-4">
                    @if($cuaca)
                        <div class="row align-items-center">
                            {{-- Sisi Kiri: Cuaca Saat Ini --}}
                            <div class="col-md-5 border-end border-white border-opacity-25">
                                <h2 class="fw-bold mb-1">Sentani</h2>
                                <p class="mb-3 opacity-75 small">Jayapura, Papua</p>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $cuaca['current']['image'] }}" width="100" alt="Ikon Cuaca">
                                    <div class="ms-3">
                                        <span class="display-3 fw-bold">{{ $cuaca['current']['t'] }}°</span>
                                        <p class="fs-5 mb-0">{{ $cuaca['current']['weather_desc'] }}</p>
                                    </div>
                                </div>
                                <div class="mt-3 small opacity-75">
                                    <span><i class="bi bi-droplets"></i> {{ $cuaca['current']['hu'] }}%</span>
                                    <span class="ms-3"><i class="bi bi-wind"></i> {{ $cuaca['current']['ws'] }} km/jam</span>
                                </div>
                            </div>

                            {{-- Sisi Kanan: List Jam Berikutnya --}}
                            <div class="col-md-7 ps-md-4">
                                <div class="d-flex text-center mt-3 mt-md-0 overflow-auto pb-2" style="gap: 25px; scrollbar-width: thin;">
                                    @foreach($cuaca['next'] as $h)
                                    <div style="min-width: 70px;">
                                        {{-- Menampilkan jam sesuai format yang benar --}}
                                        <p class="small mb-1 opacity-75">{{ \Carbon\Carbon::parse($h['local_datetime'])->format('H:i') }}</p>
                                        <img src="{{ $h['image'] }}" width="40" class="mb-1">
                                        <p class="small fw-bold mb-0">{{ $h['t'] }}°</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <p class="mb-0">Data cuaca Sentani sedang tidak tersedia.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS KEDUA: GEMPA & PERINGATAN DINI --}}
    <div class="row g-4 mb-5">
        {{-- BAGIAN GEMPABUMI TERKINI --}}
        <div class="col-lg-6 col-md-12">
            <h5 class="fw-bold mb-3">Gempabumi Terkini (M > 5.0)</h5>
            {{-- HAPUS h-100 di bawah ini --}}
            <div class="card border-0 shadow-sm text-white" style="border-radius: 15px; background-color: #2c3e50; min-height: 250px;">
                <div class="card-body p-4">
                    @if($gempa)
                        <div class="row align-items-center">
                            <div class="col-sm-5 text-center border-sm-end border-secondary mb-3 mb-sm-0">
                                <img src="https://data.bmkg.go.id/DataMKG/TEWS/{{ $gempa->shakemap }}" 
                                    class="img-fluid rounded border border-secondary shadow-sm mb-2" 
                                    style="max-height: 120px;"
                                    onerror="this.src='https://via.placeholder.com/200x200?text=Peta+Tidak+Tersedia';">
                                <p class="small mb-0 text-warning fw-bold">{{ $gempa->Tanggal }}</p>
                                <p class="small opacity-75 mb-0">{{ $gempa->Jam }}</p>
                            </div>
                            <div class="col-sm-7 ps-sm-3">
                                <span class="badge bg-danger mb-2 px-3 py-2 fs-6">{{ $gempa->Magnitude }} SR</span>
                                <p class="small mb-2 text-warning fw-bold" style="line-height: 1.2;">{{ $gempa->Wilayah }}</p>
                                <table class="table table-sm table-dark table-borderless small mb-0 opacity-75">
                                    <tr><td style="width: 80px;">Kedalaman</td><td>: {{ $gempa->Kedalaman }}</td></tr>
                                    {{-- Pastikan variabel sesuai dengan di Controller --}}
                                    <tr><td>Potensi</td><td class="text-warning">: {{ $gempa->Potensi }}</td></tr>
                                </table>
                            </div>
                        </div>
                    @else
                        <p class="text-center py-5">Data gempa tidak tersedia.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- BAGIAN PERINGATAN DINI --}}
        <div class="col-lg-6 col-md-12">
            <h5 class="fw-bold mb-3">Peringatan Dini Papua</h5>
            {{-- HAPUS h-100 di bawah ini --}}
            <div class="card border-0 shadow-sm" style="border-radius: 15px; border-left: 6px solid #0d6efd !important; min-height: 250px;">
                <div class="card-body p-4 d-flex flex-column">
                    @if($peringatan)
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-exclamation-triangle-fill text-primary fs-3 me-2"></i>
                            <h6 class="fw-bold mb-0 text-dark">{{ $peringatan->title }}</h6>
                        </div>
                        <div class="small text-secondary mb-3" style="text-align: justify;">
                            {!! Str::limit(strip_tags($peringatan->description), 250) !!}
                        </div>
                        <div class="mt-auto">
                            {{-- Ubah link href-nya --}}
                            <a href="{{ route('peringatan.index') }}" class="btn btn-sm btn-primary rounded-pill px-4">Detail Selengkapnya</a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-shield-check text-success display-6"></i>
                            <p class="mt-2 text-muted fw-bold mb-0">Cuaca Papua Aman</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- 4. BAGIAN BERITA --}}
    <div class="row mt-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0" style="color: #2c3e50;">Berita Terkini</h5>
                <a href="#" class="text-decoration-none small fw-bold">Lihat Semua Berita <i class="bi bi-arrow-right"></i></a>
            </div>
            
            @if($berita->isEmpty())
                <div class="text-center py-5 bg-light rounded-3">
                    <p class="text-muted mb-0">Belum ada berita yang diterbitkan.</p>
                </div>
            @else
                <div class="row g-4">
                    @php $utama = $berita->first(); @endphp
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; overflow: hidden;">
                            <img src="{{ asset('img/uploads/'.$utama->image) }}" class="card-img-top" style="height: 350px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/800x400?text=Berita+Stamet+Sentani'">
                            <div class="card-body p-4">
                                <h4 class="fw-bold mb-3">{{ $utama->title }}</h4>
                                <p class="text-muted mb-4">{{ Str::limit(strip_tags($utama->content), 180) }}</p>
                                <a href="{{ route('news.show', $utama->id) }}" class="btn btn-link p-0 text-decoration-none fw-bold">Baca Selengkapnya &rarr;</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="d-flex flex-column gap-3">
                            @foreach($berita->skip(1) as $item)
                            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('img/uploads/'.$item->image) }}" class="rounded-3" style="width: 110px; height: 85px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/110x85?text=News'">
                                        <div class="ms-3">
                                            <h6 class="fw-bold mb-1" style="font-size: 0.95rem; line-height: 1.3;">{{ Str::limit($item->title, 65) }}</h6>
                                            <a href="{{ route('news.show', $item->id) }}" class="small text-decoration-none fw-bold">Lihat Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection