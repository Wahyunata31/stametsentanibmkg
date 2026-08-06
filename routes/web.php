<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminNewsController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\GempaController;
use App\Http\Controllers\PeringatanDiniController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\SatelliteController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\RadarOverlayController;
use App\Http\Controllers\FlightDocumentController;
use App\Http\Controllers\AloptamaController;
use App\Models\News;

/*
|--------------------------------------------------------------------------
| Public Routes (Bisa diakses siapa saja)
|--------------------------------------------------------------------------
*/

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/berita/{id}', [AdminNewsController::class, 'show'])->name('news.show');

// Halaman Statis
Route::get('/profil', function () { return view('profil'); });
Route::get('/tugasfungsi', function () { return view('tugasfungsi'); });
Route::get('/visimisi', function () { return view('visimisi'); });
Route::get('/strukturorganisasi', function () { return view('strukturorganisasi'); });
Route::get('/sdm', function () { return view('sdm'); });
Route::get('/sejarah', function () { return view('sejarah'); });
Route::get('/aloptama', [AloptamaController::class, 'index'])->name('aloptama.index');
Route::get('/radar', function () { return view('radar'); })->name('radar.index');
//Route::get('/peringatandini', function () { return view('peringatandini', ['alerts' => []]); });
Route::get('/metartafspeci', [WeatherController::class, 'metarTafSpeci'])->name('cuaca.penerbangan');
Route::get('/pelayanandata', function () { return view('pelayanandata'); });
Route::get('/gempabumi', function () { return view('gempabumi'); });
Route::get('/citrasatelit', function () { return view('citrasatelit'); });

// Route untuk halaman publik buletin
Route::get('/buletin', [AdminNewsController::class, 'bulletinIndex'])->name('bulletin.index');

// Grouping route gempa bumi untuk mempermudah manajemen
Route::prefix('gempa')->group(function () {
    Route::get('/terbaru', [GempaController::class, 'terbaru'])->name('gempa.terbaru');
    Route::get('/m5plus', [GempaController::class, 'm5plus'])->name('gempa.m5plus');
    Route::get('/dirasakan', [GempaController::class, 'dirasakan'])->name('gempa.dirasakan');
});

//Grouping route peringatan dini untuk mempermudah manajemen
// Pastikan rute ini yang diakses di browser (contoh: localhost:8000/peringatan-dini)
Route::get('/peringatan-dini', [PeringatanDiniController::class, 'index'])->name('peringatan.index');

// Rute untuk AJAX Map
Route::get('/peringatan-dini/detail', [PeringatanDiniController::class, 'getCapDetail']);

//Rute Cuaca Prakiraan
Route::get('/cuaca-prakiraan', [WeatherController::class, 'index'])->name('cuaca.prakiraan');

/// Halaman-halaman View
Route::get('/citra-satelit', [SatelliteController::class, 'index'])->name('citra.eh');
Route::get('/citra-satelit-rp', [SatelliteController::class, 'satelliteRP'])->name('citra.rp');
Route::get('/presipitasi', [SatelliteController::class, 'precipitation'])->name('citra.presipitasi');
Route::get('/hotspot', [SatelliteController::class, 'hotspot'])->name('citra.hotspot');

// Route Stream (Satu untuk semua)
Route::get('/stream-citra', [SatelliteController::class, 'streamImage'])->name('citra.stream');

// Route untuk menyajikan citra radar terbaru secara dinamis
Route::get('/radar/latest-image.png', [RadarOverlayController::class, 'getLatestRadar'])->name('radar.latest');
Route::get('/radar/sidarma', [RadarOverlayController::class, 'getSidarmaRadar'])->name('radar.sidarma');
Route::get('/radar/biak', [RadarOverlayController::class, 'getBiakRadar'])->name('radar.biak');
Route::get('/radar/sorong', [RadarOverlayController::class, 'getSorongRadar'])->name('radar.sorong');
Route::get('/radar/wamena', [RadarOverlayController::class, 'getWamenaRadar'])->name('radar.wamena');
Route::get('/radar/timika', [RadarOverlayController::class, 'getTimikaRadar'])->name('radar.timika');
Route::get('/radar/merauke', [RadarOverlayController::class, 'getMeraukeRadar'])->name('radar.merauke');
// Route proxy untuk mengambil animasi citra satelit BMKG Region 5
Route::get('/radar/satellite-animation.gif', [RadarOverlayController::class, 'getSatelliteAnimation'])->name('radar.satellite_animation');

// Route proxy untuk mengambil data pesawat lokal
Route::get('/radar/aircraft-data', [App\Http\Controllers\RadarOverlayController::class, 'getAircraftData'])->name('radar.aircraft');

/*
|--------------------------------------------------------------------------
| Flight Document Routes (Maskapai / Public Area)
|--------------------------------------------------------------------------
*/
Route::get('/flight-document/login', [FlightDocumentController::class, 'showLogin'])->name('flightdoc.login');
Route::post('/flight-document/login', [FlightDocumentController::class, 'login'])->name('flightdoc.login.submit');
Route::post('/flight-document/logout', [FlightDocumentController::class, 'logout'])->name('flightdoc.logout');
Route::get('/flight-document/dashboard', [FlightDocumentController::class, 'dashboard'])->name('flightdoc.dashboard');
// MODIFIKASI: Mengubah Route download dari GET menjadi POST
Route::post('/flight-document/download/{id}', [FlightDocumentController::class, 'download'])->name('flightdoc.download');


/*
|--------------------------------------------------------------------------
| Authentication Routes (Login & Logout)
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Protected Routes (Hanya untuk yang sudah Login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    // Halaman Utama Input Berita Admin
    Route::get('/halaman-stamet-admin', [AdminNewsController::class, 'create'])->name('admin.create');
    
    // Proses Simpan Berita
    Route::post('/simpan-berita', [AdminNewsController::class, 'store'])->name('admin.store');
    
    // Proses Hapus Berita
    Route::delete('/hapus-berita/{id}', [AdminNewsController::class, 'destroy'])->name('admin.destroy');

    // Route untuk proses admin (simpan & hapus buletin)
    Route::post('/simpan-buletin', [AdminNewsController::class, 'storeBulletin'])->name('admin.bulletin.store');
    Route::delete('/hapus-buletin/{id}', [AdminNewsController::class, 'destroyBulletin'])->name('admin.bulletin.destroy');

    // Fitur Management Flight Document Admin
    Route::get('/admin/flight-document', [FlightDocumentController::class, 'adminIndex'])->name('admin.flightdoc.index');
    Route::post('/admin/flight-document', [FlightDocumentController::class, 'adminStore'])->name('admin.flightdoc.store');
    Route::delete('/admin/flight-document/{id}', [FlightDocumentController::class, 'adminDestroy'])->name('admin.flightdoc.destroy');
    // BARIS YANG HARUS DITAMBAHKAN:
    Route::get('/admin/flight-document/logs', [FlightDocumentController::class, 'adminLogs'])->name('admin.flightdoc.logs');
    // Halaman form input teknisi
    Route::get('/admin/aloptama', [AloptamaController::class, 'adminIndex'])->name('admin.aloptama.index');
    // Proses simpan data dari form teknisi
    Route::post('/admin/aloptama/update', [AloptamaController::class, 'update'])->name('admin.aloptama.update');
    // Tambahkan 2 rute penanganan kendala ini
    Route::post('/admin/aloptama/kendala', [AloptamaController::class, 'storeKendala'])->name('admin.aloptama.kendala.store');
    Route::post('/admin/aloptama/kendala/{id}/selesai', [AloptamaController::class, 'selesaikanKendala'])->name('admin.aloptama.kendala.selesai');
});