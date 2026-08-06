<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BMKG Sentani')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
    @stack('styles')
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    html, body {
        height: 100%;
        margin: 0;
        background-color: #fcfcfc
    }
    body {
        display: flex;
        flex-direction: column;
    }
    .content-wrapper {
        flex: 1 0 auto;
    }
    .navbar-brand {
        font-size: 1.5rem;
        letter-spacing: -0.5px;
        margin-left: 30px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }
    
    /* Efek Hover Dropdown hanya aktif di layar lebar (Desktop penuh) */
    @media (min-width: 992px) {
        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0; 
        }
    }
    
    main {
        padding-bottom: 2rem;
    }
    .time-bar {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 6px 0;
        font-family: 'Arial', sans-serif;
        font-size: 14px;
    }
    .time-date { 
        color: #4b7ea9;
        font-weight: bold; 
    }
    .time-value { 
        color: #007d32;
        font-weight: bold; 
    }
    .time-label {
        color: #6c757d;
        font-size: 13px;
        margin-right: 10px;
    }
    footer {
        flex-shrink: 0;
        background-color: #343a40;
        color: white;
        text-align: center;
        padding: 20px 0;
        width: 100%;
    }
    .navbar-brand img {
        max-height: 45px;
        width: auto;
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
    .breadcrumb-item + .breadcrumb-item::before {
        content: ">>" !important;
        color: #6c757d;
        padding-right: 0.5rem;
    }
    .breadcrumb-item + .breadcrumb-item {
        padding-left: 0.5rem;
    }

    /* ==========================================================================
       MODIFIKASI RESPONSIVE & SIDEBAR OFFCANVAS (SAAT DI-SPLIT / MOBILE)
       ========================================================================== */
    @media (max-width: 991px) {
        .navbar .container-fluid {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: nowrap; 
        }
        .navbar-brand {
            margin-left: 0;
            font-size: 0.95rem; 
            white-space: normal; 
            max-width: 75%; 
        }
        .navbar-brand img {
            max-height: 35px;
        }
        
        /* Merapikan visual menu di dalam sidebar laci */
        .offcanvas-body .nav-link {
            font-weight: bold;
            color: #2c3e50 !important;
            padding: 12px 15px !important;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .offcanvas-body .dropdown-menu {
            border: none;
            background-color: #f8f9fa;
            box-shadow: none;
            padding-left: 15px;
        }
        .offcanvas-body .dropdown-item {
            padding: 10px 15px;
            border-bottom: 1px solid #f1f1f1;
        }
    }

    @media (max-width: 576px) {
        .time-bar .container-fluid {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 4px;
        }
        .time-bar {
            font-size: 12px;
            padding: 8px 12px;
        }
        .time-label {
            margin-right: 5px;
        }
        main.container {
            padding-left: 15px;
            padding-right: 15px;
            padding-top: 15px;
        }
        .card, [class*="card"] { 
            border-radius: 16px !important; 
        }
    }
</style>
</head>

<body>
    <div class="content-wrapper">

        <div class="time-bar">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div class="time-date" id="currentDate">MEMUAT TANGGAL...</div>
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
                    Stasiun Meteorologi Kelas I Sentani - Jayapura
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header bg-light border-bottom">
                        <h5 class="offcanvas-title fw-bold" id="offcanvasNavbarLabel">MENU</h5>
                        <button type="button" class="btn-close" data-bs-disconnect="offcanvas" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    
                    <div class="offcanvas-body">
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
                                <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Pelayanan Data
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ url('/pelayanandata') }}">PTSP</a></li>
                                    <li><a class="dropdown-item" href="https://dataonline.bmkg.go.id/dataonline-home">Data Online BMKG</a></li>
                                    <li><a class="dropdown-item" href="{{ route('bulletin.index') }}">Buletin</a></li>
                                </ul>
                            </div>
                            
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Gempa Bumi
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('gempa.terbaru') }}">Gempa Bumi Terbaru</a></li>
                                    <li><a class="dropdown-item" href="{{ route('gempa.m5plus') }}">Gempabumi M 5.0+</a></li>
                                    <li><a class="dropdown-item" href="{{ route('gempa.dirasakan') }}">Gempabumi Dirasakan</a></li>
                                </ul>
                            </div>
                            
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Penerbangan
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ url('/metartafspeci') }}">METAR & TAF</a></li>
                                    <li><a class="dropdown-item" href="{{ route('radar.index') }}">Flight WAJJ</a></li>
                                    <li><a class="dropdown-item" href="{{ route('flightdoc.login') }}">Flight Document</a></li>
                                    <li><a class="dropdown-item" href="https://inasiam.bmkg.go.id/?zoom=10&lat=-2.5337&lng=140.7181">INA - SIAM</a></li>
                                </ul>
                            </div>
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
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Stasiun Meteorologi Kelas I Sentani - Jayapura</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
        function updateClock() {
            const now = new Date();
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            let dateString = now.toLocaleDateString('id-ID', dateOptions).toUpperCase();
            document.getElementById('currentDate').textContent = dateString;

            const witTime = new Date(now.getTime() + (now.getTimezoneOffset() * 60000) + (9 * 3600000));
            const localHours = String(witTime.getHours()).padStart(2, '0');
            const localMins = String(witTime.getMinutes()).padStart(2, '0');
            const localSecs = String(witTime.getSeconds()).padStart(2, '0');
            document.getElementById('localTime').textContent = `${localHours} : ${localMins} : ${localSecs}`;

            const utcHours = String(now.getUTCHours()).padStart(2, '0');
            const utcMins = String(now.getUTCMinutes()).padStart(2, '0');
            const utcSecs = String(now.getUTCSeconds()).padStart(2, '0');
            document.getElementById('utcTime').textContent = `${utcHours} : ${utcMins} : ${utcSecs} UTC`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
    @stack('scripts')
</body>
</html>