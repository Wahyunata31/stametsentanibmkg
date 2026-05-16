<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class SatelliteController extends Controller
{
    public function index()
    {
        return view('citrasatelit');
    }

    // Menampilkan halaman Citra Satelit RP
    public function satelliteRP()
    {
        return view('prakiraan.satelit_rp');
    }

    // Menampilkan halaman Presipitasi
    public function precipitation()
    {
        return view('prakiraan.presipitasi');
    }

    // Menampilkan halaman Hotspot
    public function hotspot()
    {
        return view('prakiraan.hotspot');
    }

    public function streamImage(Request $request)
    {
        $sources = [
            'satelit_eh'  => 'http://202.90.198.22/IMAGE/HIMA/H08_EH_Papua.png', 
            'satelit_rp'  => 'http://202.90.198.22/IMAGE/HIMA/H08_RP_Papua.png',
            'presipitasi' => 'http://202.90.198.22/IMAGE/GSMAP/GSMaP_Precipitation_24hr_Papua.png',
            'hotspot'     => 'http://202.90.198.22/IMAGE/HOTSPOT/Hotspot_Papua.png',
        ];

        // Mengambil tipe dari query string (?type=...)
        $type = $request->query('type');

        // Validasi: Jika type tidak ada di daftar, hentikan proses
        if (!$type || !isset($sources[$type])) {
            return abort(404, 'Tipe citra tidak ditemukan.');
        }

        $url = $sources[$type];

        try {
            $response = Http::timeout(10)->get($url);
            
            if ($response->successful()) {
                return response($response->body())
                    ->header('Content-Type', 'image/png')
                    ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
            }
        } catch (\Exception $e) {
            return abort(404, 'Gagal mengambil gambar dari server pusat.');
        }
    }
}