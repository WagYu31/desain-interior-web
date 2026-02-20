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

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detail Proyek: {{ $project->title }}</h6>
                <div>
                    <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-warning btn-sm"
                        title="Edit Proyek">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-fill me-1" viewBox="0 0 16 16">
                            <path
                                d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                        </svg>
                        Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- GALERI --}}
                    <div class="col-md-4">
                        
                        <h5><i class="bi bi-images me-2"></i>Galeri Gambar Proyek</h5>

                        @if ($project->images->isNotEmpty())
                            <div id="projectGallery" class="carousel slide mt-3" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach ($project->images as $index => $image)
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            @if ($image->is_featured)
                                                <span class="badge bg-success position-absolute top-0 start-0 m-2"
                                                    style="z-index: 1;">Utama</span>
                                            @endif
                                            <img src="{{ asset('storage/' . $image->path) }}"
                                                class="d-block w-100 rounded img-thumbnail" alt="Gambar Proyek">
                                        </div>
                                    @endforeach
                                </div>
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
                    </div>

                    {{-- DETAIL --}}
                    <div class="col-md-8">
                        <h4 class="mb-1">{{ $project->title }}</h4>
                        <p class="text-muted">
                            <span class="badge bg-primary">{{ $project->category->name }}</span> |
                            Tanggal Proyek: {{ $project->project_date->format('d F Y') }}
                        </p>
                        <hr>
                        <h5 class="mt-3">Deskripsi Proyek:</h5>
                        <div class="bg-light p-3 rounded">
                            {!! nl2br(e($project->description)) !!}
                        </div>
                    </div>
                </div>
                <hr>
                <p><strong>Dibuat pada:</strong> {{ $project->created_at->format('d M Y, H:i') }}</p>
                <p><strong>Terakhir diperbarui:</strong> {{ $project->updated_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>
@endsection
