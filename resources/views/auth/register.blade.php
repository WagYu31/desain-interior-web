@extends('layouts.auth')

@section('title', 'Register Akun Baru')

@section('content')
    <div class="text-center mb-4">
        <a href="{{ route('home') }}">
            <img src="{{ Vite::asset('resources/images/logopt.png') }}" alt="Logo" style="max-height: 50px;">
        </a>
        <h4 class="fw-bold mt-4">Buat Akun Baru</h4>
        <p class="text-muted small">Bergabunglah dengan kami untuk memulai proyek impian Anda.</p>
    </div>

    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger p-2 small">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                name="email" value="{{ old('email') }}" required autocomplete="username">
        </div>

        <!-- Nomor Telepon (WA) -->
        <div class="mb-3">
            <label for="phone" class="form-label">No. Telepon</label>
            <div class="input-group">
                <span class="input-group-text">+62</span>
                <input id="phone" type="tel"
                    class="form-control form-control-lg @error('phone') is-invalid @enderror" name="phone"
                    value="{{ old('phone') }}" required placeholder="8123456789">
            </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input id="password" type="password"
                    class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required
                    autocomplete="new-password">
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <div class="input-group">
                <input id="password_confirmation" type="password" class="form-control form-control-lg"
                    name="password_confirmation" required autocomplete="new-password">
                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                Sign Up
            </button>
        </div>

        <div class="text-center mt-3">
            <small class="text-muted">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="fw-medium text-decoration-none">Login di sini</a>
            </small>
        </div>
    </form>
@endsection

@push('scripts')
    {{-- Kode JavaScript untuk show/hide kedua password --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function setupPasswordToggle(toggleButtonId, passwordInputId) {
                const toggleButton = document.querySelector(toggleButtonId);
                const passwordInput = document.querySelector(passwordInputId);

                if (toggleButton && passwordInput) {
                    toggleButton.addEventListener('click', function() {
                        const type = passwordInput.getAttribute('type') === 'password' ? 'text' :
                        'password';
                        passwordInput.setAttribute('type', type);

                        this.querySelector('i').classList.toggle('bi-eye');
                        this.querySelector('i').classList.toggle('bi-eye-slash');
                    });
                }
            }
            setupPasswordToggle('#togglePassword', '#password');
            setupPasswordToggle('#togglePasswordConfirmation', '#password_confirmation');
        });
    </script>
@endpush
