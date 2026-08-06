@extends('layouts.main')

@section('title', 'Input Performa & Kendala Aloptama')

@section('content')
<div class="container mt-4 mb-5">
    <div class="card border-0 shadow-sm p-4 mb-4">
        <h2 class="h4 text-primary border-bottom pb-2 mb-3">Update Nilai SLA & OLA oleh Teknisi</h2>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.aloptama.update') }}" method="POST">
            @csrf
            <table class="table table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th class="text-start">Nama Alat</th>
                        <th>SLA (%)</th>
                        <th>OLA (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($performa as $item)
                    <tr>
                        <td class="text-start fw-bold">{{ $item->nama_alat }}</td>
                        <td>
                            <input type="number" step="0.01" min="0" max="100" name="performa[{{ $item->id }}][sla]" value="{{ $item->sla }}" class="form-control text-center mx-auto" style="max-width: 100px;">
                        </td>
                        <td>
                            <input type="number" step="0.01" min="0" max="100" name="performa[{{ $item->id }}][ola]" value="{{ $item->ola }}" class="form-control text-center mx-auto" style="max-width: 100px;">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Simpan Perubahan Performa</button>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h2 class="h4 text-danger border-bottom pb-2 mb-3">Laporkan Kendala Baru</h2>
                @if(session('success_kendala'))
                    <div class="alert alert-success">{{ session('success_kendala') }}</div>
                @endif

                <form action="{{ route('admin.aloptama.kendala.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Alat/Perangkat</label>
                        <select name="kode_alat" class="form-select" required>
                            <option value="">-- Pilih Alat --</option>
                            @foreach($performa as $item)
                                <option value="{{ $item->kode_alat }}">{{ $item->nama_alat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Kendala Pemeliharaan</label>
                        <textarea name="deskripsi_kendala" rows="4" class="form-control" placeholder="Tulis rincian kerusakan, kendala listrik, jaringan atau progres perbaikan di sini..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Kirim Laporan Kendala</button>
                </form>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card border-0 shadow-sm p-4">
                <h2 class="h4 text-dark border-bottom pb-2 mb-3">Daftar Status Kendala Alat</h2>
                <div class="table-responsive">
                    <table class="table table-striped align-middle border text-center small">
                        <thead class="table-secondary">
                            <tr>
                                <th class="text-start">Alat & Kendala</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatKendala as $kndl)
                            <tr>
                                <td class="text-start">
                                    <strong class="text-dark">{{ $kndl->nama_alat }}</strong>
                                    <div class="text-muted mt-1">{{ $kndl->deskripsi_kendala }}</div>
                                    <small class="text-black-50 d-block mt-1">Dilaporkan: {{ date('d-m-Y H:i', strtotime($kndl->created_at)) }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $kndl->status == 'Dalam Proses' ? 'bg-warning text-dark' : 'bg-success' }}">
                                        {{ $kndl->status }}
                                    </span>
                                </td>
                                <td>
                                    @if($kndl->status == 'Dalam Proses')
                                        <form action="{{ route('admin.aloptama.kendala.selesai', $kndl->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">Selesai</button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-muted py-3">Belum ada laporan kendala alat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection