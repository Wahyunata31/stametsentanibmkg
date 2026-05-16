@extends('layouts.main')

@section('title', 'Gempa Bumi Dirasakan')

@section('page_title', 'Gempa Bumi Dirasakan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Gempa Bumi</a></li>
    <li class="breadcrumb-item" aria-current="page">Gempa Bumi Dirasakan</li>
@endsection

@section('page_title', 'Gempa Bumi Dirasakan')

@section('page_description')
    Informasi gempa bumi dirasakan di wilayah Indonesia.
@endsection

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Daftar 15 Gempabumi Dirasakan</h2>
    <div class="table-responsive shadow-sm border rounded">
        <table class="table table-hover mb-0">
            <thead class="table-info text-dark">
                <tr>
                    <th>No</th><th>Waktu</th><th>Magnitudo</th><th>Kedalaman</th><th>Wilayah</th><th>Dirasakan (Skala MMI)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->gempa as $index => $gd)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $gd->Tanggal }} {{ $gd->Jam }}</td> 
                    <td>{{ $gd->Magnitude }} SR</td> 
                    <td>{{ $gd->Kedalaman }}</td> 
                    <td>{{ $gd->Wilayah }}</td>
                    <td><span class="text-primary">{{ $gd->Dirasakan }}</span></td> 
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p class="mt-3 text-muted small">* Sumber: BMKG</p>
</div>
@endsection