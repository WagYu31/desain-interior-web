<section>
    <header>
        <h5 class="fw-bold">{{-- Menggantikan text-lg font-medium text-gray-900 --}}
            {{ __('Hapus Akun') }}
        </h5>

        <p class="mt-1 text-muted"> {{-- Menggantikan text-sm text-gray-600 --}}
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.') }}
        </p>
    </header>

    <!-- Tombol untuk memicu modal konfirmasi penghapusan -->
    <button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        {{ __('Hapus Akun') }}
    </button>

    <!-- Modal Konfirmasi Penghapusan Akun Bootstrap -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="confirmUserDeletionModalLabel">
                            {{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted">
                            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Harap masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
                        </p>

                        <div class="mt-3">
                            <label for="delete_password" class="form-label visually-hidden">{{ __('Password') }}</label>
                            {{-- visually-hidden jika tidak ingin label terlihat tapi tetap aksesibel --}}
                            <input id="delete_password" {{-- ID diubah agar unik jika ada input password lain di halaman --}} name="password" type="password"
                                class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                {{-- Menargetkan error bag 'userDeletion' --}} placeholder="{{ __('Password') }}" required>

                            @error('password', 'userDeletion')
                                {{-- Menampilkan error dari error bag 'userDeletion' --}}
                                <div class="invalid-feedback mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Batal') }}
                        </button>
                        <button type="submit" class="btn btn-danger ms-2">
                            {{ __('Hapus Akun') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        // Jika ada error validasi dari penghapusan user, tampilkan modal secara otomatis saat halaman dimuat
        @if ($errors->userDeletion->isNotEmpty())
            document.addEventListener('DOMContentLoaded', function() {
                var confirmUserDeletionModal = new bootstrap.Modal(document.getElementById(
                    'confirmUserDeletionModal'));
                confirmUserDeletionModal.show();
            });
        @endif
    </script>
@endpush
