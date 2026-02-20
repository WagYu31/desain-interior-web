@extends('layouts.app') {{-- Atau layout utama Anda jika bukan layouts.app --}}

@section('title', 'Verifikasi Email Anda')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow-sm my-5">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ __('Verifikasi Alamat Email Anda') }}</h4>
                </div>

                <div class="card-body p-4">
                    <p class="text-muted mb-4">
                        {{ __('Terima kasih telah mendaftar! Sebelum memulai, dapatkah Anda memverifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan melalui email kepada Anda? Jika Anda tidak menerima email tersebut, kami dengan senang hati akan mengirimkannya lagi.') }}
                    </p>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success mb-4" role="alert">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                {{ __('Kirim Ulang Email Verifikasi') }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none text-muted">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">
                <p class="text-muted">Sudah verifikasi? <a href="{{ route('home') }}">Kembali ke Beranda</a></p>
            </div>
        </div>
    </div>
</div>
@endsection