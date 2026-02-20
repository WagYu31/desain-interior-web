<form action="{{ isset($admin) ? route('admin.admins.update', $admin) : route('admin.admins.store') }}" method="POST">
    @csrf
    @if (isset($admin))
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $admin->name ?? '') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
            <input type="email" id="email" name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $admin->email ?? '') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="role" class="form-label">Peran (Role) <span class="text-danger">*</span></label>
        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="">-- Pilih Peran --</option>
            @foreach ($roles as $role)
                <option value="{{ $role->name }}" 
                    @if(isset($admin) && $admin->hasRole($role->name)) selected @endif>
                    {{ Str::title($role->name) }}
                </option>
            @endforeach
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <hr class="my-4">
    <h5 class="mb-3">Ganti Password</h5>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="password" class="form-label">Password Baru</label>
            <input type="password" id="password" name="password"
                class="form-control @error('password') is-invalid @enderror" {{ isset($admin) ? '' : 'required' }}>
            @if (isset($admin))
                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
            @endif
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ isset($admin) ? 'Update Admin' : 'Simpan Admin' }}
        </button>
    </div>
</form>
