@extends('layouts.main')

@section('title', 'cuaca')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Prakiraan</a></li>
    <li class="breadcrumb-item" aria-current="page">Cuaca</li>
@endsection

@section('page_title', 'Cuaca')

@section('page_description')
    Prakiraan cuaca harian wilayah sentani dan sekitarnya.
@endsection

@section('content')
    @php
        $data = \App\Models\News::where('title', 'DATA_CUACA_KOTA')->first();
    @endphp

    <div class="container text-center">
        <h2>Prakiraan Cuaca Kota</h2>
        @if($data)
            <div class="alert alert-info">{{ $data->content }}</div>
        @else
            <p>Data cuaca kota belum diisi.</p>
        @endif
    </div>
@endsection