<section>
    <header>
        <h5 class="fw-bold"> {{-- Menggantikan text-lg font-medium text-gray-900 --}}
            {{ __('Ubah Password') }}
        </h5>

        <p class="mt-1 text-muted"> {{-- Menggantikan text-sm text-gray-600 --}}
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-3"> {{-- mt-6 space-y-6 diganti dengan margin Bootstrap --}}
        @csrf
        @method('put')

        <div class="mb-3"> {{-- Menggantikan div tanpa kelas dengan mb-3 untuk spasi --}}
            <label for="update_password_current_password" class="form-label">{{ __('Password Saat Ini') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password" required>
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback mt-2">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label">{{ __('Password Baru') }}</label>
            <input id="update_password_password" name="password" type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password" required>
            @error('password', 'updatePassword')
                <div class="invalid-feedback mt-2">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label">{{ __('Konfirmasi Password Baru') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password" required>
            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback mt-2">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="d-flex align-items-center"> {{-- Menggantikan flex items-center gap-4 --}}
            <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>

            @if (session('status') === 'password-updated')
                <span id="password-updated-message" class="ms-3 text-success"> {{-- Menggantikan <p x-data ...> dengan span dan ID --}}
                    {{ __('Tersimpan.') }}
                </span>
            @endif
        </div>
    </form>
</section>

@if (session('status') === 'password-updated')
@push('scripts')
<script>
    // Sembunyikan pesan "Tersimpan." setelah beberapa detik
    document.addEventListener('DOMContentLoaded', function () {
        const messageElement = document.getElementById('password-updated-message');
        if (messageElement) {
            setTimeout(() => {
                // Efek fade out sederhana atau langsung sembunyikan
                messageElement.style.transition = 'opacity 0.5s ease-out';
                messageElement.style.opacity = '0';
                setTimeout(() => messageElement.style.display = 'none', 500); // Hapus dari layout setelah fade
            }, 2500); // Waktu dalam milidetik (2.5 detik)
        }
    });
</script>
@endpush
@endif