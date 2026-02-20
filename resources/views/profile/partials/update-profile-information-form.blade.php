<section>
    <header>
        <h5 class="fw-bold"> {{-- Menggantikan text-lg font-medium text-gray-900 --}}
            {{ __('Informasi Profil') }}
        </h5>

        <p class="mt-1 text-muted"> {{-- Menggantikan text-sm text-gray-600 --}}
            {{ __("Perbarui informasi profil dan alamat email akun Anda.") }}
        </p>
    </header>

    {{-- Form ini hanya untuk mengirim ulang email verifikasi, tidak terlihat langsung oleh user kecuali melalui tombol --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-3"> {{-- mt-6 space-y-6 diganti dengan margin Bootstrap --}}
        @csrf
        @method('patch')

        <div class="mb-3"> {{-- Menggantikan div tanpa kelas dengan mb-3 untuk spasi --}}
            <label for="profile_name" class="form-label">{{ __('Nama') }}</label>
            <input id="profile_name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback mt-2">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="profile_email" class="form-label">{{ __('Email') }}</label>
            <input id="profile_email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback mt-2">
                    {{ $message }}
                </div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="small text-muted"> {{-- Menggantikan text-sm text-gray-800 --}}
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification" type="submit" class="btn btn-link p-0 text-decoration-none text-primary small"> {{-- Menggantikan button Tailwind --}}
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 small text-success fw-medium"> {{-- Menggantikan text-sm text-green-600 font-medium --}}
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center"> {{-- Menggantikan flex items-center gap-4 --}}
            <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>

            @if (session('status') === 'profile-updated')
                <span id="profile-updated-message" class="ms-3 text-success"> {{-- Menggantikan <p x-data ...> dengan span dan ID --}}
                    {{ __('Tersimpan.') }}
                </span>
            @endif
        </div>
    </form>
</section>

@if (session('status') === 'profile-updated')
@push('scripts')
<script>
    // Sembunyikan pesan "Tersimpan." setelah beberapa detik
    document.addEventListener('DOMContentLoaded', function () {
        const messageElement = document.getElementById('profile-updated-message');
        if (messageElement) {
            setTimeout(() => {
                messageElement.style.transition = 'opacity 0.5s ease-out';
                messageElement.style.opacity = '0';
                setTimeout(() => messageElement.style.display = 'none', 500);
            }, 2500); // Waktu dalam milidetik
        }
    });
</script>
@endpush
@endif