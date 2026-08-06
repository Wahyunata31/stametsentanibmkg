@extends('layouts.main')

@section('title', 'Sejarah')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Profil</a></li>
    <li class="breadcrumb-item" aria-current="page">Sejarah</li>
@endsection

@section('page_title', 'Sejarah')

@section('page_description')
    Informasi lengkap mengenai sejarah, tugas, fungsi, serta visi dan misi Stasiun Meteorologi Kelas I Sentani - Jayapura dalam melayani masyarakat Papua.
@endsection

@section('content')
<div class="card border-0 shadow-sm p-4 mb-4">
    <h2 class="h4 border-bottom pb-2 mb-3">Sejarah Singkat Sta. Met. Kelas I Sentani</h2>
    <p>Kegiatan pengamatan cuaca di wilayah Sentani memiliki rekam jejak yang panjang:</p>
    <ol class="list-group list-group-numbered list-group-flush mb-3">
        <li class="list-group-item bg-transparent"><strong>Masa Kolonial:</strong> Pos pengamatan meteorologi Sentani pertama kali dilaksanakan sejak masa kolonial Hindia Belanda untuk mendukung keselamatan penerbangan dan pemantauan iklim lokal.</li>
        <li class="list-group-item bg-transparent"><strong>1 Mei 1963:</strong> Secara resmi berubah nama menjadi <strong>Stasiun Meteorologi Sentani</strong>, yang mengawali kiprah operasionalnya di bawah naungan pemerintah Indonesia secara penuh.</li>
        <li class="list-group-item bg-transparent"><strong>29 Mei 2013:</strong> Seiring dengan meningkatnya volume penerbangan dan kebutuhan data meteorologi yang krusial di wilayah Papua, stasiun ini resmi ditingkatkan statusnya dari stasiun Kelas III hingga menjadi <strong>Stasiun Meteorologi Kelas I Sentani</strong> hingga saat ini.</li>
    </ol>
</div>

@endsection