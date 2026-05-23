<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminNewsController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\GempaController;
use App\Http\Controllers\PeringatanDiniController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\SatelliteController;
use App\Http\Controllers\WelcomeController;
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
Route::get('/aloptama', function () { return view('aloptama'); });
Route::get('/radar', function () { return view('radar'); });
//Route::get('/peringatandini', function () { return view('peringatandini', ['alerts' => []]); });
Route::get('/cuaca', function () { return view('cuaca'); });
Route::get('/cuacaprakiraan', function () { return view('cuacaprakiraan'); });
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
    
});