<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BMKG Sentani')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @stack('styles') {{-- CSS Leaflet akan muncul di sini --}}
    <style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    }
        /* Mengatur agar body memenuhi tinggi layar */
    html, body {
        height: 100%;
        margin: 0;
        background-color: #fcfcfc
    }

    /* Membungkus seluruh halaman menjadi kolom flex */
    body {
        display: flex;
        flex-direction: column;
    }

    /* Bagian container konten akan mengambil sisa ruang yang ada */
    .content-wrapper {
        flex: 1 0 auto;
    }
    .navbar-brand{
        font-size: 1.5rem;
        letter-spacing: -0.5px;
        margin-left: 30px; /* Atur angka ini sesuai keinginan Anda */
        transition: all 0.3s ease;
    }
    .nav-item.dropdown:hover .dropdown-menu {
        display: block;
        margin-top: 0; 
    }
    main{
        padding-bottom: 2rem;
    }
    .time-bar {
        background-color: #f8f9fa; /* Latar belakang abu-abu muda */
        border-bottom: 1px solid #dee2e6;
        padding: 6px 0;
        font-family: 'Arial', sans-serif;
        font-size: 14px;
    }
    .time-date { 
        color: #4b7ea9; /* Warna biru untuk tanggal */
        font-weight: bold; 
    }
    .time-value { 
        color: #007d32; /* Warna hijau untuk jam */
        font-weight: bold; 
    }
    .time-label {
        color: #6c757d;
        font-size: 13px;
        margin-right: 10px;
    }

    /* Styling footer agar terlihat rapi */
    footer {
        flex-shrink: 0;
        background-color: #343a40; /* Warna gelap khas footer */
        color: white;
        text-align: center;
        padding: 20px 0;
        width: 100%;
    }
    .navbar-brand img {
        max-height: 45px; /* Sesuaikan dengan tinggi navbar Anda */
        width: auto;
    }
    @media (max-width: 991px) {
        .navbar-brand {
            margin-left: 0;
        }
    }
    .breadcrumb-item a {
        color: #4b7ea9;
        font-size: 0.9rem;
    }
    .breadcrumb-item.active {
        color: #6c757d;
        font-size: 0.9rem;
    }
    h1.fw-bold {
        color: #212529;
        letter-spacing: -1px;
        margin-top: 10px;
    }
    /* Mengubah pemisah breadcrumb menjadi >> */
    .breadcrumb-item + .breadcrumb-item::before {
        content: ">>" !important;
        color: #6c757d; /* Warna abu-abu untuk pemisah */
        padding-right: 0.5rem;
    }

    /* Menghilangkan pemisah bawaan jika masih muncul */
    .breadcrumb-item + .breadcrumb-item {
        padding-left: 0.5rem;
    }
</style>

</head>

<body>
    <div class="content-wrapper">

        <div class="time-bar">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div class="time-date" id="currentDate">
                {{-- Akan diisi otomatis oleh JS --}}
                MEMUAT TANGGAL...
                </div>

                <div class="d-flex align-items-center">
                    <span class="time-label">STANDAR WAKTU INDONESIA</span>
                    <span class="time-value" id="localTime">00 : 00 : 00</span>
                    <span class="mx-2">/</span>
                    <span class="time-value" id="utcTime">00 : 00 : 00 UTC</span>
                </div>
            </div>
        </div>
        
        <nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                    <img src="{{ asset('img/bmkglogo.png') }}" alt="Logo BMKG" width="40" height="auto" class="me-2">
                    Stasiun Meteorologi Kelas I Sentani - Jayapura</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav mx-auto">
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Profil
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('/profil') }}">Profil Stasiun</a></li>
                                <li><a class="dropdown-item" href="{{ url('/tugasfungsi') }}">Tugas dan Fungsi</a></li>
                                <li><a class="dropdown-item" href="{{ url('/visimisi') }}">Visi dan Misi</a></li>
                                <li><a class="dropdown-item" href="{{ url('/strukturorganisasi') }}">Struktur Organisasi</a></li>
                                <li><a class="dropdown-item" href="{{ url('/sdm') }}">SDM</a></li>
                                <li><a class="dropdown-item" href="{{ url('/sejarah') }}">Sejarah</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Kontak Kami</a></li>
                            </ul>
                        </div>
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Pengamatan
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('/radar') }}">Radar Papua</a></li>
                                <li><a class="dropdown-item" href="{{ url('/aloptama') }}">Aloptama</a></li>
                            </ul>
                        </div>
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Prakiraan
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('peringatan.index') }}">Peringatan Dini Cuaca</a></li>
                                <li><a class="dropdown-item" href="{{ route('cuaca.prakiraan') }}">Cuaca Prakiraan Skala Kelurahan</a></li>
                                <li><h6 class="dropdown-header">Monitoring Satelit</h6></li>
                                <li><a class="dropdown-item" href="{{ route('citra.eh') }}">Satelit Himawari (EH)</a></li>
                                <li><a class="dropdown-item" href="{{ route('citra.rp') }}">Satelit Himawari (RP)</a></li>
                                <li><a class="dropdown-item" href="{{ route('citra.presipitasi') }}">Presipitasi (GSMaP)</a></li>
                                <li><a class="dropdown-item" href="{{ route('citra.hotspot') }}">Sebaran Hotspot</a></li>
                            </ul>
                        </div>
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" role="button" aria-expanded="false">
                                Pelayanan Data
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarGempa">
                                <li>
                                    <a class="dropdown-item" href="{{ url('/pelayanandata') }}">PTSP</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="https://dataonline.bmkg.go.id/dataonline-home">Data Online BMKG</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('bulletin.index') }}">Buletin</a>
                                </li>
                            </ul>
                        </div>
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" id="navbarGempa" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Gempa Bumi
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarGempa">
                                <li>
                                    <a class="dropdown-item" href="{{ route('gempa.terbaru') }}">Gempa Bumi Terbaru</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('gempa.m5plus') }}">Gempabumi M 5.0+</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('gempa.dirasakan') }}">Gempabumi Dirasakan</a>
                                </li>
                            </ul>
                        </div>
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Penerbangan
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">METAR & TAF</a></li>
                                <li><a class="dropdown-item" href="#">Flight WAJJ</a></li>
                                <li><a class="dropdown-item" href="#">Flight Document</a></li>
                                <li><a class="dropdown-item" href="https://inasiam.bmkg.go.id/?zoom=10&lat=-2.5337&lng=140.7181">INA - SIAM</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        @if (!Request::is('/'))
        <section class="py-5 mb-4 shadow-sm" style="background-color: #f0f5f9; border-bottom: 1px solid #e3e6f0">
            <div class="container text-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-2">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none" style="color: #4b7ea9">Beranda</a></li>
                        @yield('breadcrumb')
                    </ol>
                </nav>

                <h1 class="fw-bold display-6" style="color: #212329">@yield('page_title')</h1>
        
                <p class="text-muted mx-auto mt-3" style="max-width: 900px; font-size: 0.95rem">
                    @yield('page_description')
                </p>
            </div>
        </section>
        @endif

        <main class="container">
            @yield('content')
        </main> 
            
    </div>

    <footer class="py-4">
        <div class="container text-center"></div>
            <p class="mb-0"&copy; {{ date('Y') }}>Stasiun Meteorologi Kelas I Sentani - Jayapura</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
        function updateClock() {
            const now = new Date();

        // 1. Update Tanggal (Format: SELASA, 3 FEBRUARI 2026)
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            let dateString = now.toLocaleDateString('id-ID', dateOptions).toUpperCase();
            document.getElementById('currentDate').textContent = dateString;

        // 2. Update Jam Lokal (WIT - UTC+9 untuk Sentani/Jayapura)
        // Menghitung waktu WIT secara presisi
            const witTime = new Date(now.getTime() + (now.getTimezoneOffset() * 60000) + (9 * 3600000));
            const localHours = String(witTime.getHours()).padStart(2, '0');
            const localMins = String(witTime.getMinutes()).padStart(2, '0');
            const localSecs = String(witTime.getSeconds()).padStart(2, '0');
            document.getElementById('localTime').textContent = `${localHours} : ${localMins} : ${localSecs}`;

        // 3. Update Jam UTC
            const utcHours = String(now.getUTCHours()).padStart(2, '0');
            const utcMins = String(now.getUTCMinutes()).padStart(2, '0');
            const utcSecs = String(now.getUTCSeconds()).padStart(2, '0');
            document.getElementById('utcTime').textContent = `${utcHours} : ${utcMins} : ${utcSecs} UTC`;
            }

        // Jalankan fungsi setiap 1 detik
            setInterval(updateClock, 1000);
            updateClock(); // Panggil sekali saat halaman pertama dimuat
    </script>
    @stack('scripts')
</body>
</html>