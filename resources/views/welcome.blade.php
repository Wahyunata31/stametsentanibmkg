@extends('layouts.main')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    /* Membuat area scroll lebih halus */
    .overflow-auto {
        display: flex;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }

    .overflow-auto::-webkit-scrollbar {
        height: 8px; 
    }

    .overflow-auto::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }

    .overflow-auto::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3); 
        border-radius: 10px;
    }

    .overflow-auto::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
    
    /* Footer Full Width */
    .footer-full-width-breaker {
        position: relative;
        width: 100vw;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        margin-bottom: -5rem;
        background-color: #1f2d3d;
        margin-top: 60px;
    }

    .custom-footer {
        color: #b0bec5;
        font-family: Arial, sans-serif;
        width: 100%;
    }
    .custom-footer .footer-title {
        color: #ffffff;
        font-size: 18px;
        font-weight: bold;
        line-height: 1.4;
    }
    .custom-footer .footer-subtitle {
        color: #ffffff;
        font-size: 15px;
        font-weight: bold;
        letter-spacing: 0.5px;
    }
    .custom-footer a.footer-link {
        color: #b0bec5;
        text-decoration: none;
        transition: color 0.2s ease-in-out;
        font-size: 14px;
    }
    .custom-footer a.footer-link:hover {
        color: #ffffff;
    }
    .custom-footer .contact-text {
        font-size: 13.5px;
        line-height: 1.6;
    }
    .custom-footer hr.footer-divider {
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        opacity: 1;
    }
    .custom-footer .social-icons a {
        color: #b0bec5;
        font-size: 18px;
        transition: color 0.2s;
    }
    .custom-footer .social-icons a:hover {
        color: #ffffff;
    }
    .footer-map-container iframe {
        border: 0;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.3);
    }

    @media (max-width: 767.98px) {
        .footer-full-width-breaker {
            margin-bottom: -5rem;
        }
        .custom-footer {
            padding-left: 15px;
            padding-right: 15px;
        }
        .custom-footer .footer-title {
            font-size: 16px;
            text-align: center;
        }
        .custom-footer .logo-wrapper {
            justify-content: center;
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }
        .custom-footer .logo-wrapper img {
            margin-right: 0 !important;
        }
        .custom-footer .contact-text {
            font-size: 13px;
        }
        .custom-footer .footer-subtitle {
            text-align: center;
            margin-top: 15px;
        }
        .custom-footer .list-unstyled {
            align-items: center;
        }
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
                    @if(!empty($cuaca) && isset($cuaca['current']))
                        <div class="row align-items-center">
                            {{-- Sisi Kiri: Cuaca Saat Ini --}}
                            <div class="col-md-5 border-end border-white border-opacity-25">
                                <h2 class="fw-bold mb-1">Sentani</h2>
                                <p class="mb-3 opacity-75 small">Jayapura, Papua</p>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $cuaca['current']['image'] ?? '' }}" width="100" alt="Ikon Cuaca" onerror="this.src='https://api.bmkg.go.id/static/img/cuaca/cerah-berawan-am.svg';">
                                    <div class="ms-3">
                                        <span class="display-3 fw-bold">{{ $cuaca['current']['t'] ?? '-' }}°</span>
                                        <p class="fs-5 mb-0">{{ $cuaca['current']['weather_desc'] ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="mt-3 small opacity-75">
                                    <span><i class="bi bi-droplets"></i> {{ $cuaca['current']['hu'] ?? '-' }}%</span>
                                    <span class="ms-3"><i class="bi bi-wind"></i> {{ $cuaca['current']['ws'] ?? '-' }} km/jam</span>
                                </div>
                            </div>

                            {{-- Sisi Kanan: List Jam Berikutnya --}}
                            <div class="col-md-7 ps-md-4">
                                <div class="d-flex text-center mt-3 mt-md-0 overflow-auto pb-2" style="gap: 25px; scrollbar-width: thin;">
                                    @if(isset($cuaca['next']) && count($cuaca['next']) > 0)
                                        @foreach($cuaca['next'] as $h)
                                        <div style="min-width: 70px;">
                                            <p class="small mb-1 opacity-75">{{ isset($h['local_datetime']) ? \Carbon\Carbon::parse($h['local_datetime'])->format('H:i') : '-' }}</p>
                                            <img src="{{ $h['image'] ?? '' }}" width="40" class="mb-1" onerror="this.src='https://api.bmkg.go.id/static/img/cuaca/cerah-berawan-am.svg';">
                                            <p class="small fw-bold mb-0">{{ $h['t'] ?? '-' }}°</p>
                                        </div>
                                        @endforeach
                                    @endif
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

    {{-- 2. BARIS KEDUA: GEMPA & PERINGATAN DINI --}}
    <div class="row g-4 mb-5">
        {{-- BAGIAN GEMPABUMI TERKINI --}}
        <div class="col-lg-6 col-md-12">
            <h5 class="fw-bold mb-3">Gempabumi Terkini (M > 5.0)</h5>
            <div class="card border-0 shadow-sm text-white" style="border-radius: 15px; background-color: #2c3e50; min-height: 250px;">
                <div class="card-body p-4">
                    @if(!empty($gempa) && is_object($gempa))
                        <div class="row align-items-center">
                            <div class="col-sm-5 text-center border-sm-end border-secondary mb-3 mb-sm-0">
                                @php 
                                    $shakemapImg = $gempa->shakemap ?? $gempa->Shakemap ?? ''; 
                                @endphp
                                <img src="https://data.bmkg.go.id/DataMKG/TEWS/{{ $shakemapImg }}" 
                                    class="img-fluid rounded border border-secondary shadow-sm mb-2" 
                                    style="max-height: 120px;"
                                    onerror="this.src='https://via.placeholder.com/200x200?text=Peta+Tidak+Tersedia';">
                                <p class="small mb-0 text-warning fw-bold">{{ $gempa->Tanggal ?? '-' }}</p>
                                <p class="small opacity-75 mb-0">{{ $gempa->Jam ?? '-' }}</p>
                            </div>
                            <div class="col-sm-7 ps-sm-3">
                                <span class="badge bg-danger mb-2 px-3 py-2 fs-6">{{ $gempa->Magnitude ?? '-' }} SR</span>
                                <p class="small mb-2 text-warning fw-bold" style="line-height: 1.2;">{{ $gempa->Wilayah ?? '-' }}</p>
                                <table class="table table-sm table-dark table-borderless small mb-0 opacity-75">
                                    <tr>
                                        <td style="width: 80px;">Kedalaman</td>
                                        <td>: {{ $gempa->Kedalaman ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Koordinat</td>
                                        <td>: {{ $gempa->lintang ?? $gempa->Lintang ?? '-' }} - {{ $gempa->bujur ?? $gempa->Bujur ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Potensi</td>
                                        <td class="text-warning">: {{ $gempa->Potensi ?? '-' }}</td>
                                    </tr>
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
            <div class="card border-0 shadow-sm" style="border-radius: 15px; border-left: 6px solid #0d6efd !important; min-height: 250px;">
                <div class="card-body p-4 d-flex flex-column">
                    @if(!empty($peringatan))
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-exclamation-triangle-fill text-primary fs-3 me-2"></i>
                            <h6 class="fw-bold mb-0 text-dark">{{ $peringatan->title ?? '' }}</h6>
                        </div>
                        <div class="small text-secondary mb-3" style="text-align: justify;">
                            {!! Str::limit(strip_tags($peringatan->description ?? ''), 250) !!}
                        </div>
                        <div class="mt-auto">
                            <a href="{{ route('peringatan.index') }}" class="btn btn-sm btn-primary rounded-pill px-4">Detail Selengkapnya</a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-shield-check text-success display-6"></i>
<<<<<<< HEAD
                            <p class="mt-2 text-muted fw-bold mb-0">Peringatan Dini Null / Nihil</p>
=======
                            <p class="mt-2 text-muted fw-bold mb-0">Peringatan Dini Null</p>
>>>>>>> c902ccb19bd36fa7ae7a0502054dfa0fe037a2ad
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- 3. BAGIAN BERITA --}}
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0" style="color: #2c3e50;">Berita Terkini</h5>
                <a href="#" class="text-decoration-none small fw-bold">Lihat Semua Berita <i class="bi bi-arrow-right"></i></a>
            </div>
            
            @if(empty($berita) || $berita->isEmpty())
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
                                <a href="{{ route('news.show', $utama->id) }}" class="btn btn-link p-0 text-decoration-none fw-bold">Baca Selengkapnya →</a>
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

<div class="footer-full-width-breaker">
    <footer class="custom-footer py-5">
        <div class="container-fluid px-4 px-md-5">
            <div class="row g-4">
                <div class="col-12 mb-2">
                    <div class="d-flex align-items-center logo-wrapper">
                        <img src="{{ asset('favicon.png') }}" alt="Logo BMKG" class="me-3" style="height: 45px; width: auto;">
                        <h5 class="footer-title m-0">Stasiun Meteorologi Kelas I Sentani - Jayapura</h5>
                    </div>
                </div>

                <div class="col-12"><hr class="footer-divider my-2"></div>

                <div class="col-lg-3 col-md-4 col-12">
                    <div class="footer-map-container mb-3 mb-md-0">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3985.6796447814987!2d140.48443907421683!3d-2.5469591382436215!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x686cf563725b8b51%3A0x633af24237dbcb4!2sStasiun%20Meteorologi%20Sentani!5e0!3m2!1sid!2sid!4v1717580000000!5m2!1sid!2sid" 
                            width="100%" 
                            height="160" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

                <div class="col-lg-6 col-md-8 col-12 text-white text-opacity-75 contact-text px-md-4">
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-geo-alt-fill text-white me-3 fs-5 mt-1"></i>
                        <div>Jl. Bandara Sentani, Kelurahan Hinekombe, Distrik Sentani, Kabupaten Jayapura, Papua 99352</div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-envelope-fill text-white me-3 fs-5 mt-1"></i>
                        <div class="text-break">stamet.sentani@bmkg.go.id</div>
                    </div>
                    <div class="d-flex align-items-start">
                        <i class="bi bi-telephone-fill text-white me-3 fs-5 mt-1"></i>
                        <div>08114819166 (WhatsApp Pelayanan Informasi)<br></div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-12 col-12">
                    <h6 class="footer-subtitle mb-3">LINK TERKAIT</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 m-0">
                        <li><a href="https://www.bmkg.go.id" target="_blank" class="footer-link">BMKG <i class="bi bi-arrow-up-right small ms-1"></i></a></li>
                        <li><a href="https://web-aviation.bmkg.go.id/" target="_blank" class="footer-link">Meteorologi Penerbangan <i class="bi bi-arrow-up-right small ms-1"></i></a></li>
                        <li><a href="https://inasiam.bmkg.go.id/" target="_blank" class="footer-link">INASIAM <i class="bi bi-arrow-up-right small ms-1"></i></a></li>
                        <li><a href="https://cuaca.bmkg.go.id/" target="_blank" class="footer-link">Cuaca BMKG <i class="bi bi-arrow-up-right small ms-1"></i></a></li>
                        <li><a href="https://signature.bmkg.go.id/" target="_blank" class="footer-link">Signature BMKG <i class="bi bi-arrow-up-right small ms-1"></i></a></li>
                        <li><a href="https://ptsp.bmkg.go.id/pengaduan" target="_blank" class="footer-link">E-Pengaduan BMKG <i class="bi bi-arrow-up-right small ms-1"></i></a></li>
                        <li><a href="https://ppid.bmkg.go.id/" target="_blank" class="footer-link">PPID BMKG <i class="bi bi-arrow-up-right small ms-1"></i></a></li>
                    </ul>
                </div>

                <div class="col-12"><hr class="footer-divider mt-4 mb-3"></div>
                
                <div class="col-sm-6 col-12 text-center text-sm-start small mb-2 mb-sm-0">
                    © {{ date('Y') }} Stasiun Meteorologi Kelas I Sentani - Jayapura
                </div>
                
                <div class="col-sm-6 col-12 text-center text-sm-end">
                    <div class="social-icons d-flex justify-content-center justify-content-sm-end gap-3">
                        <a href="#" target="_blank" title="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" target="_blank" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" target="_blank" title="X (Twitter)"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection