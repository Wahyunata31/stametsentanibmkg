<?php

namespace App\Http\Controllers;
use App\Models\News;
use App\Models\Bulletin; // Tambahkan model Bulletin
use Illuminate\Http\Request;

class AdminNewsController extends Controller {
    
    public function create() {
        return view('admin.create'); // Membuka form input berita & buletin
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'post_date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('img/uploads'), $imageName);
        }

        News::create([
            'title' => $request->title,
            'content' => $request->content,
            'post_date' => $request->post_date,
            'image' => $imageName,
        ]);

        return redirect()->route('admin.create')->with('success', 'Berita berhasil diterbitkan!');
    }

    // --- FUNGSI BARU UNTUK PROSES INPUT BULETIN ---
    public function storeBulletin(Request $request) {
        $request->validate([
            'bulletin_title' => 'required',
            'publish_date' => 'required|date',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'pdf_file' => 'required|mimes:pdf|max:10120' // Maksimal 10MB
        ]);

        $pdfName = null;
        $coverName = null;

        // 1. Proses File Gambar Sampul
        if ($request->hasFile('cover_image')) {
            $cover = $request->file('cover_image');
            $coverName = 'cover_' . time() . '_' . $cover->getClientOriginalName();
            // Pastikan folder public/bulletins/covers sudah dibuat
            $cover->move(public_path('bulletins/covers'), $coverName);
        }

        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $pdfName = time() . '_' . $file->getClientOriginalName();
            // Simpan ke folder public/bulletins
            $file->move(public_path('bulletins'), $pdfName);
        }

        Bulletin::create([
            'title' => $request->bulletin_title,
            'cover_image' => $coverName,
            'publish_date' => $request->publish_date,
            'pdf_file' => $pdfName,
        ]);

        return redirect()->route('admin.create')->with('success', 'Buletin dan Sampul berhasil diunggah!');
    }

    // --- FUNGSI UNTUK HALAMAN DEPAN BULETIN ---
    public function bulletinIndex() {
        // Mengambil semua buletin diurutkan dari yang terbaru
        $bulletins = Bulletin::orderBy('publish_date', 'desc')->get();
        return view('buletin', compact('bulletins'));
    }

    public function index() {
        $berita = News::orderBy('post_date', 'desc')->get();
        return view('welcome', compact('berita'));
    }

    public function destroy(int $id) {
        $news = News::findOrFail($id);
        $imagePath = public_path('img/uploads/' . $news->image);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $news->delete();

        return redirect()->route('admin.create')->with('success', 'Berita berhasil dihapus!');
    }

    // --- FUNGSI BARU UNTUK HAPUS BULETIN ---
    public function destroyBulletin(int $id) {
        $bulletin = Bulletin::findOrFail($id);
        $pdfPath = public_path('bulletins/' . $bulletin->pdf_file);
        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }

        $coverPath = public_path('bulletins/covers/' . $bulletin->cover_image);
        if ($bulletin->cover_image && file_exists($coverPath)) {
            unlink($coverPath);
        }
        $bulletin->delete();

        return redirect()->route('admin.create')->with('success', 'Buletin berhasil dihapus!');
    }

    public function show(int $id) {
        $item = News::findOrFail($id);
        return view('news_detail', compact('item'));
    }
}