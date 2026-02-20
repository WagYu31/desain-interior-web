@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        {{-- Left Column - Profile Card --}}
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body p-4">
                    {{-- Profile Photo Section --}}
                    <div class="position-relative d-inline-block mb-4">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                 alt="{{ $user->name }}" 
                                 class="rounded-circle shadow"
                                 style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #fff;">
                        @else
                            <div class="rounded-circle shadow d-flex align-items-center justify-content-center mx-auto"
                                 style="width: 150px; height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: 4px solid #fff;">
                                <span class="text-white display-3 fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        
                        {{-- Camera Icon for Upload --}}
                        <button type="button" 
                                class="btn btn-primary btn-sm rounded-circle position-absolute shadow"
                                style="bottom: 5px; right: 5px; width: 40px; height: 40px;"
                                data-bs-toggle="modal" 
                                data-bs-target="#photoModal">
                            <i class="bi bi-camera-fill"></i>
                        </button>
                    </div>
                    
                    {{-- User Info --}}
                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-2">
                        <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                    </p>
                    @if($user->phone)
                    <p class="text-muted mb-3">
                        <i class="bi bi-telephone me-1"></i>{{ $user->phone }}
                    </p>
                    @endif
                    
                    {{-- Role Badge --}}
                    @if($user->roles->count() > 0)
                    <div class="mb-3">
                        @foreach($user->roles as $role)
                            <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2">
                                <i class="bi bi-shield-check me-1"></i>{{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    </div>
                    @endif
                    
                    {{-- Member Since --}}
                    <p class="small text-muted mb-0">
                        <i class="bi bi-calendar3 me-1"></i>Bergabung {{ $user->created_at->translatedFormat('d F Y') }}
                    </p>
                </div>
            </div>
            
            {{-- Quick Stats --}}
            @if($user->hasRole('user'))
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart me-2"></i>Statistik Saya</h6>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <h4 class="fw-bold text-primary mb-0">{{ $user->orders()->count() }}</h4>
                                <small class="text-muted">Total Pesanan</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <h4 class="fw-bold text-success mb-0">{{ $user->orders()->whereHas('latestDetail', fn($q) => $q->where('status', 'completed'))->count() }}</h4>
                                <small class="text-muted">Selesai</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Right Column - Forms --}}
        <div class="col-lg-8">
            {{-- Success Messages --}}
            @if(session('status') === 'profile-updated')
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>Profil berhasil diperbarui!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @if(session('status') === 'photo-updated')
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>Foto profil berhasil diperbarui!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @if(session('status') === 'photo-deleted')
            <div class="alert alert-info alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>Foto profil berhasil dihapus.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- Update Profile Information --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-fill text-primary me-2"></i>Informasi Profil
                    </h5>
                </div>
                <div class="card-body p-4">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-shield-lock-fill text-warning me-2"></i>Ubah Password
                    </h5>
                </div>
                <div class="card-body p-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Photo Upload Modal --}}
<div class="modal fade" id="photoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-camera me-2"></i>Ubah Foto Profil
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-center py-4">
                    {{-- Preview --}}
                    <div class="mb-4">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                 alt="{{ $user->name }}" 
                                 id="photoPreview"
                                 class="rounded-circle shadow"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle shadow d-flex align-items-center justify-content-center mx-auto"
                                 id="photoPlaceholder"
                                 style="width: 150px; height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <span class="text-white display-3 fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <img src="" alt="" id="photoPreview" class="rounded-circle shadow d-none" 
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @endif
                    </div>
                    
                    {{-- File Input --}}
                    <div class="mb-3">
                        <label for="profile_photo" class="btn btn-outline-primary">
                            <i class="bi bi-upload me-2"></i>Pilih Foto
                        </label>
                        <input type="file" 
                               class="d-none" 
                               id="profile_photo" 
                               name="profile_photo" 
                               accept="image/*"
                               onchange="previewPhoto(this)">
                    </div>
                    <p class="text-muted small mb-0">Format: JPG, PNG, GIF, WEBP. Maksimal 2MB.</p>
                    
                    @error('profile_photo')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer border-0 justify-content-between">
                    @if($user->profile_photo)
                    <button type="button" class="btn btn-outline-danger" onclick="deletePhoto()">
                        <i class="bi bi-trash me-1"></i>Hapus Foto
                    </button>
                    @else
                    <div></div>
                    @endif
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Photo Form --}}
<form id="deletePhotoForm" action="{{ route('profile.photo.delete') }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photoPreview');
            const placeholder = document.getElementById('photoPlaceholder');
            
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            
            if (placeholder) {
                placeholder.classList.add('d-none');
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function deletePhoto() {
    if (confirm('Yakin ingin menghapus foto profil?')) {
        document.getElementById('deletePhotoForm').submit();
    }
}
</script>
@endpush