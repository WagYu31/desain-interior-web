@extends('layouts.app')

@section('title', $project->title)

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-10 mx-auto">

                {{-- Breadcrumb --}}
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-decoration-none text-primary">Beranda</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('projects.index') }}" class="text-decoration-none text-primary">Proyek</a>
                        </li>
                        <li class="breadcrumb-item active text-secondary" aria-current="page">{{ $project->title }}</li>
                    </ol>
                </nav>

                {{-- Judul & Info Proyek --}}
                <h1 class="display-5 fw-bold mb-3 text-primary">{{ $project->title }}</h1>
                <p class="text-secondary mb-4">
                    <span class="badge bg-secondary me-2">{{ $project->category->name ?? 'Tanpa Kategori' }}</span>
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ $project->project_date ? $project->project_date->format('d F Y') : 'Tanggal tidak tersedia' }}
                </p>

                {{-- Gambar Proyek --}}
                @if ($project->images->isNotEmpty())
                    <div id="projectGallery" class="carousel slide mt-3 shadow-sm rounded" data-bs-ride="carousel">
                        <div class="carousel-inner rounded">
                            @foreach ($project->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <div class="position-relative">
                                        @if ($image->is_featured)
                                            <span
                                                class="badge bg-success position-absolute top-0 start-0 m-2 px-3 py-2 fs-6 rounded-pill shadow">
                                                Utama
                                            </span>
                                        @endif
                                        <img src="{{ asset('storage/' . $image->path) }}"
                                            class="d-block w-100 rounded img-thumbnail project-image" alt="Gambar Proyek"
                                            data-bs-toggle="modal" data-bs-target="#imageModal"
                                            data-image="{{ asset('storage/' . $image->path) }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Navigasi Carousel --}}
                        <button class="carousel-control-prev" type="button" data-bs-target="#projectGallery"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Sebelumnya</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#projectGallery"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Berikutnya</span>
                        </button>
                    </div>
                @else
                    <div class="alert alert-secondary text-center mt-3">
                        Tidak ada gambar yang di-upload untuk proyek ini.
                    </div>
                @endif

                {{-- Modal Preview Gambar --}}
                <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content bg-transparent border-0">
                            <div class="modal-body p-0 position-relative">
                                <button type="button"
                                    class="btn-close position-absolute top-0 end-0 m-3 bg-light rounded-circle shadow"
                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                <img src="" id="modalImage" class="img-fluid rounded shadow" alt="Preview Gambar">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="project-description p-4 rounded shadow-sm mt-5">
                    <h3 class="fw-semibold mb-3 text-primary">Deskripsi Proyek</h3>
                    <p class="mb-0 text-secondary">{!! nl2br(e($project->description)) !!}</p>
                </div>

                {{-- CTA --}}
                <div class="mt-5 text-center">
                    <a href="{{ route('contact.form') }}" class="btn btn-outline-primary btn-lg px-4">
                        <i class="bi bi-telephone-fill me-2"></i> Tertarik dengan Desain Serupa? Hubungi Kami!
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .project-image {
            height: 500px;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
            cursor: zoom-in;
        }

        .project-image:hover {
            transform: scale(1.03);
        }

        .project-description {
            font-size: 1.1rem;
            line-height: 1.7;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color: $primary !important;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(1) brightness(1.5);
        }

        .modal img {
            width: 100%;
            height: auto;
            border-radius: 1rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            document.querySelectorAll('.project-image').forEach(img => {
                img.addEventListener('click', () => {
                    modalImage.src = img.dataset.image;
                });
            });
        });
    </script>
@endpush
