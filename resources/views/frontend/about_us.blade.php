@extends('layouts.app')

@section('title', 'About Us')

@section('content')

    <section class="py-5 text-primary text-center rounded mb-4" data-aos="fade-up">
        <div class="container">
            <h1 class="display-4 fw-bold" data-aos="fade-up">Tentang Kami</h1>
            <p class="lead" data-aos="fade-up" data-aos-delay="100">Mengenal lebih dekat tim dan filosofi di balik setiap
                karya kami.</p>
        </div>
    </section>

    <section class="py-5 text-primary text-center rounded mb-4" data-aos="fade-up">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center" data-aos="fade-up">
                    <h2 class="section-title">PT. ASTHA TUNGGAL MAKMUR</h2>
                    <p class="lead">Kami adalah tim desainer interior dan arsitektur yang bersemangat dan berdedikasi
                        untuk
                        menciptakan ruang yang menginspirasi, fungsional, dan mencerminkan kepribadian unik setiap klien
                        kami.</p>
                    <p>Dengan pengalaman bertahun-tahun di industri ini, kami bangga dapat memberikan solusi desain inovatif
                        yang melampaui ekspektasi. Kami percaya bahwa setiap ruang memiliki potensi untuk menjadi luar
                        biasa, dan misi kami adalah membantu Anda mewujudkan potensi itu.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding text-primary">
        <div class="container">
            <div class="row g-5 text-center">
                <!-- Visi Kami -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div
                        class="bg-white text-primary rounded p-4 h-100 shadow-sm feature-box d-flex flex-column justify-content-center">
                        <h3 class="fw-bold mb-3">
                            <i class="bi bi-eye-fill me-2 text-primary"></i>Visi Kami
                        </h3>
                        <p class="mb-0">
                            Menjadi perusahaan desain interior dan arsitektur terdepan yang diakui secara nasional atas
                            inovasi, kualitas, dan komitmen terhadap kepuasan klien.
                        </p>
                    </div>
                </div>

                <!-- Misi Kami -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div
                        class="bg-white text-primary rounded p-4 h-100 shadow-sm feature-box d-flex flex-column justify-content-center">
                        <h3 class="fw-bold mb-3">
                            <i class="bi bi-bullseye me-2 text-primary"></i>Misi Kami
                        </h3>
                        <ul class="vision-mission-list list-unstyled text-start mx-auto" style="max-width: 280px;">
                            <li><i class="bi bi-check2-circle text-primary me-2"></i>Memberikan solusi desain kreatif,
                                fungsional, dan personal.</li>
                            <li><i class="bi bi-check2-circle text-primary me-2"></i>Mengutamakan kualitas material dan
                                pengerjaan di setiap proyek.</li>
                            <li><i class="bi bi-check2-circle text-primary me-2"></i>Membangun hubungan jangka panjang
                                dengan klien berdasarkan kepercayaan.</li>
                        </ul>
                    </div>
                </div>

                <!-- Nilai Kami -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div
                        class="bg-white text-primary rounded p-4 h-100 shadow-sm feature-box d-flex flex-column justify-content-center">
                        <h3 class="fw-bold mb-3">
                            <i class="bi bi-gem me-2 text-primary"></i>Nilai Kami
                        </h3>
                        <ul class="vision-mission-list list-unstyled text-start mx-auto" style="max-width: 280px;">
                            <li><i class="bi bi-check2-circle text-primary me-2"></i>Kreativitas & Inovasi</li>
                            <li><i class="bi bi-check2-circle text-primary me-2"></i>Kualitas & Detail</li>
                            <li><i class="bi bi-check2-circle text-primary me-2"></i>Integritas Profesional</li>
                            <li><i class="bi bi-check2-circle text-primary me-2"></i>Kepuasan Klien</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Team Section --}}
    {{-- ======================= TIM KAMI (DINAMIS DARI DATABASE) ======================= --}}
    <section class="py-5 text-white text-center rounded mb-4" data-aos="fade-up">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center" data-aos="fade-up">
                    <h2 class="section-title text-primary">Tim Profesional Kami</h2>
                    <p class="section-subtitle text-secondary">Kami didukung oleh tim profesional yang berpengalaman di
                        bidangnya.</p>

                    <div class="row g-4 justify-content-center">

                        @forelse ($teamMembers as $member)
                            <div class="col-md-6 col-lg-3" data-aos="fade-up"
                                data-aos-delay="{{ ($loop->index % 4) * 100 }}">
                                <div class="card border-0 shadow-sm h-100 team-card">
                                    @if ($member->photo_path)
                                        <img src="{{ $member->photo_path ? asset('storage/' . $member->photo_path) : 'https://via.placeholder.com/150/E8E8E8/B0B0B0?text=Foto' }}"
                                            class="card-img-top rounded-circle p-3 mx-auto mt-3"
                                            style="width: 150px; height: 150px; object-fit: cover;"
                                            alt="{{ $member->name }}">
                                    @else
                                        <div class="card-img-top rounded-circle p-3 mx-auto mt-3 d-flex align-items-center justify-content-center"
                                            style="width: 150px; height: 150px; background-color: #e9ecef;">
                                            <i class="bi bi-person-fill" style="font-size: 80px; color: #adb5bd;"></i>
                                        </div>
                                    @endif
                                    <div class="card-body text-center">
                                        <h5 class="card-title fw-bold">{{ $member->name }}</h5>
                                        <p class="card-text text-muted">{{ $member->position }}</p>

                                        <div class="mt-3">
                                            @if ($member->linkedin_url)
                                                <a href="{{ $member->linkedin_url }}" target="_blank"
                                                    class="text-secondary me-2 fs-4" title="LinkedIn"><i
                                                        class="bi bi-linkedin"></i></a>
                                            @endif
                                            @if ($member->instagram_url)
                                                <a href="{{ $member->instagram_url }}" target="_blank"
                                                    class="text-secondary me-2 fs-4" title="Instagram"><i
                                                        class="bi bi-instagram"></i></a>
                                            @endif
                                            @if ($member->email)
                                                <a href="mailto:{{ $member->email }}" class="text-secondary me-2 fs-4"
                                                    title="Email"><i class="bi bi-envelope-fill"></i></a>
                                            @endif
                                            @if ($member->whatsapp_url)
                                                @php
                                                    $waLink = $member->whatsapp_url;
                                                    if (is_numeric(str_replace(['+', ' '], '', $waLink))) {
                                                        $waNumber = preg_replace('/[^0-9]/', '', $waLink);
                                                        if (substr($waNumber, 0, 1) === '0') {
                                                            $waNumber = '62' . substr($waNumber, 1);
                                                        }
                                                        $waLink = 'https://wa.me/' . $waNumber;
                                                    }
                                                @endphp
                                                <a href="{{ $waLink }}" target="_blank" class="text-secondary fs-4"
                                                    title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-primary">Data tim akan segera ditampilkan.</p>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
@endsection
