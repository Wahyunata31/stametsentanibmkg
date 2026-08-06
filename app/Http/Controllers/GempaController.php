<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GempaController extends Controller
{
    private $headers = [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
        'Accept'     => 'application/json, text/plain, text/xml, */*',
    ];

    /**
     * Halaman Gempa Bumi Terbaru (Autogempa)
     */
    public function terbaru()
    {
        $cacheKeyFresh    = 'gempa_terbaru_xml';
        $cacheKeyFallback = 'gempa_terbaru_xml_fallback';

        // 1. Ambil dari Fresh Cache
        $xmlString = Cache::get($cacheKeyFresh);

        // 2. Fetch Live jika Fresh Cache Kosong
        if (!$xmlString) {
            try {
                $response = Http::withoutVerifying()
                    ->withHeaders($this->headers)
                    ->retry(2, 300)
                    ->timeout(6)
                    ->get("https://data.bmkg.go.id/DataMKG/TEWS/autogempa.xml");

                if ($response->successful()) {
                    $xmlString = $response->body();
                    Cache::put($cacheKeyFresh, $xmlString, 1800);       // Fresh 30 Menit
                    Cache::put($cacheKeyFallback, $xmlString, 2592000); // Fallback 30 Hari
                }
            } catch (\Exception $e) {
                Log::warning("Gagal fetch gempa terbaru BMKG: " . $e->getMessage());
            }
        }

        // 3. Fallback Long-term Cache jika Live Fetch Gagal
        if (!$xmlString) {
            $xmlString = Cache::get($cacheKeyFallback);
        }

        $data = $xmlString ? @simplexml_load_string($xmlString) : null;

        return view('gempa.terbaru', compact('data'));
    }

    /**
     * Halaman Daftar Gempa M 5.0+
     */
    public function m5plus()
    {
        $cacheKeyFresh    = 'gempa_m5plus_xml';
        $cacheKeyFallback = 'gempa_m5plus_xml_fallback';

        $xmlString = Cache::get($cacheKeyFresh);

        if (!$xmlString) {
            try {
                $response = Http::withoutVerifying()
                    ->withHeaders($this->headers)
                    ->retry(2, 300)
                    ->timeout(6)
                    ->get("https://data.bmkg.go.id/DataMKG/TEWS/gempaterkini.xml");

                if ($response->successful()) {
                    $xmlString = $response->body();
                    Cache::put($cacheKeyFresh, $xmlString, 1800);
                    Cache::put($cacheKeyFallback, $xmlString, 2592000);
                }
            } catch (\Exception $e) {
                Log::warning("Gagal fetch gempa M5+ BMKG: " . $e->getMessage());
            }
        }

        if (!$xmlString) {
            $xmlString = Cache::get($cacheKeyFallback);
        }

        $data = $xmlString ? @simplexml_load_string($xmlString) : null;

        return view('gempa.m5plus', compact('data'));
    }

    /**
     * Halaman Gempa Dirasakan
     */
    public function dirasakan()
    {
        $cacheKeyFresh    = 'gempa_dirasakan_xml';
        $cacheKeyFallback = 'gempa_dirasakan_xml_fallback';

        $xmlString = Cache::get($cacheKeyFresh);

        if (!$xmlString) {
            try {
                $response = Http::withoutVerifying()
                    ->withHeaders($this->headers)
                    ->retry(2, 300)
                    ->timeout(6)
                    ->get("https://data.bmkg.go.id/DataMKG/TEWS/gempadirasakan.xml");

                if ($response->successful()) {
                    $xmlString = $response->body();
                    Cache::put($cacheKeyFresh, $xmlString, 1800);
                    Cache::put($cacheKeyFallback, $xmlString, 2592000);
                }
            } catch (\Exception $e) {
                Log::warning("Gagal fetch gempa dirasakan BMKG: " . $e->getMessage());
            }
        }

        if (!$xmlString) {
            $xmlString = Cache::get($cacheKeyFallback);
        }

        $data = $xmlString ? @simplexml_load_string($xmlString) : null;

        return view('gempa.dirasakan', compact('data'));
    }
}