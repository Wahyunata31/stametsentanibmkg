@extends('layouts.main')

@section('title', 'Admin - Riwayat Unduhan Flight Document')
@section('page_title', 'Riwayat Unduhan Dokumen')
@section('page_description', 'Daftar log crew maskapai yang telah melakukan pengisian data diri dan mengunduh berkas.')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history text-primary me-2"></i>Log Unduhan Maskapai</h4>
        <a href="{{ route('admin.flightdoc.index') }}" class="btn btn-outline-secondary fw-bold btn-sm rounded-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Maskapai</th>
                            <th>Nama Pengunduh</th>
                            <th>Posisi / Jabatan</th>
                            <th>Dokumen & No. Flight</th>
                            <th class="pe-4">Waktu Unduh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                                <td><span class="badge bg-success text-white px-2.5 py-1.5 fw-bold rounded-2">{{ $log->airline }}</span></td>
                                <td><div class="fw-bold text-dark">{{ $log->downloader_name }}</div></td>
                                <td><span class="text-secondary small">{{ $log->downloader_position }}</span></td>
                                <td>
                                    <div class="small fw-semibold text-truncate" style="max-width: 200px;">{{ $log->document_name }}</div>
                                    <span class="badge bg-secondary font-monospace" style="font-size: 0.75rem;">{{ $log->flight_number }}</span>
                                </td>
                                <td class="pe-4">
                                    <small class="text-muted d-block">{{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y') }}</small>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }} WIT</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat unduhan berkas yang tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection