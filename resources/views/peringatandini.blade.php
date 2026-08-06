@extends('layouts.main')

@section('title', 'Peringatan Dini')

@section('page_title', 'Peringatan Dini')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Prakiraan</a></li>
    <li class="breadcrumb-item" aria-current="page">Peringatan Dini</li>
@endsection

@section('page_description')
    Peringatan dini hujan sedang hingga lebat yang dapat disertai petir dan angin kencang di wilayah Papua.
@endsection

@push('styles')
<style>
    #image-container { 
        width: 100%; 
        border-radius: 8px; 
        border: 1px solid #ddd; 
        min-height: 500px; 
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .alert-item { cursor: pointer; transition: 0.2s; border-left: 4px solid transparent; }
    .alert-item:hover { background-color: #f0f7ff; border-left: 4px solid #007bff; }
    .scroll-box { max-height: 500px; overflow-y: auto; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white fw-bold">
                Daftar Peringatan Aktif (BMKG)
            </div>
            <div class="card-body p-0 scroll-box">
                <div class="list-group list-group-flush" id="alert-list">
                    @forelse($alerts as $index => $alert)
                        <div class="list-group-item alert-item alert-link-item" 
                             data-link="{{ $alert['link'] }}"
                             onclick="loadImage('{{ $alert['link'] }}')">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 fw-bold text-primary">{{ $alert['title'] }}</h6>
                            </div>
                            <small class="text-muted d-block mb-1"><i class="bi bi-clock"></i> {{ $alert['pubDate'] }}</small>
                            <p class="mb-1 small text-truncate">{{ \Illuminate\Support\Str::limit($alert['description'], 100) }}</p>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted" id="empty-alert">
                            <i class="bi bi-info-circle d-block mb-2"></i>
                            Tidak ada peringatan dini aktif saat ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div id="image-container" class="shadow-sm bg-white p-2 text-center position-relative">
            <div id="loading-spinner" class="d-none text-muted">
                <div class="spinner-border text-primary mb-2" role="status"></div>
                <span class="d-block">Memuat infografis...</span>
            </div>

            <div id="placeholder-text" class="text-muted p-4">
                <i class="bi bi-image d-block mb-2" style="font-size: 3rem;"></i>
                Tidak ada data infografis yang tersedia.
            </div>

            <img id="alert-image" src="" alt="Infografis Peringatan Dini" class="img-fluid rounded d-none" style="max-height: 550px;">
        </div>

        <div id="info-detail" class="mt-3 card card-body border-warning bg-light d-none shadow-sm">
            <h5 id="detail-headline" class="text-danger fw-bold"></h5>
            <p id="detail-area" class="mb-0"></p>
            <hr>
            <small class="text-muted text-end">Sumber Data: <strong>BMKG</strong></small>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil item peringatan pertama jika ada
        const firstAlertItem = document.querySelector('.alert-link-item');
        
        if (firstAlertItem) {
            const firstUrl = firstAlertItem.getAttribute('data-link');
            // Jalankan pengisian gambar pertama kali secara otomatis
            loadImage(firstUrl);
        } else {
            // Jika benar-benar tidak ada alert sama sekali
            document.getElementById('placeholder-text').classList.remove('d-none');
        }
    });

    function loadImage(capUrl) {
        const infoBox = document.getElementById('info-detail');
        const placeholderText = document.getElementById('placeholder-text');
        const alertImage = document.getElementById('alert-image');
        const spinner = document.getElementById('loading-spinner');

        // Tampilkan loading spinner, sembunyikan gambar lama & placeholder
        spinner.classList.remove('d-none');
        alertImage.classList.add('d-none');
        placeholderText.classList.add('d-none');
        infoBox.classList.add('d-none');

        fetch(`/peringatan-dini/detail?url=${encodeURIComponent(capUrl)}`)
            .then(res => res.json())
            .then(data => {
                spinner.classList.add('d-none');

                if (data.image_url) {
                    alertImage.classList.remove('d-none');
                    alertImage.src = data.image_url;
                } else {
                    placeholderText.classList.remove('d-none');
                    placeholderText.innerHTML = `
                        <i class="bi bi-exclamation-triangle text-warning d-block mb-2" style="font-size: 3rem;"></i>
                        Gambar infografis tidak ditemukan untuk peringatan ini.
                    `;
                }

                // Tampilkan detail teks pendukung di bawah infografis
                infoBox.classList.remove('d-none');
                document.getElementById('detail-headline').innerText = data.headline;
                document.getElementById('detail-area').innerHTML = `
                    <strong>Wilayah:</strong> ${data.area}<br>
                    <p class="mt-2 small text-dark">${data.description}</p>
                `;
            })
            .catch(err => {
                console.error("Error loading CAP Image:", err);
                spinner.classList.add('d-none');
                placeholderText.classList.remove('d-none');
                placeholderText.innerHTML = `
                    <i class="bi bi-x-circle text-danger d-block mb-2" style="font-size: 3rem;"></i>
                    Gagal memuat data dari server BMKG.
                `;
            });
    }
</script>
@endpush