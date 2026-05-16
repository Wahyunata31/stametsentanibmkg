@extends('layouts.main')

@section('title', 'Pelayanan Data')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Pelayanan Data</a></li>
@endsection

@section('page_title', 'Pelayanan Data')

@section('page_description')
    Permintaan data cuaca resmi untuk pendidikan dan instansi.
@endsection

@section('content')
<div class="row g-4 mt-2">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-4" style="color: #4b7ea9;">Jenis Layanan Data</h4>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Jenis Data / Jasa</th>
                                <th>Satuan</th>
                                <th>Tarif (PNBP)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Informasi Meteorologi untuk Penerbangan</td>
                                <td>Per Dokumen</td>
                                <td class="fw-semibold">Rp 0,- (Sesuai PP)</td>
                            </tr>
                            <tr>
                                <td>Data Meteorologi (Khusus Penelitian)</td>
                                <td>Per Stasiun</td>
                                <td class="text-success fw-bold">Gratis*</td>
                            </tr>
                            <tr>
                                <td>Data Parameter Meteorologi (Umum/Bisnis)</td>
                                <td>Per Parameter</td>
                                <td>Sesuai PP No. 47 Thn 2018</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-muted small mt-2">* Syarat dan ketentuan berlaku bagi mahasiswa/peneliti dengan melampirkan surat pengantar resmi.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3" style="color: #4b7ea9;">Alur Permohonan Data</h4>
                <div class="list-group list-group-flush">
                    <div class="list-group-item border-0 d-flex align-items-start px-0">
                        <div class="bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; flex-shrink: 0;">1</div>
                        <div>Pemohon mengajukan surat permintaan data atau datang langsung ke kantor.</div>
                    </div>
                    <div class="list-group-item border-0 d-flex align-items-start px-0">
                        <div class="bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; flex-shrink: 0;">2</div>
                        <div>Petugas memverifikasi ketersediaan data dan menghitung biaya PNBP (jika ada).</div>
                    </div>
                    <div class="list-group-item border-0 d-flex align-items-start px-0">
                        <div class="bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; flex-shrink: 0;">3</div>
                        <div>Pemohon melakukan pembayaran melalui sistem SIMPONI (E-Billing).</div>
                    </div>
                    <div class="list-group-item border-0 d-flex align-items-start px-0">
                        <div class="bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; flex-shrink: 0;">4</div>
                        <div>Data diserahkan kepada pemohon secara digital atau fisik.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 bg-dark text-white shadow-sm mb-4">
            <div class="card-body p-4 text-center">
                <h5 class="fw-bold">Butuh Data Cepat?</h5>
                <p class="small">Gunakan aplikasi PTSP BMKG untuk permintaan data secara online.</p>
                <a href="https://ptsp.bmkg.go.id" target="_blank" class="btn btn-outline-light btn-sm px-4">Kunjungi PTSP</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Kontak Layanan</h5>
                <p class="mb-1 text-muted small"><i class="bi bi-envelope-fill me-2"></i>Email:</p>
                <p class="fw-bold mb-3">stamet.sentani@bmkg.go.id</p>
                
                <p class="mb-1 text-muted small"><i class="bi bi-whatsapp me-2"></i>WhatsApp:</p>
                <p class="fw-bold mb-3">+62 811-XXXX-XXXX</p>

                <p class="mb-1 text-muted small"><i class="bi bi-clock-fill me-2"></i>Jam Operasional:</p>
                <p class="fw-bold mb-0">Senin - Jumat</p>
                <p class="fw-bold">08.00 - 16.00 WIT</p>
            </div>
        </div>
    </div>
</div>

@endsection