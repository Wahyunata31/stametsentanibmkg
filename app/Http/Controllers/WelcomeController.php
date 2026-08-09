<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\News;
use Carbon\Carbon;

class WelcomeController extends Controller
{
    public function index()
    {
        $httpHeaders = [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            'Accept'     => 'application/json, text/plain, text/xml, */*',
        ];

        // 1. Berita Terbaru dari Database
        $berita = News::orderBy('post_date', 'desc')->take(5)->get();


        // ==========================================
        // 2. PRAKIRAAN CUACA SENTANI
        // ==========================================
        // Utamakan Cache dari Cron Command `bmkg:fetch` ('welcome_cuaca_sentani')
        $cuaca = Cache::get('welcome_cuaca_sentani');

        if (!$cuaca) {
            // Fallback: Jika cache Cron belum ada, coba cache alternatif v3
            $cuaca = Cache::get('welcome_cuaca_sentani_v3');
        }

        if (!$cuaca) {
            // Option Terakhir: Fetch Live dari API BMKG
            try {
                $responseCuaca = Http::withoutVerifying()
                    ->withHeaders($httpHeaders)
                    ->retry(2, 200)
                    ->timeout(6)
                    ->get("https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=91.03.01.2006");

<<<<<<< HEAD
                if ($responseCuaca->successful()) {
                    $rawCuaca = $responseCuaca->json();
                    if (isset($rawCuaca['data'][0]['cuaca'])) {
                        $allData = [];
                        foreach ($rawCuaca['data'][0]['cuaca'] as $hari) {
                            foreach ((array)$hari as $jam) {
                                $allData[] = $jam;
                            }
                        }

                        $now = now();
                        $filteredCuaca = [];
                        $currentWeather = null;

                        foreach ($allData as $item) {
                            $itemTime = Carbon::parse($item['local_datetime']);
                            if ($itemTime->isPast() && $itemTime->diffInMinutes($now) <= 180) {
                                $currentWeather = $item;
                            }
                            if ($itemTime->isFuture()) {
                                $filteredCuaca[] = $item;
                            }
                        }

                        if (!$currentWeather && count($allData) > 0) {
                            $currentWeather = $allData[0];
                        }

                        if ($currentWeather) {
                            $cuaca = [
                                'current' => $currentWeather,
                                'next'    => array_slice($filteredCuaca, 0, 12)
                            ];
                            Cache::put('welcome_cuaca_sentani', $cuaca, 1800);
=======
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
>>>>>>> c902ccb19bd36fa7ae7a0502054dfa0fe037a2ad
                        }
                    }
                }
            } catch (\Exception $e) {}
        }


        // ==========================================
        // 3. GEMPABUMI TERKINI (MULTI-LAYER CACHE FIX)
        // ==========================================
        $gempa = null;

        // Layer A: Cek Cache dari Cron Command `FetchBmkgData`
        $gempaObj = Cache::get('welcome_gempa_terkini');

        // Layer B: Cek Cache XML Mentah dari `GempaController` ('gempa_terbaru_xml')
        if (!$gempaObj) {
            $xmlRaw = Cache::get('gempa_terbaru_xml');
            if ($xmlRaw) {
                try {
                    $xml = @simplexml_load_string($xmlRaw);
                    if ($xml && isset($xml->gempa)) {
                        $gempaObj = json_decode(json_encode($xml->gempa));
                        $gempaObj->lintang  = (string)($gempaObj->Lintang ?? '');
                        $gempaObj->bujur    = (string)($gempaObj->Bujur ?? '');
                        $gempaObj->shakemap = trim((string)($gempaObj->Shakemap ?? ''));
                    }
                } catch (\Exception $e) {}
            }
<<<<<<< HEAD
        }

        // Layer C: Jika Semua Cache Kosong -> Fetch Live XML dari BMKG
        if (!$gempaObj) {
            try {
                $responseGempa = Http::withoutVerifying()
                    ->withHeaders($httpHeaders)
                    ->retry(2, 200)
                    ->timeout(6)
                    ->get("https://data.bmkg.go.id/DataMKG/TEWS/autogempa.xml");

                if ($responseGempa->successful()) {
                    $xml = @simplexml_load_string($responseGempa->body());
                    if ($xml && isset($xml->gempa)) {
                        $gempaObj = json_decode(json_encode($xml->gempa));
                        $gempaObj->lintang  = (string)($gempaObj->Lintang ?? '');
                        $gempaObj->bujur    = (string)($gempaObj->Bujur ?? '');
                        $gempaObj->shakemap = trim((string)($gempaObj->Shakemap ?? ''));

                        // Simpan ke Cache
                        Cache::put('welcome_gempa_terkini', $gempaObj, 3600);
                        Cache::put('gempa_terbaru_xml', $responseGempa->body(), 3600);
                    }
                }
            } catch (\Exception $e) {}
        }

        $gempa = $gempaObj ? (object) $gempaObj : null;


        // ==========================================
        // 4. PERINGATAN DINI PAPUA
        // ==========================================
        // Utamakan Cache dari Cron Command `bmkg:fetch` ('welcome_peringatan_papua')
        $peringatan = Cache::get('welcome_peringatan_papua');

        if (!$peringatan) {
            $peringatanData = Cache::get('welcome_peringatan_papua_v3');
            $peringatan = $peringatanData ? (object) $peringatanData : null;
        }
=======
        } catch (\Exception $e) {} //
>>>>>>> c902ccb19bd36fa7ae7a0502054dfa0fe037a2ad

        return view('welcome', compact('berita', 'cuaca', 'gempa', 'peringatan'));
    }
}