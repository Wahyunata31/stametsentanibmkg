@extends('layouts.main')

@section('title', 'Dashboard - ' . $airline)
@section('page_title', $airline)
@section('page_description', 'Berikut daftar flight document terbaru milik maskapai Anda yang siap untuk diunduh.')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Penerbangan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="container my-3">
    <div class="d-flex justify-content-between align-items-center mb-4 bg-light p-3 rounded-3 shadow-sm border">
        <div>
            <span class="text-muted text-uppercase small d-block">Selamat Datang, crew dari</span>
            <h4 class="fw-bold text-success mb-0"><i class="bi bi-check-circle-fill me-1"></i> {{ $airline }}</h4>
        </div>
        <form action="{{ route('flightdoc.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger fw-bold btn-sm">
                <i class="bi bi-box-arrow-left"></i> Keluar
            </button>
        </form>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>No. Penerbangan</th>
                            <th>Nama Dokumen / Keterangan</th>
                            <th>Tanggal Terbit</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $index => $doc)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                                <td><span class="badge bg-secondary p-2 font-monospace fs-6">{{ $doc->flight_number }}</span></td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $doc->document_name }}</div>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i> {{ $doc->created_at->translatedFormat('d F Y') }} <br>
                                        <i class="bi bi-clock me-1"></i> {{ $doc->created_at->format('H:i') }} WIT
                                    </small>
                                </td>
                                <td class="text-center pe-4">
                                    <button type="button" 
                                            class="btn btn-success btn-sm fw-bold px-3 shadow-sm rounded-3 btn-download-trigger"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#downloadModal"
                                            data-id="{{ $doc->id }}"
                                            data-flight="{{ $doc->flight_number }}">
                                        <i class="bi bi-cloud-arrow-down-fill me-1"></i> Download
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-folder-x display-4 d-block mb-3"></i>
                                    Belum ada berkas Flight Document yang diunggah hari ini untuk maskapai Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header bg-success text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="downloadModalLabel"><i class="bi bi-person-bounding-box me-2"></i>Verifikasi Data Pengunduh</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-shadow="none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formDownload" action="" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">Silakan isi identitas crew yang bertugas mengunduh dokumen penerbangan <strong id="modal-flight-no" class="text-dark"></strong> ini.</p>
                    
                    <div class="mb-3">
                        <label for="downloader_name" class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="downloader_name" id="downloader_name" class="form-control" placeholder="Contoh: Capt. Budi Santoso / Angga (FO)" required>
                    </div>

                    <div class="mb-3">
                        <label for="downloader_position" class="form-label fw-bold">Posisi / Jabatan</label>
                        <select name="downloader_position" id="downloader_position" class="form-select" required>
                            <option value="" selected disabled>-- Pilih Posisi --</option>
                            <option value="Captain / Pilot in Command">Captain / Pilot in Command</option>
                            <option value="First Officer / Co-Pilot">First Officer / Co-Pilot</option>
                            <option value="Flight Operation Officer (FOO) / Dispatcher">Flight Operation Officer (FOO) / Dispatcher</option>
                            <option value="Flight Attendant / Crew">Flight Attendant / Crew</option>
                            <option value="Airline Staff / Admin">Airline Staff / Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 border-top-0">
                    <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-bold px-4">
                        <i class="bi bi-download me-1"></i> Kirim & Unduh
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const downloadButtons = document.querySelectorAll('.btn-download-trigger');
        const formDownload = document.getElementById('formDownload');
        const modalFlightNo = document.getElementById('modal-flight-no');

        downloadButtons.forEach(button => {
            button.addEventListener('click', function () {
                const docId = this.getAttribute('data-id');
                const flightNo = this.getAttribute('data-flight');
                
                // Menentukan action URL Form secara dinamis mengarah ke route POST download
                formDownload.action = `/flight-document/download/${docId}`;
                modalFlightNo.textContent = `(${flightNo})`;
            });
        });
    });
</script>
@endsection