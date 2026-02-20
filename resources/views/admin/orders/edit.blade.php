@extends('admin.layouts.app')


@section('title', 'Update Progress Pesanan #' . $order->user_order_id)

@section('content')

    @php
        $latestDetail = $order->latestDetail;
    @endphp

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Pemesanan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update Progress Pesanan #{{ $order->id }}</li>
        </ol>
    </nav>

    @cannot('update', $order)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Pesanan ini sudah selesai atau dibatalkan dan tidak dapat diedit lagi.
        </div>
    @endcannot

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary">
                Update Progress untuk Pesanan #{{ $order->id }}
                <small class="text-muted">(Pemesan: {{ $order->user->name }})</small>
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <fieldset @cannot('update', $order) disabled @endcannot>

                    {{-- Baris 1: Status & Foto --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status Pemesanan <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                                required>
                                {{-- PERBAIKAN: Gunakan status dari $latestDetail sebagai nilai awal --}}
                                <option value="pending"
                                    {{ old('status', $latestDetail->status ?? '') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="in_progress"
                                    {{ old('status', $latestDetail->status ?? '') == 'in_progress' ? 'selected' : '' }}>
                                    Dalam Pengerjaan</option>
                                <option value="completed"
                                    {{ old('status', $latestDetail->status ?? '') == 'completed' ? 'selected' : '' }}>
                                    Selesai</option>
                                <option value="cancelled"
                                    {{ old('status', $latestDetail->status ?? '') == 'cancelled' ? 'selected' : '' }}>
                                    Dibatalkan</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @if(($latestDetail->status ?? 'pending') !== 'pending' || Auth::user()->hasRole('arsitek'))
                        <div class="col-md-6 mb-3">
                            {{-- PERBAIKAN: Mengganti nama input 'photos[]' menjadi 'new_photos[]' agar sesuai controller --}}
                            <label for="new_photos" class="form-label">Tambah Foto Progress Baru</label>
                            <input type="file" name="new_photos[]" id="new_photos" class="form-control" multiple
                                accept="image/*">
                            @error('new_photos.*')
                                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                        @endif
                    </div>

                    {{-- Baris 2: Detail Progress & Harga Final --}}
                    @if(($latestDetail->status ?? 'pending') !== 'pending' || Auth::user()->hasRole('arsitek'))
                    <div class="mb-3">
                        <label for="progress_details" class="form-label">Detail Progress (Catatan untuk User)</label>
                        {{-- PERBAIKAN: Gunakan progress_description dari $latestDetail --}}
                        <textarea class="form-control @error('progress_details') is-invalid @enderror" id="progress_details"
                            name="progress_details" rows="5"
                            placeholder="Contoh: Tahap pemasangan keramik sudah selesai. Berikut foto terlampir.">{{ old('progress_details', $latestDetail->progress_description ?? '') }}</textarea>
                        @error('progress_details')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif

                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('owner'))
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="final_price" class="form-label">Harga Final (setelah RAB)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                {{-- PERBAIKAN: Gunakan final_price dari $latestDetail --}}
                                <input type="number" step="1000" name="final_price" id="final_price"
                                    class="form-control" value="{{ old('final_price', $latestDetail->final_price ?? '') }}"
                                    placeholder="Contoh: 50000000">
                            </div>
                            <small class="form-text">Isi setelah harga disetujui klien. Ini akan digunakan untuk
                                laporan.</small>
                        </div>
                    </div>

                    <hr>
                    <h5 class="mb-3">Tugaskan Tim</h5>
                    <div class="mb-3">
                        <label for="team_members" class="form-label">Pilih Anggota Tim untuk Proyek Ini</label>
                        <select class="form-select" name="team_members[]" id="team_members" multiple size="5">
                            @php
                                $currentTeamIds = $latestDetail->team_member_ids ?? [];
                            @endphp
                            @foreach ($allTeamMembers as $member)
                                <option value="{{ $member->id }}"
                                    {{ in_array($member->id, $currentTeamIds) ? 'selected' : '' }}>
                                    {{ $member->name }} ({{ $member->position }})
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text">Tahan Ctrl (atau Cmd di Mac) untuk memilih lebih dari satu.</small>
                    </div>
                    @endif
                </fieldset>

                <hr>

                {{-- Tampilkan foto yang sudah ada --}}
                @php
                    $allPhotos = $order->details->pluck('photos')->flatten()->filter();
                @endphp
                @if ($allPhotos->isNotEmpty())
                    <h5><i class="bi bi-images me-2"></i>Semua Foto Progress yang Pernah Diunggah</h5>
                    <div class="row g-3 mt-2 mb-4">
                        @foreach ($allPhotos as $photoPath)
                            <div class="col-lg-2 col-md-3 col-4">
                                <a href="{{ asset('storage/' . $photoPath) }}" target="_blank" title="Lihat gambar">
                                    <img src="{{ asset('storage/' . $photoPath) }}" alt="Progress Photo"
                                        class="img-fluid rounded img-thumbnail">
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary me-2">Kembali</a>
                    @can('update', $order)
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle-fill me-1"></i>Simpan Perubahan
                        </button>
                    @endcan
                </div>

            </form>
        </div>
    </div>
@endsection
