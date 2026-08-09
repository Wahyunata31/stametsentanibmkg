<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PeringatanDiniController extends Controller
{
    private $headers = [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
        'Accept'     => 'application/json, text/plain, text/xml, */*',
    ];

    public function index()
    {
        // 1. Ambil list dari Cache yang sudah difetch oleh Cron Command `bmkg:fetch`
        $alerts = Cache::get('peringatan_dini_papua_list');

        // 2. Jika Cache Kosong, Lakukan Live Fetch
        if ($alerts === null) {
            $alerts = [];
            try {
                $resRss = Http::withoutVerifying()
                    ->withHeaders($this->headers)
                    ->retry(2, 300)
                    ->timeout(6)
                    ->get("https://www.bmkg.go.id/alerts/nowcast/id/rss.xml");

<<<<<<< HEAD
                if ($resRss->successful()) {
                    $xmlRss = @simplexml_load_string($resRss->body());
                    $wilayahPapua = [
                        'Papua', 'Jayapura', 'Sentani', 'Keerom', 'Sarmi', 'Biak', 'Serui', 
                        'Nabire', 'Mimika', 'Merauke', 'Wamena', 'Mappi', 'Asmat', 'Boven Digoel',
                        'Pegunungan', 'Intan Jaya', 'Paniai', 'Puncak', 'Dogiyai', 'Deiyai', 'Sorong', 'Manokwari'
                    ];

                    if (isset($xmlRss->channel->item)) {
                        foreach ($xmlRss->channel->item as $item) {
                            $title = (string)$item->title;
                            $description = (string)$item->description;
                            $fullContent = $title . ' ' . $description;

                            $isPapua = false;
                            foreach ($wilayahPapua as $wilayah) {
                                if (stripos($fullContent, $wilayah) !== false) {
                                    $isPapua = true;
                                    break;
                                }
                            }

                            if ($isPapua) {
                                $pulauLain = ['Sumatera', 'Jawa', 'Sulawesi', 'Kalimantan', 'Bali', 'Nusa Tenggara', 'Maluku'];
                                foreach ($pulauLain as $pulau) {
                                    if (stripos($fullContent, $pulau) !== false && stripos($fullContent, 'Papua') === false) {
                                        $isPapua = false;
                                        break;
                                    }
                                }
                            }

                            if ($isPapua) {
                                $alerts[] = [
                                    'title'       => trim($title),
                                    'link'        => trim((string)$item->link),
                                    'description' => trim(strip_tags($description)),
                                    'pubDate'     => trim((string)$item->pubDate),
                                    'author'      => trim((string)$item->author),
                                ];
=======
        try {
            // Gunakan withoutVerifying() dan timeout seperti di WelcomeController
            $response = Http::withoutVerifying()->timeout(10)->get($rss_url);
        
            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());
                
                if (isset($xml->channel->item)) {
                    foreach ($xml->channel->item as $item) {
                        // Ambil konten mentah untuk dicek
                        $title = (string)$item->title;
                        $description = (string)$item->description;
                        //$content = $title . ' ' . $description;
                        
                        $isPapua = false;
                        foreach ($wilayahPapua as $wilayah) {
                            // stripos mencari tanpa peduli besar/kecil huruf
                            if (stripos($title, $wilayah) !== false) {
                                $isPapua = true;
                                break;
>>>>>>> c902ccb19bd36fa7ae7a0502054dfa0fe037a2ad
                            }
                        }
                    }

<<<<<<< HEAD
                    // Simpan ke Cache jika ditemukan alerts
                    if (!empty($alerts)) {
                        Cache::put('peringatan_dini_papua_list', $alerts, 1800);
=======
                        // PERUBAHAN 2: Validasi tambahan untuk menghindari lolosnya wilayah luar Papua
                        // Jika di dalam judul ada kata "Sumatera", "Jawa", "Sulawesi", atau "Kalimantan", batalkan status Papua.
                        if ($isPapua) {
                            $pengecualian = ['Sumatera', 'Jawa', 'Sulawesi', 'Kalimantan', 'Barat', 'Selatan', 'Tengah', 'Utara', 'Timur'];
                            foreach ($pengecualian as $kecuali) {
                            // Jika ada kata pengecualian selain kata 'Papua Barat' / 'Papua Selatan' dsb.
                                if (stripos($title, $kecuali) !== false && stripos($title, 'Papua') === false) {
                                    $isPapua = false;
                                    break;
                                }
                            }
                        }

                        if ($isPapua) {
                            // Simpan ke array dengan memastikan semua data adalah string murni
                            $alerts[] = [
                                'title'       => trim($title),
                                'link'        => trim((string)$item->link),
                                'description' => trim(strip_tags($description)),
                                'pubDate'     => trim((string)$item->pubDate),
                                'author'      => trim((string)$item->author),
                            ];
                        }
>>>>>>> c902ccb19bd36fa7ae7a0502054dfa0fe037a2ad
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Gagal fetch RSS Peringatan Dini: " . $e->getMessage());
            }
        }

        return view('peringatandini', compact('alerts'));
    }

    public function getCapDetail(Request $request)
    {
        $url = $request->query('url');
        if (!$url) return response()->json(['error' => 'URL required'], 400);

        $cacheKey = "cap_detail_" . md5($url);

        $result = Cache::remember($cacheKey, 2592000, function () use ($url) { // Cache 30 Hari agar infografis tidak perlu reload ulang
            try {
                $response = Http::withoutVerifying()
                    ->withHeaders($this->headers)
                    ->retry(2, 300)
                    ->timeout(8)
                    ->get($url);

                if (!$response->successful()) return null;

                $xml = simplexml_load_string($response->body());
                $namespaces = $xml->getNamespaces(true);
                $cap_ns = $namespaces[""] ?? "urn:oasis:names:tc:emergency:cap:1.2";
                $info = $xml->children($cap_ns)->info;

                $imageUrl = isset($info->web) ? (string)$info->web : null;

                $polygons = [];
                if ($info && isset($info->area)) {
                    foreach ($info->area as $area) {
                        if (isset($area->polygon)) {
                            foreach ($area->polygon as $poly_str) {
                                $coords = [];
                                $pairs = explode(" ", trim((string)$poly_str));
                                foreach ($pairs as $pair) {
                                    $lat_lon = explode(",", $pair);
                                    if (count($lat_lon) === 2) {
                                        $coords[] = [(float)$lat_lon[0], (float)$lat_lon[1]];
                                    }
                                }
                                $polygons[] = $coords;
                            }
                        }
                    }
                }

                return [
                    'polygons'    => $polygons,
                    'image_url'   => $imageUrl,
                    'headline'    => (string)($info->headline ?? 'Peringatan Dini'),
                    'area'        => (string)($info->area->areaDesc ?? 'Wilayah Papua'),
                    'description' => (string)($info->description ?? ''),
                    'instruction' => (string)($info->instruction ?? 'Tetap waspada.')
                ];
            } catch (\Exception $e) {
                return null;
            }
        });

        if ($result) return response()->json($result);
        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }
}