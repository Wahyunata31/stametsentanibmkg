@extends('layouts.main')

@section('title', 'Buletin Bulanan')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Buletin</li>
@endsection

@section('page_title', 'Buletin Bulanan')

@section('page_description')
    Buletin Resmi Stasiun Meteorologi Kelas I Sentani Jayapura.
@endsection

@section('content')
<div class="container py-4">
    @if($bulletins->isEmpty())
        <div class="alert alert-info text-center">
            Belum ada buletin yang diterbitkan saat ini.
        </div>
    @else
        <div class="row">
            @foreach($bulletins as $bulletin)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 overflow-hidden">
                        
                        <div class="position-relative bg-light text-center" style="height: 250px; overflow: hidden;">
                            @if($bulletin->cover_image)
                                <img src="{{ asset('bulletins/covers/' . $bulletin->cover_image) }}" 
                                     alt="Sampul {{ $bulletin->title }}" 
                                     class="w-100 h-100" 
                                     style="object-fit: cover; object-position: top;">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 4rem;"></i>
                                </div>
                            @endif
                        </div>

                        <div class="card-body p-3">
                            <h5 class="card-title fw-bold text-dark text-truncate mb-1" title="{{ $bulletin->title }}">
                                {{ $bulletin->title }}
                            </h5>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-calendar3"></i> Terbit: {{ \Carbon\Carbon::parse($bulletin->publish_date)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pb-3 pt-0">
                            <a href="{{ asset('bulletins/' . $bulletin->pdf_file) }}" target="_blank" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-eye"></i> Baca / Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection