@extends('layouts.main')

@section('title', 'Visi dan Misi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Profil</a></li>
    <li class="breadcrumb-item" aria-current="page">Visi dan Misi</li>
@endsection

@section('page_title', 'Visi dan Misi')

@section('page_description')
    Informasi lengkap mengenai sejarah, tugas, fungsi, serta visi dan misi Stasiun Meteorologi Kelas I Sentani - Jayapura dalam melayani masyarakat Papua.
@endsection

@section('content')
<div class="card border-0 shadow-sm p-4 mb-4">
    <div class="mb-4 bg-light p-4 rounded border-start border-primary border-4">
        <h2 class="h4 text-primary mb-3">VISI</h2>
        <p class="fs-5 italic text-dark mb-0">
            "Mewujudkan BMKG yang berkelas dunia dengan spirit socio-entrepreneur untuk mewujudkan Indonesia Maju yang Berdaulat, Mandiri, dan berkepribadian berlandaskan Gotong-Royong."
        </p>
    </div>

    <div>
        <h2 class="h4 text-primary mb-3">MISI</h2>
        <ul class="list-group list-group-flush">
            <li class="list-group-item bg-transparent ps-0"><i class="bi bi-check-circle-fill text-success me-2"></i> Menjadikan informasi BMKG sebagai rujukan masyarakat internasional dan mewujudkan Regional Modelling Centre.</li>
            <li class="list-group-item bg-transparent ps-0"><i class="bi bi-check-circle-fill text-success me-2"></i> Mendorong SDM BMKG berperan aktif dalam organisasi MKG internasional.</li>
            <li class="list-group-item bg-transparent ps-0"><i class="bi bi-check-circle-fill text-success me-2"></i> Mewujudkan SDM BMKG berperan aktif dalam organisasi MKG Internasional.</li>
            <li class="list-group-item bg-transparent ps-0"><i class="bi bi-check-circle-fill text-success me-2"></i> Mewujudkan sebagian unit layanan jasa dan informasi BMKG menjadi unit Badan Layanan Umum (BLU).</li>
        </ul>
    </div>
</div>

@endsection