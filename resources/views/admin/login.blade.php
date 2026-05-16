@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white text-center">
                    <h5 class="mb-0">Login Admin</h5>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('admin.login.submit') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Kode Captcha</label>
                            <div class="mb-2">{!! captcha_img() !!}</div>
                            <input type="text" name="captcha" class="form-control" placeholder="Masukkan kode di atas" required>
                            @error('captcha') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">Masuk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection