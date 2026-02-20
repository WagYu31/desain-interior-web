@extends('layouts.app') {{-- Atau layout utama Anda jika bukan layouts.app --}}

@section('title', 'Lupa Password')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm my-5">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ __('Lupa Password Anda?') }}</h4>
                </div>

                <div class="card-body p-4">
                    <p class="text-muted mb-4">
                        {{ __('Tidak masalah. Cukup beritahu kami alamat email Anda dan kami akan mengirimkan email berisi tautan untuk mengatur ulang kata sandi yang akan memungkinkan Anda memilih yang baru.') }}
                    </p>

                    <!-- Session Status (Pesan Sukses setelah mengirim link) -->
                    @if (session('status'))
                        <div class="alert alert-success mb-4" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Alamat Email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Kirim Link Reset Password') }}
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-decoration-none">Kembali ke Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection