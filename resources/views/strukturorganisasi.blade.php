@extends('layouts.main')

@section('title', 'Struktur Organisasi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Profil</a></li>
    <li class="breadcrumb-item" aria-current="page">Struktur Organisasi</li>
@endsection

@section('page_title', 'Struktur Organisasi')

@section('page_description')
    Informasi lengkap mengenai sejarah, tugas, fungsi, serta visi dan misi Stasiun Meteorologi Kelas I Sentani - Jayapura dalam melayani masyarakat Papua.
@endsection

@section('content')
<div class="container my-5">
    
    <h2 class="text-center fw-bold mb-5" style="color: #1e3a8a; letter-spacing: 1px;">STASIUN METEOROLOGI KELAS I SENTANI - JAYAPURA</h2>

    <div class="d-flex flex-column align-items-center w-100" style="min-width: 950px; font-family: 'Segoe UI', sans-serif;">
        
        <div class="text-center p-3 text-white shadow-sm" style="background-color: #0f172a; width: 280px; border-radius: 12px; z-index: 10;">
            <h5 class="fw-bold m-0" style="font-size: 1.15rem;">Zem Irianto Padama</h5>
            <small class="text-light opacity-75" style="font-size: 0.85rem;">Kepala Stasiun</small>
        </div>

        <div class="d-flex justify-content-center align-items-center position-relative" style="width: 100%; height: 90px; margin-top: -1px;">
            
            <div style="position: absolute; left: 50%; top: 0; width: 2px; height: 100%; background-color: #64748b; transform: translateX(-50%); z-index: 1;"></div>
            
            <div style="position: absolute; left: 50%; top: 50%; width: 60px; height: 2px; background-color: #64748b; z-index: 1;"></div>
            
            <div style="position: absolute; left: calc(50% + 60px); top: 50%; transform: translateY(-50%); z-index: 10;">
                <div class="p-3 shadow-sm" style="background-color: #fef08a; border: 2px solid #eab308; width: 260px; border-radius: 12px;">
                    <h6 class="fw-bold m-0 text-dark" style="font-size: 1rem;">Soni Kurniawan</h6>
                    <small class="text-muted" style="font-size: 0.8rem; display: block; margin-top: 2px;">Kepala Sub Bagian Tata Usaha</small>
                </div>
            </div>
        </div>

        <div class="position-relative" style="width: 66.66%; max-width: 600px; height: 35px; margin-top: -1px;">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 2px; background-color: #64748b;"></div>
            <div style="position: absolute; top: 0; left: 0; width: 2px; height: 100%; background-color: #64748b;"></div>
            <div style="position: absolute; top: 0; right: 0; width: 2px; height: 100%; background-color: #64748b;"></div>
            <div style="position: absolute; top: 0; left: 50%; width: 2px; height: 100%; background-color: #64748b; transform: translateX(-50%);"></div>
        </div>

        <div class="row w-100 text-center justify-content-between" style="max-width: 900px; margin-top: 5px;">
            
            <div class="col-4 d-flex flex-column align-items-center gap-3 px-2">
                <div class="p-3 bg-white border border-secondary shadow-sm w-100 d-flex flex-column justify-content-center align-items-center" style="border-radius: 14px; min-height: 105px;">
                    <h6 class="fw-bold m-0 text-dark" style="font-size: 0.95rem; line-height: 1.3;">Ardi Widi Lestanto</h6>
                    <small class="text-muted d-block mt-2" style="font-size: 0.75rem; line-height: 1.3;">Ketua Tim Layanan Data Informasi dan Pelayanan Jasa</small>
                </div>
                <div class="p-2 bg-light border w-100 text-muted d-flex align-items-center justify-content-center" style="border-radius: 14px; font-size: 0.75rem; min-height: 55px; line-height: 1.3;">
                    Kelompok Fungsional Layanan Data Informasi dan Pelayanan Jasa
                </div>
            </div>

            <div class="col-4 d-flex flex-column align-items-center gap-3 px-2">
                <div class="p-3 bg-white border border-secondary shadow-sm w-100 d-flex flex-column justify-content-center align-items-center" style="border-radius: 14px; min-height: 105px;">
                    <h6 class="fw-bold m-0 text-dark" style="font-size: 0.95rem; line-height: 1.3;">Eronika Harianja</h6>
                    <small class="text-muted d-block mt-2" style="font-size: 0.75rem; line-height: 1.3;">Ketua Tim Observasi</small>
                </div>
                <div class="p-2 bg-light border w-100 text-muted d-flex align-items-center justify-content-center" style="border-radius: 14px; font-size: 0.75rem; min-height: 55px; line-height: 1.3;">
                    Kelompok Fungsional Observasi Permukaan dan Udara Atas
                </div>
            </div>

            <div class="col-4 d-flex flex-column align-items-center gap-3 px-2">
                <div class="p-3 bg-white border border-secondary shadow-sm w-100 d-flex flex-column justify-content-center align-items-center" style="border-radius: 14px; min-height: 105px;">
                    <h6 class="fw-bold m-0 text-dark" style="font-size: 0.95rem; line-height: 1.3;">Sahat S. Hutapea</h6>
                    <small class="text-muted d-block mt-2" style="font-size: 0.75rem; line-height: 1.3;">Ketua Tim Teknisi</small>
                </div>
                <div class="p-2 bg-light border w-100 text-muted d-flex align-items-center justify-content-center" style="border-radius: 14px; font-size: 0.75rem; min-height: 55px; line-height: 1.3;">
                    Kelompok Fungsional Teknisi
                </div>
            </div>

        </div>

    </div>
</div>
@endsection