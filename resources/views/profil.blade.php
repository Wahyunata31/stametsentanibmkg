@extends('layouts.main')

@section('title', 'Profil Stasiun')

@section('page_title', 'Profil Stasiun')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Profil</a></li>
    <li class="breadcrumb-item" aria-current="page">Profil Stasiun</li>
@endsection

@section('page_title', 'Profil')

@section('page_description')
    Informasi lengkap mengenai sejarah, tugas, fungsi, serta visi dan misi Stasiun Meteorologi Kelas I Sentani - Jayapura dalam melayani masyarakat Papua.
@endsection

@section('content')
    <div class="card border-0 shadow-sm p-4 mb-4">
        <h2 class="h4 border-bottom pb-2 mb-3">Tentang Stasiun</h2>
        <p><strong>Stasiun Meteorologi Kelas I Sentani - Jayapura</strong> merupakan salah satu unit pelaksana teknis Badan Meteorologi, Klimatologi, dan Geofisika (BMKG) yang berada di wilayah timur Indonesia, tepatnya di Kabupaten Jayapura, Papua.</p>
    
        <h2 class="h4 border-bottom pb-2 mb-3 mt-4">Lokasi Stasiun</h2>
        <ul>
            <li><strong>Koordinat:</strong> 140.52 BT, -2.56 LS</li>
            <li><strong>Ketinggian:</strong> ± 88 mdpl</li>
            <li><strong>Karakteristik Topografi:</strong> Berada di antara lereng terjal Pegunungan Cyclops di sebelah utara dan hamparan Danau Sentani di sisi timur serta selatan. Koridor topografi unik ini menciptakan kondisi cuaca lokal yang sangat dinamis dan menantang.</li>
        </ul>

        <h2 class="h4 border-bottom pb-2 mb-3 mt-4">Akses Kantor</h2>
        <p>Akses masuk kantor Stasiun Meteorologi Kelas I Sentani dimulai dari pintu masuk Baseops TNI-AU Silas Papare, kemudian mengikuti jalan aspal yang terdapat pada pinggir Apron hingga ke depan Mess Satgas Kopasgat, di mana kantor Stasiun Meteorologi Kelas I Sentani berada.</p>
    </div>

@endsection