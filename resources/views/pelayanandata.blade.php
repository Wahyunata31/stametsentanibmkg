@extends('layouts.main')

@section('title', 'Pelayanan Data & Informasi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Pelayanan Data</a></li>
@endsection

@section('page_title', 'Pelayanan Data & Informasi Meteorologi')

@section('page_description')
    Standar Pelayanan Informasi Meteorologi Publik, Informasi Khusus, dan Jasa Meteorologi Stasiun Meteorologi Kelas I Sentani.
@endsection

@section('content')
<div class="row g-4 mt-2">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3" style="color: #4b7ea9;">Jenis Layanan & Tarif (PNBP)</h4>
                <p class="text-muted small mb-4">Berdasarkan Peraturan Pemerintah No. 47 Tahun 2018 dan Peraturan BMKG No. 12 Tahun 2019.</p>

                <h5 class="fw-bold fs-6 text-dark border-bottom pb-2 mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i>1. Pelayanan Informasi Publik (Rutin & Peringatan Dini)</h5>
                <div class="table-responsive mb-4">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Jenis Layanan</th>
                                <th>Satuan / Media</th>
                                <th>Tarif</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Informasi Prakiraan Cuaca Rutin</td>
                                <td >Akses Web <a href="https://cuaca.bmkg.go.id">Cuaca</a></td>
                                <td><span class="badge bg-success">Rp 0,- (Gratis)</span></td>
                            </tr>
                            <tr>
                                <td>Peringatan Dini Cuaca Ekstrem</td>
                                <td>Akses Web <a href="https://nowcasting.bmkg.go.id">Peringatan dini</a></td>
                                <td><span class="badge bg-success">Rp 0,- (Gratis)</span></td>
                            </tr>
                            <tr>
                                <td>Peringatan Dini Cuaca Penerbangan</td>
                                <td>Akses Web <a href="https://inasiam.bmkg.go.id">Aviation</a></td>
                                <td><span class="badge bg-success">Rp 0,- (Gratis)</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h5 class="fw-bold fs-6 text-dark border-bottom pb-2 mb-3"><i class="bi bi-file-earmark-text-fill text-primary me-2"></i>2. Pelayanan Informasi Khusus & Jasa</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Jenis Layanan / Jasa</th>
                                <th>Satuan</th>
                                <th>Tarif (PNBP)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Informasi Cuaca untuk Penerbangan</td>
                                <td>Per Dokumen</td>
                                <td>4% Biaya Jasa Navigasi</td>
                            </tr>
                            <tr>
                                <td>Informasi Cuaca untuk Klaim Asuransi</td>
                                <td>Per Lokasi / Hari</td>
                                <td class="fw-semibold">Rp 175.000,-</td>
                            </tr>
                            <tr>
                                <td>Informasi Cuaca Khusus Kegiatan Olahraga</td>
                                <td>Per Lokasi / Hari</td>
                                <td class="fw-semibold">Rp 100.000,-</td>
                            </tr>
                            <tr>
                                <td>Informasi Cuaca Kegiatan Komersil (Outdoor/Indoor)</td>
                                <td>Per Lokasi / Hari</td>
                                <td class="fw-semibold">Rp 100.000,-</td>
                            </tr>
                            <tr>
                                <td>Informasi Radar Cuaca (Per 10 Menit)</td>
                                <td>Per Data / Lokasi</td>
                                <td class="fw-semibold">Rp 70.000,-</td>
                            </tr>
                            <tr>
                                <td>Informasi Khusus Pendukung Proyek, Survei & Penelitian Komersil</td>
                                <td>Per Lokasi</td>
                                <td class="fw-semibold">Rp 3.750.000,-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-info border-0 small mt-3 mb-0">
                    <i class="bi bi-info-circle me-1"></i> <strong>Ketentuan Tarif Rp 0,00 (Nol Rupiah):</strong> Diberlakukan untuk kegiatan sosial, keagamaan, penanggulangan bencana, serta pendidikan/penelitian non-komersil sesuai <strong>Peraturan BMKG No. 12 Tahun 2019</strong> dengan melampirkan surat pengantar/permohonan resmi.
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3" style="color: #4b7ea9;">Alur Permohonan & Standar Prosedur</h4>
                <div class="list-group list-group-flush">
                    <div class="list-group-item border-0 d-flex align-items-start px-0">
                        <div class="bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; flex-shrink: 0;">1</div>
                        <div>
                            <strong>Pengajuan Permohonan & Persyaratan</strong><br>
                            <span class="small text-muted">Pemohon mendatangi PTSP Stamet Sentani atau menghubungi layanan online dengan melampirkan Scan KTP/Paspor dan Surat Permohonan.</span>
                        </div>
                    </div>
                    <div class="list-group-item border-0 d-flex align-items-start px-0">
                        <div class="bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; flex-shrink: 0;">2</div>
                        <div>
                            <strong>Verifikasi & Pemilihan Layanan</strong><br>
                            <span class="small text-muted">Petugas melakukan verifikasi berkas permohonan dan ketersediaan data sesuai Katalog Pelayanan BMKG.</span>
                        </div>
                    </div>
                    <div class="list-group-item border-0 d-flex align-items-start px-0">
                        <div class="bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; flex-shrink: 0;">3</div>
                        <div>
                            <strong>Pembayaran Billing SIMPONI (Jika Berbiaya)</strong><br>
                            <span class="small text-muted">Pemohon mencetak kode billing PNBP dan melakukan pembayaran melalui sistem SIMPONI E-Billing (kecuali layanan Tarif Rp 0,-).</span>
                        </div>
                    </div>
                    <div class="list-group-item border-0 d-flex align-items-start px-0">
                        <div class="bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; flex-shrink: 0;">4</div>
                        <div>
                            <strong>Review Layanan & Penyerahan Hasil</strong><br>
                            <span class="small text-muted">Pemohon mengisi Ulasan/Review Layanan dan mengunduh/menerima dokumen hasil analisa data meteorologi resmi.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 bg-dark text-white shadow-sm mb-4">
            <div class="card-body p-4 text-center">
                <h5 class="fw-bold">Layanan Online (PTSP BMKG)</h5>
                <p class="small text-light opacity-75">Ajukan permintaan data dan informasi meteorologi secara online dari mana saja.</p>
                <a href="https://ptsp.bmkg.go.id" target="_blank" class="btn btn-outline-light btn-sm px-4 rounded-pill">Kunjungi ptsp.bmkg.go.id</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Kontak & Operasional</h5>
                
                <p class="mb-1 text-muted small"><i class="bi bi-geo-alt-fill me-2 text-primary"></i>Alamat Kantor:</p>
                <p class="fw-semibold small mb-3">Jl. Yabaso, Komplek Bandar Udara Sentani - Jayapura</p>

                <p class="mb-1 text-muted small"><i class="bi bi-telephone-fill me-2 text-primary"></i>Telepon / WhatsApp:</p>
                <p class="fw-bold mb-3">(0967) 5191700 / +62 811-4819-166</p>

                <p class="mb-1 text-muted small"><i class="bi bi-envelope-fill me-2 text-primary"></i>Email Resmi:</p>
                <p class="fw-bold mb-3">stamet.sentani@bmkg.go.id</p>

                <p class="mb-1 text-muted small"><i class="bi bi-clock-fill me-2 text-primary"></i>Jam Pelayanan Front Office:</p>
                <p class="fw-bold mb-0">Senin - Jumat</p>
                <p class="fw-bold">08.00 - 16.00 WIT</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Kanal Pengaduan & Saran</h5>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2"><i class="bi bi-globe me-2 text-primary"></i><strong>SP4N LAPOR:</strong> <a href="https://www.lapor.go.id" target="_blank" class="text-decoration-none">lapor.go.id</a></li>
                    <li class="mb-2"><i class="bi bi-headset me-2 text-primary"></i><strong>Call Center:</strong> 196</li>
                    <li class="mb-2"><i class="bi bi-whatsapp me-2 text-primary"></i><strong>WhatsApp:</strong> 08114819166</li>
                    <li class="mb-0"><i class="bi bi-box-seam me-2 text-primary"></i>Kotak Saran Front Office Stamet Sentani</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection