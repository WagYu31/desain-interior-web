@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Tambah Proyek Baru</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Formulir Proyek</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Proyek <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title" value="{{ old('title') }}" required autofocus>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                            name="category_id" required>
                            <option value="">Pilih Kategori...</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="project_date" class="form-label">Tanggal Proyek <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('project_date') is-invalid @enderror"
                            id="project_date" name="project_date" value="{{ old('project_date') }}" required>
                        @error('project_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="5" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="images" class="form-label">Gambar Proyek (Pilih Lebih dari Satu) <span
                                class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('images') is-invalid @enderror" id="images"
                            name="images[]" multiple required onchange="previewImages()">
                        @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="image-preview-container" class="row g-3" style="display: none;">
                        {{-- Pratinjau gambar akan muncul di sini --}}
                    </div>

                    {{-- Hidden input untuk menyimpan index gambar utama --}}
                    <input type="hidden" name="featured_image" id="featured_image_index">
                    @error('featured_image')
                        <div class="text-danger mt-2"><small>{{ $message }}</small></div>
                    @enderror

                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-save-fill me-1" viewBox="0 0 16 16">
                            <path
                                d="M8.5 1.5A1.5 1.5 0 0 1 10 0h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h6c1.336 0 2.31.82 2.604 2H12A1 1 0 0 0 11 3v1a1 1 0 0 0 1 1h1V1.5H10a.5.5 0 0 0-.5.5V3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5V2a.5.5 0 0 0-.5-.5H14a.5.5 0 0 0-.5.5V3a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5V2A1.5 1.5 0 0 0 13.5 0h-4A1.5 1.5 0 0 0 8 1.5v2.134L1.697 6.49A.5.5 0 0 0 1 7v7a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5V7a.5.5 0 0 0-.276-.447L8.5 2.514V1.5zM3 9.5a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm0 2a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM3 5.5a1 1 0 1 1 2 0 1 1 0 0 1-2 0z" />
                        </svg>
                        Simpan Proyek
                    </button>
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImages() {
            const imageInput = document.querySelector('#images');
            const previewContainer = document.querySelector('#image-preview-container');
            const featuredImageIndexInput = document.querySelector('#featured_image_index');

            // Kosongkan kontainer setiap kali file berubah
            previewContainer.innerHTML = '';
            featuredImageIndexInput.value = '';

            if (imageInput.files.length > 0) {
                previewContainer.style.display = 'flex';
                previewContainer.insertAdjacentHTML('beforebegin',
                    '<p class="text-muted">Klik pada gambar untuk menjadikannya gambar utama (thumbnail).</p>');

                // Set gambar pertama sebagai default featured
                featuredImageIndexInput.value = 0;

                Array.from(imageInput.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3';

                        const card = document.createElement('div');
                        card.className = 'card preview-card';
                        card.dataset.index = index;
                        // Beri border pada gambar pertama
                        if (index === 0) card.classList.add('featured');

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-fluid rounded';

                        card.appendChild(img);
                        col.appendChild(card);
                        previewContainer.appendChild(col);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        // Event listener untuk memilih gambar utama
        document.addEventListener('click', function(e) {
            const card = e.target.closest('.preview-card');
            if (card) {
                document.querySelectorAll('.preview-card').forEach(c => c.classList.remove('featured'));
                card.classList.add('featured');
                document.querySelector('#featured_image_index').value = card.dataset.index;
            }
        });
    </script>
@endpush
