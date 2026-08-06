@extends('layouts.main')

@section('title', 'Login Maskapai - Flight Document')
@section('page_title', 'Flight Document Login')
@section('page_description', 'Silakan pilih maskapai Anda dan masukkan password resmi untuk mengakses lembar dokumen penerbangan.')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Penerbangan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Flight Document</li>
@endsection

@section('content')
<div class="row justify-content-center my-4">
    <div class="col-md-5">
        <div class="card shadow border-0" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-airplane-fill text-primary" style="font-size: 3rem;"></i>
                    <h4 class="fw-bold mt-2">Flight Doc Station</h4>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('flightdoc.login.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="airline" class="form-label fw-bold">Pilih Maskapai</label>
                        <select name="airline" id="airline" class="form-select" required>
                            <option value="" selected disabled>-- Pilih Maskapai Anda --</option>
                            @foreach($airlines as $airline)
                                <option value="{{ $airline }}">{{ $airline }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold">Password Penerbangan</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password khusus" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm rounded-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Ruang Unduh
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection