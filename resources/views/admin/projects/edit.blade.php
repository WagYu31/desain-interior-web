@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.projects.index') }}">Proyek</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $project->title }}</li>
            </ol>
        </nav>
        <h1 class="h3 mb-4 text-gray-800">Edit Proyek: {{ $project->title }}</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Proyek</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Judul --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Proyek <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title" value="{{ old('title', $project->title) }}" required autofocus>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                            name="category_id" required>
                            <option value="">Pilih Kategori...</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $project->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal --}}
                    <div class="mb-3">
                        <label for="project_date" class="form-label">Tanggal Proyek <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('project_date') is-invalid @enderror"
                            id="project_date" name="project_date"
                            value="{{ old('project_date', $project->project_date->format('Y-m-d')) }}" required>
                        @error('project_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="5" required>{{ old('description', $project->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h5 class="mb-3">Kelola Gambar Proyek</h5>

                    {{-- Gambar yang sudah ada --}}
                    <div class="mb-3">
                        <label class="form-label">Gambar Saat Ini:</label>
                        <div id="existing-images-container" class="row g-3">
                            @forelse($project->images as $image)
                                <div class="col-lg-3 col-md-4 col-6 position-relative existing-image-wrapper">
                                    <div class="card preview-card existing-image {{ $image->is_featured ? 'featured' : '' }}"
                                        data-image-id="{{ $image->id }}">
                                        <img src="{{ asset('storage/' . $image->path) }}" class="img-fluid rounded">
                                        @if ($image->is_featured)
                                            <div class="badge bg-success position-absolute top-0 start-0 m-2">Utama</div>
                                        @endif
                                    </div>
                                    <button type="button"
                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 delete-image-btn"
                                        data-image-id="{{ $image->id }}" title="Hapus Gambar">&times;</button>
                                </div>
                            @empty
                                <p class="text-muted">Belum ada gambar.</p>
                            @endforelse
                        </div>
                        <small class="form-text text-muted">Klik gambar untuk menjadikannya gambar utama.</small>
                        <input type="hidden" name="existing_featured_image" id="existing_featured_image"
                            value="{{ $project->images->where('is_featured', true)->first()->id ?? '' }}">
                    </div>

                    {{-- Upload gambar baru --}}
                    <div class="mb-3">
                        <label for="images" class="form-label">Tambah Gambar Baru (Opsional)</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple
                            onchange="previewNewImages()">
                        <div id="new-image-preview-container" class="row g-3 mt-2"></div>
                        <input type="hidden" name="featured_image_index" id="featured_image_index">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Perbarui Proyek</button>
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>

                {{-- Form hapus tersembunyi --}}
                @foreach ($project->images as $image)
                    <form id="delete-form-{{ $image->id }}" action="{{ route('admin.project-images.destroy', $image) }}"
                        method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .preview-card {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            border: 2px solid transparent;
        }

        .preview-card:hover {
            transform: scale(1.03);
            border-color: #dee2e6;
        }

        .preview-card.featured {
            border: 3px solid #0d6efd;
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.5);
        }

        .delete-image-btn {
            cursor: pointer;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            line-height: 18px;
            text-align: center;
            font-weight: bold;
            padding: 0;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newImagePreviewContainer = document.getElementById('new-image-preview-container');
            const imageInput = document.getElementById('images');
            const existingFeaturedInput = document.getElementById('existing_featured_image');
            const newFeaturedInput = document.getElementById('featured_image_index');

            // Pilih gambar utama
            document.body.addEventListener('click', function(e) {
                const card = e.target.closest('.card.preview-card');
                if (card && !e.target.classList.contains('delete-image-btn')) {
                    document.querySelectorAll('.preview-card').forEach(c => c.classList.remove('featured'));
                    card.classList.add('featured');

                    if (card.classList.contains('existing-image')) {
                        existingFeaturedInput.value = card.dataset.imageId;
                        newFeaturedInput.value = '';
                    } else {
                        newFeaturedInput.value = card.dataset.index;
                        existingFeaturedInput.value = '';
                    }
                }
            });

            // Hapus gambar lama
            document.querySelectorAll('.delete-image-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
                        document.getElementById(`delete-form-${this.dataset.imageId}`).submit();
                    }
                });
            });

            // Preview gambar baru
            window.previewNewImages = function() {
                newImagePreviewContainer.innerHTML = '';
                if (imageInput.files.length > 0) {
                    Array.from(imageInput.files).forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const col = document.createElement('div');
                            col.className = 'col-lg-3 col-md-4 col-6';
                            const card = document.createElement('div');
                            card.className = 'card preview-card';
                            card.dataset.index = index;
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'img-fluid rounded';
                            card.appendChild(img);
                            col.appendChild(card);
                            newImagePreviewContainer.appendChild(col);
                        }
                        reader.readAsDataURL(file);
                    });
                }
            }
        });
    </script>
@endpush
