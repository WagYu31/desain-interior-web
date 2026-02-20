@extends('layouts.app')

@section('title', 'Beranda')

@php $useContainer = false; @endphp

@section('content')

    {{-- ================= HERO SECTION ================= --}}
    @php
        $slides = ['resources/images/bg.jpg', 'resources/images/bg1.jpg', 'resources/images/bg2.jpg'];
    @endphp

    <section id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            @foreach ($slides as $slide)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <img src="{{ Vite::asset($slide) }}" class="d-block w-100" alt="Interior Design Slide">
                    <div class="carousel-caption">
                        <div class="hero-content container">
                            <h1 data-aos="fade-right">{{ $settings['hero_title'] ?? 'Wujudkan Ruang Impian Anda' }}</h1>
                            <p class="lead" data-aos="fade-right" data-aos-delay="100">{{ $settings['hero_subtitle'] ?? 'Solusi desain interior dan arsitektur yang memadukan estetika, fungsionalitas, dan kenyamanan.' }}</p>
                            <div class="mt-4" data-aos="fade-up" data-aos-delay="200">
                                <a href="{{ route('contact.form') }}" class="btn btn-primary btn-lg">{{ $settings['hero_cta_text'] ?? 'Konsultasi Sekarang' }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev"><span
                class="carousel-control-prev-icon"></span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next"><span
                class="carousel-control-next-icon"></span></button>
    </section>

    {{-- ================= WHY CHOOSE US ================= --}}
    <section class="py-5 text-primary text-center rounded mb-4">
        <div class="container">
            <section class="text-primary">
                <div class="text-center mb-5">
                    <h2 class="section-title">{{ $settings['why_title'] ?? 'Kenapa Memilih Kami?' }}</h2>
                    <p class="section-subtitle">
                        {{ $settings['why_subtitle'] ?? 'Kami berkomitmen untuk memberikan hasil terbaik melalui layanan yang profesional, kreatif, dan terpercaya.' }}
                    </p>
                </div>

                <div class="row g-4">
                    @for($i = 1; $i <= 3; $i++)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" @if($i > 1) data-aos-delay="{{ ($i-1) * 100 }}" @endif>
                        <div class="bg-white text-center text-dark rounded p-4 h-100 shadow-sm feature-box">
                            <div class="icon mb-3">
                                <i class="bi {{ $settings['why_'.$i.'_icon'] ?? 'bi-star' }} fs-1 text-primary"></i>
                            </div>
                            <h5 class="fw-bold">{{ $settings['why_'.$i.'_title'] ?? '' }}</h5>
                            <p class="text-muted">
                                {{ $settings['why_'.$i.'_desc'] ?? '' }}
                            </p>
                        </div>
                    </div>
                    @endfor
                </div>
            </section>
        </div>
    </section>

    {{-- ================= PORTFOLIO ================= --}}
    <section class="py-5 text-primary text-center rounded mb-4">
        <div class="py-5">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-6 mx-auto text-center" data-aos="fade-up">
                        <h2 class="section-title">Portfolio</h2>
                        <p class="text-primary">
                            Lihat beberapa karya terbaik yang telah kami selesaikan untuk klien kami.
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    @forelse ($latestProjects as $project)
                        <div class="col-md-6 col-lg-4" data-aos="fade-up">
                            <a href="{{ route('projects.show', $project) }}" class="text-decoration-none text-dark">
                                <div class="card project-card h-100 shadow-sm border-0">
                                    @php
                                        $mainImage =
                                            $project->images->firstWhere('is_featured', true) ??
                                            $project->images->first();
                                    @endphp

                                    <img src="{{ $mainImage ? asset('storage/' . $mainImage->path) : asset('images/default.jpg') }}"
                                        class="card-img-top rounded-top" alt="{{ $project->name }}"
                                        style="height: 250px; object-fit: cover;">

                                    <div class="card-body">
                                        <h5 class="card-title">{{ $project->name }}</h5>
                                        <span class="badge bg-primary bg-opacity-10 text-primary mb-2">
                                            {{ $project->category->name ?? 'Tanpa Kategori' }}
                                        </span>
                                        <p class="card-text text-muted">{{ Str::limit($project->description, 100) }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-primary text-center rounded py-5">
                            <i class="bi bi-folder-x fs-1 text-primary"></i>
                            <p class="lead text-primary mt-3">Belum ada proyek yang ditampilkan.</p>
                        </div>
                    @endforelse
                </div>

                @if ($latestProjects->count() > 0)
                    <div class="text-primary text-center rounded mt-5">
                        <a href="{{ route('projects.index') }}" class="btn btn-outline-primary">Lihat Semua Proyek</a>
                    </div>
                @endif

            </div>
        </div>
    </section>

    {{-- ================= SERVICES ================= --}}
    <section class="section-padding text-primary">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">{{ $settings['services_title'] ?? 'Layanan Kami' }}</h2>
                <p class="section-subtitle">
                    {{ $settings['services_subtitle'] ?? 'Kami menyediakan solusi desain komprehensif untuk memenuhi setiap kebutuhan ruang Anda.' }}
                </p>
            </div>

            <div class="row g-4">
                @for($i = 1; $i <= 5; $i++)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" @if($i > 1) data-aos-delay="{{ (($i-1) % 3) * 100 }}" @endif>
                    <div class="service-box">
                        <div class="icon"><i class="bi {{ $settings['service_'.$i.'_icon'] ?? 'bi-gear' }} text-primary"></i></div>
                        <h5>{{ $settings['service_'.$i.'_title'] ?? '' }}</h5>
                        <p>
                            {{ $settings['service_'.$i.'_desc'] ?? '' }}
                        </p>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- ================= CALL TO ACTION ================= --}}
    <section class="py-5 text-primary text-center rounded" data-aos="fade-up">
        <div class="container">
            <div class="row align-items-center">

                {{-- Kolom Kiri: Teks Ajakan --}}
                <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0" data-aos="fade-right">
                    <h6 class="text-uppercase" style="color: #1a1919;">PUNYA PERTANYAAN?</h6>
                    <h2 class="section-title text-secondary">Pertanyaan yang Sering Diajukan</h2>
                    <p class="lead my-4 text-muted">Kami telah merangkum beberapa pertanyaan yang paling sering diajukan oleh klien
                        kami. Jika pertanyaan Anda tidak ada di sini, jangan ragu untuk menghubungi kami.</p>
                    <a href="{{ route('contact.form') }}" class="btn btn-primary btn-lg fw-bold">
                        <i class="bi bi-chat-dots-fill me-2"></i>Hubungi Kami
                    </a>
                </div>

                {{-- Kolom Kanan: Accordion FAQ --}}
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
                    <div class="accordion accordion-flush" id="faqAccordion">
                        @for($i = 1; $i <= 4; $i++)
                        @php
                            $headingId = 'heading' . $i;
                            $collapseId = 'collapse' . $i;
                        @endphp
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="{{ $headingId }}">
                                <button class="accordion-button {{ $i > 1 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#{{ $collapseId }}" aria-expanded="{{ $i === 1 ? 'true' : 'false' }}" aria-controls="{{ $collapseId }}">
                                    {{ $settings['faq_'.$i.'_question'] ?? '' }}
                                </button>
                            </h2>
                            <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $i === 1 ? 'show' : '' }}" aria-labelledby="{{ $headingId }}"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    {{ $settings['faq_'.$i.'_answer'] ?? '' }}
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
