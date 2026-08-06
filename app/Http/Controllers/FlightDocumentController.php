<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlightDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class FlightDocumentController extends Controller
{
    // Pasangkan langsung nama maskapai (Key) dengan Password-nya (Value)
    private $airlines = [
        'Garuda Indonesia' => 'garuda@sentani26',
        'Lion Air'          => 'lion@sentani26',
        'Batik Air'         => 'batik@sentani26',
        'Citilink'          => 'citilink@sentani26',
        'Sriwijaya Air'     => 'sriwijaya@sentani26',
        'Trigana Air'       => 'trigana@sentani26',
        'Wings Air'         => 'wings@sentani26',
        'Susi Air'          => 'susiair@sentani26',
        'AURI'              => 'auri@sentani26',
        'Yajasi'            => 'yajasi@sentani26'
    ];

    public function showLogin()
    {
        if (session()->has('airline_auth')) {
            return redirect()->route('flightdoc.dashboard');
        }
        // Mengirimkan array key (nama maskapai saja) ke halaman view login
        return view('flightdoc.login', ['airlines' => array_keys($this->airlines)]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'airline' => 'required',
            'password' => 'required',
        ]);

        // Cek apakah maskapai terdaftar dan password yang diinput cocok dengan array di atas
        if (array_key_exists($request->airline, $this->airlines) && $this->airlines[$request->airline] === $request->password) {
            session(['airline_auth' => $request->airline]);
            return redirect()->route('flightdoc.dashboard');
        }

        return back()->withErrors(['password' => 'Password maskapai salah atau tidak terdaftar.']);
    }

    // Logout Maskapai
    public function logout()
    {
        session()->forget('airline_auth');
        return redirect()->route('flightdoc.login');
    }

    // Dashboard Download Maskapai
    public function dashboard()
    {
        if (!session()->has('airline_auth')) {
            return redirect()->route('flightdoc.login');
        }

        $airline = session('airline_auth');
        // Ambil dokumen khusus milik maskapai yang sedang login
        $documents = FlightDocument::where('airline', $airline)->latest()->get();

        return view('flightdoc.dashboard', compact('airline', 'documents'));
    }

    // Proses Download File
/*     public function download($id)
    {
        $doc = FlightDocument::findOrFail($id);
        
        // Memastikan maskapai yang login hanya bisa mengunduh file miliknya sendiri
        if (session('airline_auth') !== $doc->airline && !auth()->check()) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        if (Storage::disk('public')->exists($doc->file_path)) {
            return Storage::disk('public')->download($doc->file_path, $doc->document_name . '.' . pathinfo($doc->file_path, PATHINFO_EXTENSION));
        }

        return back()->with('error', 'File fisik tidak ditemukan di server.');
    } */


    /* ==========================================
       FITUR ADMIN (UPLOAD & KELOLA)
       ========================================== */

    // Halaman Admin: List & Form Upload
    // Halaman Admin: List & Form Upload
    public function adminIndex()
    {
        $documents = FlightDocument::latest()->get();
        return view('admin.flightdoc.index', [
            'documents' => $documents,
            'airlines' => array_keys($this->airlines) // <-- PERBAIKAN: Tambahkan array_keys() di sini
        ]);
    }

    // Admin: Proses Simpan Upload File
    public function adminStore(Request $request)
    {
        $request->validate([
            'airline' => 'required',
            'flight_number' => 'required|string|max:20',
            'document_name' => 'required|string|max:255',
            'file_flight' => 'required|file|mimes:pdf,zip,rar,doc,docx,png,jpg|max:20480', // max 20MB
        ]);

        if ($request->hasFile('file_flight')) {
            $file = $request->file('file_flight');
            $extension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['pdf', 'zip', 'rar', 'doc', 'docx', 'png', 'jpg', 'jpeg'];

            // Validasi manual ekstensi file
            if (!in_array($extension, $allowedExtensions)) {
                return back()->withErrors(['file_flight' => 'Format file tidak diizinkan! Gunakan format: pdf, zip, rar, doc, docx, png, jpg.']);
            }

            // Menyimpan di storage/app/public/flight_documents
            $path = $file->store('flight_documents', 'public');

            FlightDocument::create([
                'airline' => $request->airline,
                'flight_number' => strtoupper($request->flight_number),
                'document_name' => $request->document_name,
                'file_path' => $path,
            ]);

            return back()->with('success', 'Flight Document berhasil diupload!');
        }

        return back()->with('error', 'Gagal mengupload file.');
    }

    // Admin: Hapus Dokumen
    public function adminDestroy($id)
    {
        $doc = FlightDocument::findOrFail($id);
        
        // Hapus file dari penyimpanan
        if (Storage::disk('public')->exists($doc->file_path)) {
            Storage::disk('public')->delete($doc->file_path);
        }

        $doc->delete();
        return back()->with('success', 'Flight Document berhasil dihapus.');
    }

    // Proses Download File (Telah dimodifikasi)
    public function download(Request $request, $id)
    {
        // 1. Validasi input data diri pengunduh
        $request->validate([
            'downloader_name' => 'required|string|max:255',
            'downloader_position' => 'required|string|max:255',
        ]);

        $doc = FlightDocument::findOrFail($id);
        
        // Memastikan maskapai yang login hanya bisa mengunduh file miliknya sendiri
        if (session('airline_auth') !== $doc->airline && !auth()->check()) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        if (Storage::disk('public')->exists($doc->file_path)) {
            
            // 2. Simpan informasi log pengunduh ke database
            DB::table('flight_document_downloads')->insert([
                'flight_document_id' => $doc->id,
                'airline' => session('airline_auth') ?? $doc->airline,
                'downloader_name' => $request->downloader_name,
                'downloader_position' => $request->downloader_position,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Return file untuk diunduh oleh browser
            return Storage::disk('public')->download(
                $doc->file_path, 
                $doc->document_name . '.' . pathinfo($doc->file_path, PATHINFO_EXTENSION)
            );
        }

        return back()->with('error', 'File fisik tidak ditemukan di server.');
    }

    // Admin: Menampilkan Halaman Log Pengunduh Flight Document
    public function adminLogs()
    {
        // Mengambil data dari tabel logs dan join ke tabel dokumen penerbangan
        $logs = DB::table('flight_document_downloads')
            ->join('flight_documents', 'flight_document_downloads.flight_document_id', '=', 'flight_documents.id')
            ->select(
                'flight_document_downloads.*',
                'flight_documents.document_name',
                'flight_documents.flight_number'
            )
            ->orderBy('flight_document_downloads.created_at', 'desc')
            ->get();

        return view('admin.flightdoc.logs', compact('logs'));
    }
}