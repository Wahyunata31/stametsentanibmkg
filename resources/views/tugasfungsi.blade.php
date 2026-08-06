@extends('layouts.main')

@section('title', 'Tugas dan Fungsi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Profil</a></li>
    <li class="breadcrumb-item" aria-current="page">Tugas dan Fungsi</li>
@endsection

@section('page_title', 'Tugas dan Fungsi')

@section('page_description')
    Informasi lengkap mengenai sejarah, tugas, fungsi, serta visi dan misi Stasiun Meteorologi Kelas I Sentani - Jayapura dalam melayani masyarakat Papua.
@endsection

@section('content')
<div class="card border-0 shadow-sm p-4 mb-4">
    <p class="text-muted">Berdasarkan Peraturan Kepala (Perka) BMKG Nomor 8 Tahun 2019</p>
    
    <h2 class="h4 border-bottom pb-2 mb-3 text-primary">Tugas</h2>
    <p>Stasiun Meteorologi Kelas I Sentani mempunyai tugas melaksanakan pengamatan, pengumpulan, penyebaran, pengolahan, analisis, serta pelayanan informasi meteorologi penerbangan, meteorologi publik, dan jasa meteorologi lainnya di wilayah kerja yang ditentukan.</p>
    
    <h2 class="h4 border-bottom pb-2 mb-3 mt-4 text-primary">Fungsi</h2>
    <ul>
        <li class="mb-2">Melakukan pengamatan meteorologi permukaan secara berkala dan kontinu (real-time).</li>
        <li class="mb-2">Melakukan pengumpulan, pengolahan, pencatatan, dan pengarsipan data meteorologi.</li>
        <li class="mb-2">Menyediakan pelayanan informasi cuaca penerbangan untuk menunjang keselamatan operasional di Bandara Sentani dan sekitarnya.</li>
        <li class="mb-2">Melakukan diseminasi atau penyebaran peringatan dini cuaca ekstrem kepada stakeholder dan masyarakat umum.</li>
        <li class="mb-2">Melakukan pemeliharaan dan perbaikan peralatan operasional meteorologi (Aloptama).</li>
    </ul>
</div>

@endsection