<form
    action="{{ isset($teamMember) ? route('admin.team-members.update', $teamMember) : route('admin.team-members.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    {{-- Method spoofing untuk request UPDATE --}}
    @if (isset($teamMember))
        @method('PUT')
    @endif

    {{-- Informasi Utama --}}
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $teamMember->name ?? '') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="position" class="form-label">Posisi / Jabatan <span class="text-danger">*</span></label>
            <select name="position" id="position" class="form-select" required>
                <option value="">Pilih Posisi</option>
                @foreach ($positions as $position)
                    <option value="{{ $position }}" 
                            {{ old('position', $teamMember->position ?? '') == $position ? 'selected' : '' }}>
                        {{ $position }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label for="photo" class="form-label">Foto Profil</label>
        <input type="file" id="photo" name="photo" class="form-control @error('photo') is-invalid @enderror"
            accept="image/*">
        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah foto. Ukuran maks: 2MB.</small>
        @error('photo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if (isset($teamMember))
            <div class="mt-2">
                <small>Foto saat ini:</small><br>
                @if ($teamMember->photo_path)
                    <img src="{{ asset('storage/' . $teamMember->photo_path) }}" alt="{{ $teamMember->name }}"
                        style="max-height: 100px;" class="rounded img-thumbnail">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remove_photo" id="remove_photo"
                            value="1">
                        <label class="form-check-label text-danger" for="remove_photo">
                            Hapus foto ini
                        </label>
                    </div>
                @else
                    <span class="text-muted"><em>Tidak ada foto.</em></span>
                @endif
            </div>
        @endif
    </div>

    <hr class="my-4">
    <h5 class="mb-3">Sosial Media (Opsional)</h5>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" id="email" name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $teamMember->email ?? '') }}" placeholder="contoh@email.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="whatsapp_url" class="form-label">WhatsApp (Nomor atau Link wa.me)</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
                <input type="text" id="whatsapp_url" name="whatsapp_url"
                    class="form-control @error('whatsapp_url') is-invalid @enderror"
                    value="{{ old('whatsapp_url', $teamMember->whatsapp_url ?? '') }}" placeholder="628123456789">
                @error('whatsapp_url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="linkedin_url" class="form-label">URL LinkedIn</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-linkedin"></i></span>
                <input type="url" id="linkedin_url" name="linkedin_url"
                    class="form-control @error('linkedin_url') is-invalid @enderror"
                    value="{{ old('linkedin_url', $teamMember->linkedin_url ?? '') }}"
                    placeholder="https://www.linkedin.com/in/nama">
                @error('linkedin_url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="instagram_url" class="form-label">URL Instagram</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-instagram"></i></span>
                <input type="url" id="instagram_url" name="instagram_url"
                    class="form-control @error('instagram_url') is-invalid @enderror"
                    value="{{ old('instagram_url', $teamMember->instagram_url ?? '') }}"
                    placeholder="https://www.instagram.com/nama">
                @error('instagram_url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        <a href="{{ route('admin.team-members.index') }}" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ isset($teamMember) ? 'Update Anggota Tim' : 'Simpan Anggota Tim' }}
        </button>
    </div>
</form>
