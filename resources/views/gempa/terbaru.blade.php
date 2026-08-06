@extends('layouts.main')
@section('title', 'Gempa Bumi Terbaru')

@section('page_title', 'Gempa Bumi Terbaru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Gempa Bumi</a></li>
    <li class="breadcrumb-item" aria-current="page">Gempa Bumi Terbaru</li>
@endsection

@section('page_description')
    Informasi gempa bumi terbaru di wilayah Indonesia.
@endsection

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Gempabumi Terbaru</h2>
    <div class="card shadow-sm">
        @if(isset($data->gempa))
            <div class="row g-0">
                <div class="col-md-5 text-center p-3">
                    <img src="https://data.bmkg.go.id/DataMKG/TEWS/{{ $data->gempa->Shakemap }}" class="img-fluid rounded" alt="Shakemap" onerror="this.src='https://via.placeholder.com/400x400?text=Peta+Tidak+Tersedia';"> 
                </div>
                <div class="col-md-7">
                    <div class="card-body">
                        <table class="table">
                            <tr><th>Tanggal / Jam</th><td>{{ $data->gempa->Tanggal }} / {{ $data->gempa->Jam }}</td></tr>
                            <tr><th>Magnitudo</th><td><span class="badge bg-danger">{{ $data->gempa->Magnitude }} SR</span></td></tr>
                            <tr><th>Kedalaman</th><td>{{ $data->gempa->Kedalaman }}</td></tr> 
                            <tr><th>Lokasi</th><td>{{ $data->gempa->Wilayah }}</td></tr> 
                            <tr><th>Potensi</th><td><strong>{{ $data->gempa->Potensi }}</strong></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-muted mb-0">Data gempa bumi terbaru saat ini sedang tidak tersedia.</p>
            </div>
        @endif
    </div>
    <p class="mt-3 text-muted small">* Sumber: BMKG</p>
</div>
@endsection