@extends('layouts.main')
@section('title', 'Daftar Gempa M 5.0+')

@section('page_title', 'Daftar Gempa M 5.0+')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Gempa Bumi</a></li>
    <li class="breadcrumb-item" aria-current="page">Gempa Bumi M 5.0+</li>
@endsection

@section('page_title', 'Daftar Gempa M 5.0+')

@section('page_description')
    Informasi gempa bumi M 5.0+ di wilayah Indonesia.
@endsection


@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Daftar 15 Gempabumi M 5.0+</h2>
    <div class="table-responsive shadow-sm border rounded">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>No</th><th>Waktu</th><th>Magnitudo</th><th>Kedalaman</th><th>Lokasi</th><th>Potensi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->gempa as $index => $g)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $g->Tanggal }} {{ $g->Jam }}</td> 
                    <td><span class="badge bg-warning text-dark">{{ $g->Magnitude }}</span></td> 
                    <td>{{ $g->Kedalaman }}</td> 
                    <td>{{ $g->Wilayah }}</td>
                    <td>{{ $g->Potensi }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p class="mt-3 text-muted small">* Sumber: BMKG</p>
</div>
@endsection