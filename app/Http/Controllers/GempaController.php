<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GempaController extends Controller
{
    public function terbaru()
    {
        // Pastikan ada titik koma (;) di akhir baris ini
        $data = simplexml_load_file("https://data.bmkg.go.id/DataMKG/TEWS/autogempa.xml") or die("Gagal akses data"); 
        
        return view('gempa.terbaru', compact('data'));
    }

    public function m5plus()
    {
        // Pastikan ada titik koma (;) di akhir baris ini
        $data = simplexml_load_file("https://data.bmkg.go.id/DataMKG/TEWS/gempaterkini.xml") or die("Gagal akses data");
        
        return view('gempa.m5plus', compact('data'));
    }

    public function dirasakan()
    {
        // Pastikan ada titik koma (;) di akhir baris ini
        $data = simplexml_load_file("https://data.bmkg.go.id/DataMKG/TEWS/gempadirasakan.xml") or die("Gagal akses data");
        
        return view('gempa.dirasakan', compact('data'));
    }
}