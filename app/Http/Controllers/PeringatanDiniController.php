<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PeringatanDiniController extends Controller
{
    public function index()
    {
        // 1. Gunakan URL yang sama persis dengan WelcomeController
        $rss_url = "https://www.bmkg.go.id/alerts/nowcast/id/rss.xml?v=" . time(); 
        $alerts = [];

        // 2. Daftar wilayah standar Papua
        $wilayahPapua = ['Papua', 'Jayapura', 'Sentani', 'Keerom', 'Sarmi', 'Biak', 'Serui', 'Nabire', 'Mimika', 'Merauke', 'Wamena'];

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
                        $content = $title . ' ' . $description;
                        
                        $isPapua = false;
                        foreach ($wilayahPapua as $wilayah) {
                            // stripos mencari tanpa peduli besar/kecil huruf
                            if (stripos($content, $wilayah) !== false) {
                                $isPapua = true;
                                break;
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
                    }
                }
            }
        } catch (\Exception $e) {
            // Jika error, kirim array kosong agar view tidak crash
            $alerts = [];
        }

        // Debugging sederhana: Jika masih kosong, coba hapus filter Papua sementara 
        // untuk memastikan data RSS BMKG memang bisa ditarik.
        return view('peringatandini', compact('alerts'));
    }

    public function getCapDetail(Request $request)
    {
        $url = $request->query('url');
        if (!$url) return response()->json(['error' => 'URL required'], 400);

        try {
            // Gunakan Http client agar konsisten dengan bypass SSL
            $response = Http::withoutVerifying()->get($url);
            $xml = simplexml_load_string($response->body());
            
            $namespaces = $xml->getNamespaces(true);
            $cap_ns = $namespaces[""] ?? "urn:oasis:names:tc:emergency:cap:1.2";
            $info = $xml->children($cap_ns)->info;

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

            return response()->json([
                'polygons' => $polygons,
                'headline' => (string)($info->headline ?? 'Peringatan Dini'),
                'area'     => (string)($info->area->areaDesc ?? 'Wilayah Papua'),
                'description' => (string)($info->description ?? ''),
                'instruction' => (string)($info->instruction ?? 'Tetap waspada.')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }
}