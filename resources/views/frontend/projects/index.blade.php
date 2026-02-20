@extends('layouts.app')

@section('title', 'Portofolio')

@section('content')
    <div class="container py-4">
        <div class="text-center text-primary mb-5">
            <h1 class="display-5 fw-bold">Portfolio Proyek</h1>
            <p class="lead text-secondary">Temukan inspirasi dari berbagai proyek desain interior yang telah kami kerjakan.</p>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <form action="{{ route('projects.index') }}" method="GET" class="row g-3 align-items-center p-3 rounded">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Cari proyek..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-5">
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        @if ($projects->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach ($projects as $project)
                    <div class="col" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                        <a href="{{ route('projects.show', $project) }}" class="text-decoration-none">
                            <div class="card h-100 shadow-sm project-card border-0">
                                {{-- PERBAIKAN UTAMA DI SINI --}}
                                @php
                                    // Ambil gambar utama, atau gambar pertama jika tidak ada yang utama
                                    $thumbnail = $project->featuredImage ?? $project->images->first();
                                @endphp
                                @if ($thumbnail)
                                    <img src="{{ asset('storage/' . $thumbnail->path) }}" class="card-img-top"
                                        alt="{{ $project->name }}" style="height: 220px; object-fit: cover;">
                                @else
                                    <img src="https://via.placeholder.com/400x220.png?text=Project+Image"
                                        class="card-img-top" alt="No Image">
                                @endif

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-dark">{{ $project->name }}</h5>
                                    <p class="card-text text-muted small mb-2">
                                        <span
                                            class="badge bg-primary bg-opacity-10 text-primary">{{ $project->category->name ?? 'N/A' }}</span>
                                        | {{ $project->project_date->format('d M Y') }}
                                    </p>
                                    <p class="card-text flex-grow-1 text-secondary">
                                        {{ Str::limit($project->description, 100) }}</p>
                                </div>
                                <div class="card-footer bg-white border-0">
                                    <span class="btn btn-sm btn-outline-primary">Lihat Detail</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $projects->appends(request()->query())->links() }}
            </div>
        @else
            <div class="alert alert-info text-center" role="alert">
                Tidak ada proyek yang ditemukan sesuai kriteria Anda.
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15) !important;
            transition: transform .2s ease-in-out, box-shadow .2s ease-in-out;
        }
    </style>
@endpush
