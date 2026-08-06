<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AloptamaController extends Controller
{
    // Menampilkan halaman publik (aloptama.blade.php)
    public function index()
    {
        $performa = DB::table('aloptama_performa')->get()->keyBy('kode_alat');
        
        // PERBAIKAN: Tanda kurung untuk fungsi join sudah diperbaiki di sini
        $kendala = DB::table('aloptama_kendala')
            ->join('aloptama_performa', 'aloptama_kendala.kode_alat', '=', 'aloptama_performa.kode_alat')
            ->select('aloptama_kendala.*', 'aloptama_performa.nama_alat')
            ->where('aloptama_kendala.status', 'Dalam Proses')
            ->orderBy('aloptama_kendala.created_at', 'desc')
            ->get();

        return view('aloptama', compact('performa', 'kendala'));
    }

    // Menampilkan halaman input untuk Teknisi (Form SLA/OLA & Manajemen Kendala)
    public function adminIndex()
    {
        $devices = [
            'awos_sentani'    => 'AWOS KAT III Sentani',
            'awos_oksibil'    => 'AWOS KAT I Oksibil',
            'awos_kasonaweja' => 'AWOS KAT I Kasonaweja',
            'awos_keerom'     => 'AWOS KAT I Keerom (Senggeh)',
            'aws_digi'        => 'AWS Digitalisasi (AWS DIGI)',
            'konvensional'    => 'Peralatan Konvensional',
            'display_bandara' => 'Display Cuaca Bandara',
            'awos_yahukimo'   => 'AWOS KAT I Yahukimo',
            'radar_cuaca'     => 'Radar Cuaca',
        ];

        foreach ($devices as $kode => $nama) {
            $exists = DB::table('aloptama_performa')->where('kode_alat', $kode)->exists();
            if (!$exists) {
                DB::table('aloptama_performa')->insert([
                    'kode_alat'  => $kode,
                    'nama_alat'  => $nama,
                    'sla'        => 100.00,
                    'ola'        => 100.00,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $performa = DB::table('aloptama_performa')->get();
        
        // Ambil semua riwayat kendala untuk manajemen teknisi
        $riwayatKendala = DB::table('aloptama_kendala')
            ->join('aloptama_performa', 'aloptama_kendala.kode_alat', '=', 'aloptama_performa.kode_alat')
            ->select('aloptama_kendala.*', 'aloptama_performa.nama_alat')
            ->orderBy('aloptama_kendala.created_at', 'desc')
            ->get();

        return view('admin.aloptama_input', compact('performa', 'riwayatKendala'));
    }

    // Menyimpan pembaruan nilai SLA & OLA
    public function update(Request $request)
    {
        foreach ($request->performa as $id => $data) {
            DB::table('aloptama_performa')->where('id', $id)->update([
                'sla'        => $data['sla'],
                'ola'        => $data['ola'],
                'updated_at' => now(),
            ]);
        }
        return redirect()->back()->with('success', 'Data SLA & OLA berhasil diperbarui!');
    }

    // Menyimpan laporan kendala baru dari teknisi
    public function storeKendala(Request $request)
    {
        $request->validate([
            'kode_alat'         => 'required',
            'deskripsi_kendala' => 'required',
        ]);

        DB::table('aloptama_kendala')->insert([
            'kode_alat'         => $request->kode_alat,
            'deskripsi_kendala' => $request->deskripsi_kendala,
            'status'            => 'Dalam Proses',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return redirect()->back()->with('success_kendala', 'Laporan kendala baru berhasil ditambahkan!');
    }

    // Mengubah status kendala menjadi selesai
    public function selesaikanKendala($id)
    {
        DB::table('aloptama_kendala')->where('id', $id)->update([
            'status'     => 'Selesai',
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success_kendala', 'Status kendala berhasil diubah menjadi Selesai!');
    }
}