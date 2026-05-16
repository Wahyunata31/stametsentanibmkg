@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Beranda</a></li>
                    <li class="breadcrumb-item active">Berita</li>
                </ol>
            </nav>

            <img src="{{ asset('img/uploads/'.$item->image) }}" class="img-fluid w-100 mb-4" style="border-radius: 15px; max-height: 500px; object-fit: cover;">
            
            <p class="text-muted mb-2">{{ \Carbon\Carbon::parse($item->post_date)->format('d F Y') }}</p>
            <h1 class="fw-bold mb-4" style="color: #002d57;">{{ $item->title }}</h1>
            
            <div class="content" style="line-height: 1.8; font-size: 1.1rem; color: #333; text-align: justify;">
                {!! nl2br(e($item->content)) !!}
            </div>

            <hr class="my-5">
            <a href="/" class="btn btn-outline-primary">&larr; Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection