@extends('layouts.main')

@section('title', 'Aloptama')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Pengamatan</a></li>
    <li class="breadcrumb-item" aria-current="page">Aloptama</li>
@endsection

@section('page_title', 'Aloptama')

@section('page_description')
    Informasi Alat Operasi Utama (Aloptama) dan performa perangkat pengamatan di Stasiun Meteorologi Kelas I Sentani - Jayapura.
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="card border-0 shadow-sm p-4 mb-4">
        <h2 class="h4 border-bottom pb-2 mb-3 text-primary">Daftar Alat Operasi Utama (Aloptama)</h2>
        <p>Dalam mendukung keselamatan penerbangan internasional dan publik, Stasiun Meteorologi Kelas I Sentani didukung oleh jajaran perangkat pengamatan cuaca otomatis maupun konvensional modern:</p>
        
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <div class="p-3 bg-light rounded border-start border-3 border-primary">
                    <h5 class="mb-1 fw-bold">Perangkat Radar & Otomasi Utama</h5>
                    <ul class="mb-0 ps-3">
                        <li>Radar Cuaca</li>
                        <li>AWOS (Automated Weather Observing System) Kat III Sentani</li>
                        <li>AWS (Automatic Weather Station) Digitalisasi</li>
                        <li>Display Cuaca Bandara</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded border-start border-3 border-success">
                    <h5 class="mb-1 fw-bold">Jejaring Wilayah (AWOS Kategori I)</h5>
                    <ul class="mb-0 ps-3">
                        <li>AWOS Kat I Oksibil (Kab. Pegunungan Bintang)</li>
                        <li>AWOS Kat I Yahukimo (Kab. Yahukimo)</li>
                        <li>AWOS Kat I Kasonaweja (Kab. Mambramo Raya)</li>
                        <li>AWOS Kat I Senggeh (Kab. Keerom)</li>
                        <li>Peralatan Konvensional</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @php
        // Fungsi pembantu lokal untuk menentukan warna kelas teks berdasarkan ambang nilai
        if (!function_exists('dapatkanWarnaKelas')) {
            function dapatkanWarnaKelas($nilai) {
                if ($nilai >= 100) return 'text-success fw-bold';
                if ($nilai >= 99) return 'text-primary';
                if ($nilai >= 95) return 'text-warning fw-bold';
                return 'text-danger fw-bold';
            }
        }
    @endphp

    <div class="card border-0 shadow-sm p-4 mb-4">
        <h2 class="h4 border-bottom pb-2 mb-3 text-primary">Rata-rata Performa Tahunan Peralatan (Periode 2025)</h2>
        <p class="text-muted small">Data ketersediaan perangkat berdasarkan nilai akumulasi Service Level Agreement (SLA) dan Operational Level Agreement (OLA):</p>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle border text-center">
                <thead class="table-dark">
                    <tr>
                        <th class="text-start" style="width: 40%;">Nama Alat / Perangkat</th>
                        <th style="width: 30%;">Rata-rata SLA (%)</th>
                        <th style="width: 30%;">Rata-rata OLA (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Urutan penampilan baris tabel disesuaikan dengan susunan awal Anda
                        $urutanKunci = [
                            'awos_sentani', 
                            'awos_oksibil', 
                            'awos_kasonaweja', 
                            'awos_keerom', 
                            'aws_digi', 
                            'konvensional', 
                            'display_bandara', 
                            'awos_yahukimo', 
                            'radar_cuaca'
                        ];
                    @endphp

                    @foreach($urutanKunci as $kunci)
                        @if(isset($performa[$kunci]))
                            @php $alat = $performa[$kunci]; @endphp
                            <tr>
                                <td class="text-start fw-bold">{{ $alat->nama_alat }}</td>
                                <td class="{{ dapatkanWarnaKelas($alat->sla) }}">{{ number_format($alat->sla, 2) }}%</td>
                                <td class="{{ dapatkanWarnaKelas($alat->ola) }}">{{ number_format($alat->ola, 2) }}%</td>
                            </tr>
                        @else
                            {{-- Cadangan jika data awal di database belum terisi lengkap oleh controller --}}
                            <tr>
                                <td class="text-start fw-bold text-muted">{{ ucwords(str_replace('_', ' ', $kunci)) }}</td>
                                <td class="text-muted">-</td>
                                <td class="text-muted">-</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4 mb-4">
        <h2 class="h4 border-bottom pb-2 mb-3 text-danger">Laporan Kendala Pemeliharaan Peralatan (Corrective Maintenance)</h2>
        <p>Daftar penurunan performa atau gangguan operasional alat yang saat ini sedang dalam penanganan oleh tim teknisi:</p>
        
        @if($kendala->isEmpty())
            <div class="alert alert-success border-0 shadow-sm my-2">
                <i class="bi bi-check-circle-fill me-2"></i> <strong>Kondisi Baik:</strong> Seluruh sistem peralatan utama dan jejaring wilayah beroperasi normal hari ini.
            </div>
        @else
            <div class="accordion" id="kendalaAloptama">
                @foreach($kendala as $index => $kndl)
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header" id="heading{{ $kndl->id }}">
                        <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }} fw-bold text-dark bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $kndl->id }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $kndl->id }}">
                            <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> Kendala {{ $kndl->nama_alat }}
                        </button>
                    </h2>
                    <div id="collapse{{ $kndl->id }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $kndl->id }}" data-bs-parent="#kendalaAloptama">
                        <div class="accordion-body bg-light text-muted small">
                            <p class="mb-1"><strong>Rincian Kendala Operasional:</strong></p>
                            <p class="mb-2 text-dark">{{ $kndl->deskripsi_kendala }}</p>
                            <span class="badge bg-warning text-dark mb-1">Status Penanganan: {{ $kndl->status }}</span>
                            <div class="text-black-50 small mt-1">Waktu Update Laporan: {{ date('d M Y - H:i', strtotime($kndl->created_at)) }} WIT</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection