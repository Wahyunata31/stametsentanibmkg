<?php

namespace App\Http\Controllers;
use App\Models\News;
use Illuminate\Http\Request;

class AdminNewsController extends Controller {
    public function create() {
        return view('admin.create'); // Membuka form input
    }


    public function store(Request $request) {
        // 1. Validasi
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'post_date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048' // Maksimal 2MB
        ]);

        $imageName = null; // Inisialisasi awal

        // 2. Proses File Gambar
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
    
            // Simpan ke public/img/uploads
            // Pastikan folder ini sudah Anda buat secara manual
            $image->move(public_path('img/uploads'), $imageName);
        }

        // 3. Simpan ke Database
        News::create([
            'title' => $request->title,
            'content' => $request->content,
            'post_date' => $request->post_date,
            'image' => $imageName,
        ]);

        return redirect()->route('admin.create')->with('success', 'Berita berhasil diterbitkan!');
    }

    public function index() {
        $berita = \App\Models\News::orderBy('post_date', 'desc')->get();
        return view('welcome', compact('berita'));
    }
    // Tambahkan di dalam class AdminNewsController
    public function destroy(int $id) {
        $news = News::findOrFail($id);
        // Hapus file gambar secara fisik dari folder
        $imagePath = public_path('img/uploads/' . $news->image);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $news->delete();

        return redirect()->route('admin.create')->with('success', 'Berita berhasil dihapus!');
    }
    // Tambahkan fungsi ini di dalam class AdminNewsController
    public function show(int $id) {
        $item = News::findOrFail($id);
        return view('news_detail', compact('item'));
}

}
