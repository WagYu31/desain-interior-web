@extends('layouts.auth')

@section('title', 'Login')

@section('content')

    <div class="text-center mb-4">
        <a href="{{ route('home') }}">
            <img src="{{ Vite::asset('resources/images/logopt.png') }}" alt="Logo" style="max-height: 50px;">
        </a>
        <h4 class="fw-bold mt-4">Login ke Akun Anda</h4>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger p-2 small">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" class="form-control form-control-lg" name="email"
                value="{{ old('email') }}" required autofocus placeholder="jhondoe@email.com">
        </div>
        <div class="mb-3">
            <div class="d-flex justify-content-between text-primary">
                <label class="form-label">Password</label>
                <a href="{{ route('password.request') }}" class="small">Lupa Password?</a>
            </div>

            <div class="input-group">
                <input id="password" type="password" class="form-control form-control-lg" name="password" required
                    placeholder="Password">

                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
            <label class="form-check-label text-primary" for="remember_me">Ingat saya</label>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg fw-semibold">Login</button>
        </div>
        <div class="text-center mt-3">
            <small class="text-muted">
                Belum punya akun? <a href="{{ route('register') }}" class="fw-medium text-decoration-none">Daftar di
                    sini</a>
            </small>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');

            togglePassword.addEventListener('click', function() {
                const type = password.type === 'password' ? 'text' : 'password';
                password.type = type;
                this.querySelector('i').classList.toggle('bi-eye');
                this.querySelector('i').classList.toggle('bi-eye-slash');
            });
        });
    </script>
@endpush
