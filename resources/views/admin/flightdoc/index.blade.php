@extends('layouts.main')

@section('title', 'Admin - Kelola Flight Document')
@section('page_title', 'Manajemen Dokumen Penerbangan')
@section('page_description', 'Halaman khusus Admin Meteorologi untuk mengunggah dokumen penerbangan / MET-Folder maskapai.')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Flight Document</li>
@endsection

@section('content')
<div class="container my-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border border-primary-subtle rounded-4">
                <div class="card-header bg-primary text-white fw-bold py-3 rounded-top-4">
                    <i class="bi bi-upload me-1"></i> Form Upload Dokumen
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.flightdoc.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Target Maskapai</label>
                            <select name="airline" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Maskapai --</option>
                                @foreach($airlines as $airline)
                                    <option value="{{ $airline }}">{{ $airline }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Penerbangan</label>
                            <input type="text" name="flight_number" class="form-control" placeholder="Contoh: GA-650 / LNI-782" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama/Keterangan Dokumen</label>
                            <input type="text" name="document_name" class="form-control" placeholder="Contoh: Flight Doc 07 Juni 2026 Pagi" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Berkas File Document</label>
                            <input type="file" name="file_flight" class="form-control" required>
                            <div class="form-text text-danger">Format: pdf, zip, rar, doc. Maksimal 20MB.</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow">
                            <i class="bi bi-cloud-plus-fill me-1"></i> Upload Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border rounded-4">
                <div class="card-header bg-dark text-white py-2.5 rounded-top-4 d-flex justify-content-between align-items-center">
                    <span class="fw-bold">
                        <i class="bi bi-collection-play me-1"></i> List Dokumen Aktif
                    </span>
                    <a href="{{ route('admin.flightdoc.logs') }}" class="btn btn-primary fw-bold btn-sm rounded-3 shadow-sm px-3">
                        <i class="bi bi-clock-history me-1"></i> Lihat Log Riwayat Unduhan
                    </a>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="table-light">
                                    <th class="ps-3">Maskapai</th>
                                    <th>Flight No</th>
                                    <th>Keterangan</th>
                                    <th>Dibuat</th>
                                    <th class="text-center pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $doc)
                                    <tr>
                                        <td class="ps-3"><strong class="text-primary">{{ $doc->airline }}</strong></td>
                                        <td><span class="badge bg-secondary font-monospace">{{ $doc->flight_number }}</span></td>
                                        <td><small class="fw-bold text-dark">{{ $doc->document_name }}</small></td>
                                        <td><small class="text-muted">{{ $doc->created_at->format('d/m/Y H:i') }}</small></td>
                                        <td class="text-center pe-3">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('flightdoc.download', $doc->id) }}" class="btn btn-light btn-sm text-success" title="Download File" onclick="event.preventDefault(); document.getElementById('admin-download-form-{{ $doc->id }}').submit();">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                <form id="admin-download-form-{{ $doc->id }}" action="{{ route('flightdoc.download', $doc->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    <input type="hidden" name="downloader_name" value="Admin Meteorologi">
                                                    <input type="hidden" name="downloader_position" value="Administrator / Petugas Stamet">
                                                </form>

                                                <form action="{{ route('admin.flightdoc.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-light btn-sm text-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">Belum ada file flight document yang tersimpan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection