<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\News;

class WelcomeController extends Controller
{
    public function index()
    {
        // Ambil 5 berita terbaru
        $berita = News::orderBy('post_date', 'desc')->take(5)->get();

        // 1. Ambil Cuaca Sentani
        $cuaca = null;
        try {
            $responseCuaca = Http::withoutVerifying()->timeout(5)
                ->get("https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=91.03.01.2006");
    
            if ($responseCuaca->successful()) {
                $rawCuaca = $responseCuaca->json();
        
                if (isset($rawCuaca['data'][0]['cuaca'])) {
                    $allData = [];
                    // Gabungkan semua hari menjadi satu list linear
                    foreach ($rawCuaca['data'][0]['cuaca'] as $hari) {
                        foreach ($hari as $jam) {
                            $allData[] = $jam;
                        }
                    }

                    $now = now(); // Waktu saat ini
                    $filteredCuaca = [];
                    $currentWeather = null;

                    foreach ($allData as $item) {
                        $itemTime = \Carbon\Carbon::parse($item['local_datetime']);
                
                        // Cari data cuaca yang paling mendekati jam sekarang untuk tampilan utama
                        if ($itemTime->isPast() && $itemTime->diffInMinutes($now) < 60) {
                            $currentWeather = $item;
                        }
                
                        // Ambil data untuk jam-jam berikutnya (masa depan)
                        if ($itemTime->isFuture()) {
                            $filteredCuaca[] = $item;
                        }
                    }

                    // Jika tidak ada data yang pas "sekarang", ambil data pertama yang tersedia
                    if (!$currentWeather) {
                        $currentWeather = $allData[0];
                    }

                    $cuaca = [
                        'current' => $currentWeather,
                        'next' => array_slice($filteredCuaca, 0, 12) // Ambil 12 jam ke depan
                    ];
                }
            }
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
        }

        // 2. Ambil Gempa Terkini
        $gempa = null;
        try {
            $responseGempa = Http::withoutVerifying()->timeout(5)
                ->get("https://data.bmkg.go.id/DataMKG/TEWS/autogempa.xml");
            if ($responseGempa->successful()) {
                $xml = simplexml_load_string($responseGempa->body());
                $gempa = $xml->gempa;
                
                // Perbaikan: BMKG menggunakan PascalCase (Coordinates) bukan lowercase
                // Kita simpan ke property baru agar mudah dipanggil di view
                $gempa->display_coordinates = (string)$xml->gempa->Coordinates;
                $gempa->shakemap = trim((string)$xml->gempa->Shakemap); 
            }
        } catch (\Exception $e) {}

        // WelcomeController.php

        // 3. Ambil Peringatan Dini Papua
        $peringatan = null;
        try {
            // Menambahkan query parameter untuk menghindari cache provider jika ada
            $responseRss = Http::withoutVerifying()->timeout(5)
                ->get("https://www.bmkg.go.id/alerts/nowcast/id/rss.xml?v=" . time());
    
            if ($responseRss->successful()) {
                $xmlRss = simplexml_load_string($responseRss->body());
                if (isset($xmlRss->channel->item)) {
                    // Daftar kata kunci wilayah di tanah Papua untuk filter yang lebih akurat
                    $wilayahPapua = ['Papua', 'Jayapura', 'Sentani', 'Keerom', 'Sarmi', 'Biak', 'Serui', 'Nabire', 'Mimika', 'Merauke', 'Wamena'];
            
                    foreach ($xmlRss->channel->item as $item) { //
                        $title = (string)$item->title;
                        
                        // Periksa kata kunci Papua HANYA pada JUDUL
                        $isPapua = false;
                        foreach ($wilayahPapua as $wilayah) { //
                            if (stripos($title, $wilayah) !== false) { //
                                $isPapua = true;
                                break; //
                            }
                        }

                        // Proteksi Ganda: Jika lolos, pastikan tidak ada nama pulau lain di judulnya
                        if ($isPapua) {
                            $pengecualian = ['Sumatera', 'Jawa', 'Sulawesi', 'Kalimantan', 'Barat', 'Selatan', 'Tengah', 'Utara', 'Timur'];
                            foreach ($pengecualian as $kecuali) {
                                if (stripos($title, $kecuali) !== false && stripos($title, 'Papua') === false) {
                                    $isPapua = false;
                                    break;
                                }
                            }
                        }

                        // Jika murni peringatan untuk Papua, ambil dan langsung hentikan pencarian
                        if ($isPapua) {
                            $peringatan = $item;
                            break; 
                        }
                    }
                }
            }
        } catch (\Exception $e) {} //

        return view('welcome', compact('berita', 'cuaca', 'gempa', 'peringatan'));
    }
}