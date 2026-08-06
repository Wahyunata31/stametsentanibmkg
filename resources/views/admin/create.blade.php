@extends('layouts.main')

@section('content')
<div class="container py-5">
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Panel Manajemen Konten</h4>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-danger">Logout</button>
        </form>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Tambah Berita Stasiun Meteorologi</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Judul Berita</label>
                            <input type="text" name="title" class="form-control" required placeholder="Masukkan judul berita">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="post_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar Berita</label>
                            <input type="file" name="image" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Isi / Ringkasan Berita</label>
                            <textarea name="content" class="form-control" rows="5" required placeholder="Tuliskan detail berita di sini..."></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Terbitkan Berita</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah Buletin Bulanan</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.bulletin.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Judul/Edisi Buletin</label>
                            <input type="text" name="bulletin_title" class="form-control" required placeholder="Contoh: Buletin Edisi Januari 2026">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Terbit</label>
                            <input type="date" name="publish_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar Sampul (Thumbnail)</label>
                            <input type="file" name="cover_image" class="form-control" accept="image/*" required>
                            <small class="text-muted">Format: JPG, JPEG, PNG (Maks. 2MB)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">File PDF Buletin</label>
                            <input type="file" name="pdf_file" class="form-control" accept="application/pdf" required>
                            <small class="text-muted">Format file harus berupa PDF (Maks. 10MB)</small>
                        </div>
                        <div class="d-grid" style="margin-top: 105px;"> <button type="submit" class="btn btn-success">Unggah Buletin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <div class="row">
        <div class="col-md-6">
            <h5 class="mb-3">Daftar Berita Saat Ini</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Judul</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\News::orderBy('post_date', 'desc')->get() as $n)
                        <tr>
                            <td>{{ $n->title }}</td>
                            <td>{{ $n->post_date }}</td>
                            <td>
                                <form action="{{ route('admin.destroy', $n->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <h5 class="mb-3">Daftar Buletin Saat Ini</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>Edisi Buletin</th>
                            <th>Tanggal Terbit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Bulletin::orderBy('publish_date', 'desc')->get() as $b)
                        <tr>
                            <td>{{ $b->title }}</td>
                            <td>{{ $b->publish_date }}</td>
                            <td>
                                <form action="{{ route('admin.bulletin.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buletin ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection