<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FetchBmkgData extends Command
{
    protected $signature = 'bmkg:fetch';
    protected $description = 'Fetch seluruh data BMKG Pusat ke Cache Lokal secara terotomatisasi';

    public function handle()
    {
        $httpHeaders = [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            'Accept'     => 'application/json, text/plain, text/xml, */*',
        ];

        // ==========================================
        // 1. GEMPA TERKINI (AUTOGEMPA HOME)
        // ==========================================
        try {
            $res = Http::withoutVerifying()->withHeaders($httpHeaders)->timeout(8)->get("https://data.bmkg.go.id/DataMKG/TEWS/autogempa.xml");
            if ($res->successful()) {
                $xml = @simplexml_load_string($res->body());
                if (isset($xml->gempa)) {
                    $gempaObj = json_decode(json_encode($xml->gempa));
                    
                    $gempaObj->lintang  = (string)($gempaObj->Lintang ?? '');
                    $gempaObj->bujur    = (string)($gempaObj->Bujur ?? '');
                    $gempaObj->shakemap = trim((string)($gempaObj->Shakemap ?? ''));

                    Cache::put('welcome_gempa_terkini', $gempaObj, 3600);
                }
            }
        } catch (\Exception $e) {}

        // ==========================================
        // 2. DATA XML GEMPA (UNTUK GEMPA CONTROLLER)
        // ==========================================
        try {
            // Gempa Terbaru
            $r1 = Http::withoutVerifying()->withHeaders($httpHeaders)->timeout(8)->get("https://data.bmkg.go.id/DataMKG/TEWS/autogempa.xml");
            if ($r1->successful()) { 
                Cache::put('gempa_terbaru_xml', $r1->body(), 1800); 
                Cache::put('gempa_terbaru_xml_fallback', $r1->body(), 2592000); 
            }

            // Gempa M 5.0+
            $r2 = Http::withoutVerifying()->withHeaders($httpHeaders)->timeout(8)->get("https://data.bmkg.go.id/DataMKG/TEWS/gempaterkini.xml");
            if ($r2->successful()) { 
                Cache::put('gempa_m5plus_xml', $r2->body(), 1800); 
                Cache::put('gempa_m5plus_xml_fallback', $r2->body(), 2592000); 
            }

            // Gempa Dirasakan
            $r3 = Http::withoutVerifying()->withHeaders($httpHeaders)->timeout(8)->get("https://data.bmkg.go.id/DataMKG/TEWS/gempadirasakan.xml");
            if ($r3->successful()) { 
                Cache::put('gempa_dirasakan_xml', $r3->body(), 1800); 
                Cache::put('gempa_dirasakan_xml_fallback', $r3->body(), 2592000); 
            }
        } catch (\Exception $e) {}

        // ==========================================
        // 3. CUACA SENTANI (HALAMAN BERANDA / WELCOME)
        // ==========================================
        try {
            $resCuaca = Http::withoutVerifying()->withHeaders($httpHeaders)->timeout(8)->get("https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=91.03.01.2006");
            if ($resCuaca->successful()) {
                $rawCuaca = $resCuaca->json();
                if (isset($rawCuaca['data'][0]['cuaca'])) {
                    $allData = [];
                    foreach ($rawCuaca['data'][0]['cuaca'] as $hari) {
                        foreach ((array)$hari as $jam) { $allData[] = $jam; }
                    }
                    $now = now();
                    $filteredCuaca = [];
                    $currentWeather = null;
                    foreach ($allData as $item) {
                        $itemTime = \Carbon\Carbon::parse($item['local_datetime']);
                        if ($itemTime->isPast() && $itemTime->diffInMinutes($now) <= 180) { $currentWeather = $item; }
                        if ($itemTime->isFuture()) { $filteredCuaca[] = $item; }
                    }
                    if (!$currentWeather && count($allData) > 0) { $currentWeather = $allData[0]; }

                    Cache::put('welcome_cuaca_sentani', [
                        'current' => $currentWeather,
                        'next'    => array_slice($filteredCuaca, 0, 12)
                    ], 1800);
                }
            }
        } catch (\Exception $e) {}

        // ==========================================
        // 4. PRE-FETCH SELURUH WILAYAH PRAKIRAAN
        // ==========================================
        $wilayahAutoFetch = [
            // --- KABUPATEN JAYAPURA ---
            // Airu
            '91.03.16.2006', '91.03.16.2005', '91.03.16.2004', '91.03.16.2003', '91.03.16.2002', '91.03.16.2001',
            // Demta
            '91.03.10.2001', '91.03.10.2003', '91.03.10.2002', '91.03.10.2006', '91.03.10.2007', '91.03.10.2004', '91.03.10.2005',
            // Sentani
            '91.03.01.2006', '91.03.01.2002', '91.03.01.1002', '91.03.01.2011', '91.03.01.3010', '91.03.01.3007', '91.03.01.2004', '91.03.01.2017', '91.03.01.2005',
            // Sentani Timur
            '91.03.02.2004', '91.03.02.3005', '91.03.02.3003', '91.03.02.2006', '91.03.02.2001', '91.03.02.2002', '91.03.02.3007',
            // Sentani Barat
            '91.03.04.2001', '91.03.04.2005', '91.03.04.2011', '91.03.04.2008', '91.03.04.3006',
            // Kemtuk
            '91.03.05.2006', '91.03.05.2003', '91.03.05.2004', '91.03.05.2002', '91.03.05.2005', '91.03.05.2008', '91.03.05.2001', '91.03.05.2009', '91.03.05.2010', '91.03.05.2007',
            // Kemtuk Gresi
            '91.03.06.1015', '91.03.06.2004', '91.03.06.2006', '91.03.06.2002', '91.03.06.2001', '91.03.06.2009', '91.03.06.2014', '91.03.06.2007', '91.03.06.2005', '91.03.06.2013', '91.03.06.2003',
            // Nimboran
            '91.03.07.1021', '91.03.07.2004', '91.03.07.2001', '91.03.07.2009', '91.03.07.3018', '91.03.07.2010', '91.03.07.2017', '91.03.07.2003', '91.03.07.2005', '91.03.07.2019', '91.03.07.2002', '91.03.07.2006', '91.03.07.2014', '91.03.07.2015',
            // Kaureh
            '91.03.11.2001', '91.03.11.2002', '91.03.11.2003', '91.03.11.2006', '91.03.11.2004',
            // Depapre
            '91.03.03.2002', '91.03.03.2003', '91.03.03.2004', '91.03.03.2001', '91.03.03.2006', '91.03.03.2005', '91.03.03.2007',
            // Nimbokrang
            '91.03.08.2002', '91.03.08.2003', '91.03.08.2004', '91.03.08.2009', '91.03.08.2005', '91.03.08.2001', '91.03.08.2007', '91.03.08.2008', '91.03.08.2006',
            // Unurum Guay
            '91.03.09.2001', '91.03.09.2002', '91.03.09.2003', '91.03.09.2004', '91.03.09.2005',
            // Ebungfao
            '91.03.12.2002', '91.03.12.3003', '91.03.12.2001', '91.03.12.2004', '91.03.12.3005',
            // Waibu
            '91.03.13.3001', '91.03.13.2006', '91.03.13.2002', '91.03.13.2003', '91.03.13.2005', '91.03.13.2004',
            // Nambluong
            '91.03.14.2005', '91.03.14.2007', '91.03.14.2008', '91.03.14.2003', '91.03.14.2002', '91.03.14.2004',
            // Yapsi
            '91.03.15.2004', '91.03.15.2002', '91.03.15.2006', '91.03.15.2005', '91.03.15.2003', '91.03.15.2008', '91.03.15.2001', '91.03.15.2007',
            // Ravenirara
            '91.03.17.3004', '91.03.17.2003', '91.03.17.2002', '91.03.17.2001',
            // Gresi Selatan
            '91.03.18.2004', '91.03.18.3002', '91.03.18.2003', '91.03.18.2001',
            // Yokari
            '91.03.19.2005', '91.03.19.2003', '91.03.19.2001', '91.03.19.2002', '91.03.19.2006',

            // --- KOTA JAYAPURA ---
            // Jayapura Utara
            '91.71.01.2008', '91.71.01.1007', '91.71.01.1002', '91.71.01.1001', '91.71.01.1004', '91.71.01.1006', '91.71.01.1005', '91.71.01.1003',
            // Jayapura Selatan
            '91.71.02.1003', '91.71.02.1006', '91.71.02.1005', '91.71.02.1001', '91.71.02.1002', '91.71.02.2007', '91.71.02.2008',
            // Abepura
            '91.71.03.1011', '91.71.03.1002', '91.71.03.1008', '91.71.03.1012', '91.71.03.1014', '91.71.03.1015', '91.71.03.1016', '91.71.03.2004', '91.71.03.2007', '91.71.03.2001',
            // Muara Tami
            '91.71.04.1004', '91.71.04.1005', '91.71.04.2006', '91.71.04.2007', '91.71.04.2008', '91.71.04.2001', '91.71.04.2003', '91.71.04.2002',
            // Heram
            '91.71.05.1001', '91.71.05.1002', '91.71.05.1004', '91.71.05.2005', '91.71.05.2003'
        ];

        foreach ($wilayahAutoFetch as $admCode) {
            try {
                $resW = Http::withoutVerifying()->withHeaders($httpHeaders)->timeout(6)
                    ->get("https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4={$admCode}");
                if ($resW->successful()) {
                    $rawW = $resW->json();
                    $rawCuacaW = $rawW['data'][0]['cuaca'] ?? null;
                    $lokasiW = $rawW['data'][0]['lokasi'] ?? null;

                    if (!empty($rawCuacaW) && is_array($rawCuacaW)) {
                        $flatItems = [];
                        foreach ($rawCuacaW as $l1) {
                            if (is_array($l1)) {
                                if (isset($l1['local_datetime']) || isset($l1['datetime']) || isset($l1['t'])) {
                                    $flatItems[] = $l1;
                                } else {
                                    foreach ($l1 as $l2) {
                                        if (is_array($l2)) { $flatItems[] = $l2; }
                                    }
                                }
                            }
                        }
                        if (!empty($flatItems)) {
                            $dataToCache = ['lokasi' => $lokasiW, 'cuaca' => $flatItems];
                            Cache::put("prakiraan_cuaca_v6_{$admCode}", $dataToCache, 10800);
                            Cache::put("prakiraan_cuaca_fallback_v6_{$admCode}", $dataToCache, 2592000);
                        }
                    }
                }
            } catch (\Exception $e) {}
        }

        // ==========================================
        // 5. PERINGATAN DINI PAPUA
        // ==========================================
        try {
            $resRss = Http::withoutVerifying()->withHeaders($httpHeaders)->timeout(8)->get("https://www.bmkg.go.id/alerts/nowcast/id/rss.xml");
            
            if ($resRss->successful()) {
                $xmlRss = @simplexml_load_string($resRss->body());
                $wilayahPapua = [
                    'Papua', 'Jayapura', 'Sentani', 'Keerom', 'Sarmi', 'Biak', 'Serui', 
                    'Nabire', 'Mimika', 'Merauke', 'Wamena', 'Mappi', 'Asmat', 'Boven Digoel',
                    'Pegunungan', 'Intan Jaya', 'Paniai', 'Puncak', 'Dogiyai', 'Deiyai', 'Sorong', 'Manokwari'
                ];

                $alertsList = [];
                $welcomePeringatan = null;

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
                            if (!$welcomePeringatan) {
                                $welcomePeringatan = [
                                    'title'       => trim($title),
                                    'description' => trim($description),
                                    'link'        => trim((string)$item->link)
                                ];
                            }
                            $alertsList[] = [
                                'title'       => trim($title),
                                'link'        => trim((string)$item->link),
                                'description' => trim(strip_tags($description)),
                                'pubDate'     => trim((string)$item->pubDate),
                                'author'      => trim((string)$item->author),
                            ];
                        }
                    }
                }

                if ($welcomePeringatan && !empty($alertsList)) {
                    Cache::put('peringatan_dini_papua_list', $alertsList, 1800);
                    Cache::put('welcome_peringatan_papua', json_decode(json_encode($welcomePeringatan)), 1800);
                } else {
                    Cache::forget('peringatan_dini_papua_list');
                    Cache::forget('welcome_peringatan_papua');
                }
            } else {
                Cache::forget('welcome_peringatan_papua');
            }
        } catch (\Exception $e) {
            Cache::forget('welcome_peringatan_papua');
        }

        // ==========================================
        // 6. METAR, TAF, SPECI (AVIATION)
        // ==========================================
        try {
            $apiKey = "6c8618ae30d96d4fa3bde89d96ff5542a374a72f0896db183f2208491ec6";
            $icao = "WAJJ";
            $headers = array_merge($httpHeaders, [
                'Authorization' => "Bearer {$apiKey}", 
                'Content-Type'  => 'application/json'
            ]);

            $metar = '-'; $taf = '-'; $speci = '-';

            $r1 = Http::withoutVerifying()->withHeaders($headers)->timeout(5)->get("https://web-aviation.bmkg.go.id/api/v1/metar/{$icao}");
            if ($r1->successful() && isset($r1->json()[$icao][0])) { $metar = $r1->json()[$icao][0]['data_text'] ?? '-'; }

            $r2 = Http::withoutVerifying()->withHeaders($headers)->timeout(5)->get("https://web-aviation.bmkg.go.id/api/v1/taf/{$icao}");
            if ($r2->successful() && isset($r2->json()[$icao][0])) { $taf = $r2->json()[$icao][0]['data_text'] ?? '-'; }

            $r3 = Http::withoutVerifying()->withHeaders($headers)->timeout(5)->get("https://web-aviation.bmkg.go.id/api/v1/metar-speci/{$icao}");
            if ($r3->successful() && isset($r3->json()[$icao][0])) { $speci = $r3->json()[$icao][0]['data_text'] ?? '-'; }

            Cache::put('aviation_wajj_data', [
                'metar' => $metar,
                'taf'   => $taf,
                'speci' => $speci
            ], 300);
        } catch (\Exception $e) {}

        $this->info('Seluruh data BMKG berhasil diperbarui ke Cache!');
    }
}