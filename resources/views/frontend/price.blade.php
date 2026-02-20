@extends('layouts.app')

@section('title', 'Layanan Kami')

@section('content')
    {{-- ======================= HERO SECTION ======================= --}}
    <div class="price-hero">
        <div class="container text-center" data-aos="fade-up">
            <h1 class="display-4 text-primary fw-bold">layanan Kami</h1>
            <p class="lead text-secondary">Kami menyediakan solusi desain komprehensif untuk memenuhi setiap kebutuhan ruang
                Anda.</p>
        </div>
    </div>

    <section class="section-padding text-primary">
        <div class="container py-5">

            <div class="row g-4">
                {{-- Service 1 --}}
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="service-box">
                        <div class="icon"><i class="bi bi-house-door-fill text-primary"></i></div>
                        <h5>Desain Interior Residensial</h5>
                        <p>
                            Menciptakan hunian yang nyaman, fungsional, dan mencerminkan kepribadian Anda, mulai dari
                            apartemen, rumah tinggal, hingga villa.
                        </p>
                    </div>
                </div>

                {{-- Service 2 --}}
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-box">
                        <div class="icon"><i class="bi bi-building-fill text-primary"></i></div>
                        <h5>Desain Interior Komersial</h5>
                        <p>
                            Solusi desain untuk ruang komersial seperti kantor, toko ritel, restoran, dan kafe yang dapat
                            meningkatkan citra brand dan kenyamanan pelanggan.
                        </p>
                    </div>
                </div>

                {{-- Service 3 --}}
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-box">
                        <div class="icon"><i class="bi bi-rulers text-primary"></i></div>
                        <h5>Konsultasi & Perencanaan Ruang</h5>
                        <p>
                            Membantu Anda dalam tahap awal perencanaan, mulai dari layout, pemilihan material, hingga skema
                            warna yang paling sesuai.
                        </p>
                    </div>
                </div>

                {{-- Service 4 --}}
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="service-box">
                        <div class="icon"><i class="bi bi-palette-fill text-primary"></i></div>
                        <h5>Desain Furnitur Kustom</h5>
                        <p>
                            Merancang dan membuat furnitur yang dibuat khusus untuk memaksimalkan fungsi dan estetika ruang
                            Anda secara sempurna.
                        </p>
                    </div>
                </div>

                {{-- Service 5 --}}
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-box">
                        <div class="icon"><i class="bi bi-cone-striped text-primary"></i></div>
                        <h5>Manajemen & Pengawasan Proyek</h5>
                        <p>
                            Mengawasi setiap tahap implementasi desain di lapangan untuk memastikan hasil akhir sesuai
                            dengan rencana dan standar kualitas tertinggi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================= BAGIAN HARGA UTAMA ======================= --}}
    <div class="container py-5">
        <div class="text-center">
            <h2 class="section-title text-primary fw-bold">Harga Layanan Kami</h2>
            <p class="section-subtitle text-secondary">
                Transparan, kompetitif, dan disesuaikan dengan kebutuhan unik proyek Anda.
            </p>
        </div>
        <div class="card pricing-box shadow-sm" data-aos="fade-up" data-aos-delay="100">
            <div class="card-body p-4 p-md-5">

                <h4 class="text-center text-primary">Arsitektur & Interior</h4>
                <p class="text-center price text-dark">
                    Rp 2.800.000<span class="fs-4 text-muted fw-normal"> / m²</span>
                </p>

                <hr class="my-4">

                <ul class="list-group list-group-flush mb-4">
                    @foreach (['Free Survey Site', 'Free Consultation', 'Unlimited Revision', 'Measurement Of The Area', '2D Layout Concept', '3D Modelling Concept', '3D Facade Visualization', 'Architectural Drawing', 'Structural Drawing', 'Technical Drawing (MEP)'] as $item)
                        <li class="list-group-item">
                            <i class="bi bi-check-lg me-3 text-primary"></i>
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="d-grid mt-4">
                    <a href="{{ route('user.orders.create') }}" class="btn btn-outline-primary btn-lg">
                        Pilih Paket
                    </a>
                </div>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{--        SECTION 6 LANGKAH PEMESANAN (ICON VERSION)        --}}
        {{-- ======================================================== --}}
        <section class="section-padding mt-5">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-8 text-center" data-aos="fade-up">
                        <h2 class="section-title text-primary fw-bold">6 Langkah Menuju Interior Impianmu</h2>
                    </div>
                </div>

                @php
                    $steps = [
                        [
                            'icon' => 'bi bi-file-text-fill',
                            'title' => 'Registrasi & Kirim Permintaan',
                            'desc' =>
                                'Isi formulir pemesanan online kami dengan detail kebutuhan Anda. Tim kami akan segera meninjau permintaan Anda.',
                            'btn' => ['text' => 'Mulai Sekarang', 'link' => route('user.orders.create')],
                        ],
                        [
                            'icon' => 'bi-telephone-outbound-fill',
                            'title' => 'Konsultasi Desain Awal',
                            'desc' =>
                                'Tim desainer kami akan menghubungi Anda untuk diskusi lebih lanjut mengenai konsep, gaya, anggaran, dan survei lokasi.',
                            'btn' => ['text' => 'Hubungi Kami', 'link' => route('contact.form')],
                        ],
                        [
                            'icon' => 'bi-file-earmark-text-fill',
                            'title' => 'Penawaran & Kontrak',
                            'desc' =>
                                'Kami akan menyusun Rencana Anggaran Biaya (RAB) dan kontrak kerja. Proses desain dimulai setelah persetujuan.',
                            'btn' => ['text' => 'Lihat Struktur Harga', 'link' => route('price')],
                        ],
                        [
                            'icon' => 'bi-easel2-fill',
                            'title' => 'Pengembangan Desain 3D',
                            'desc' =>
                                'Desainer kami akan mengubah ide Anda menjadi visualisasi 3D yang realistis untuk Anda tinjau dan revisi.',
                            'btn' => ['text' => 'Lihat Portfolio', 'link' => route('projects.index')],
                        ],
                        [
                            'icon' => 'bi-tools',
                            'title' => 'Produksi & Instalasi',
                            'desc' =>
                                'Jika kamu sudah setuju dengan desain yang kami berikan, kami memulai tahap produksi furnitur kustom dan persiapan instalasi di lokasi proyek setelah pembayaran DP 50%.',
                            'btn' => ['text' => 'Tentang Kami', 'link' => route('about.us')],
                        ],
                        [
                            'icon' => 'bi-key-fill',
                            'title' => 'Serah Terima & Garansi',
                            'desc' =>
                                'Proyek selesai! Kami akan melakukan serah terima kunci dan memberikan garansi sebagai jaminan kualitas pengerjaan kami.',
                            'btn' => ['text' => 'Selesai!', 'link' => '#'],
                        ],
                    ];
                @endphp

                <div class="steps-carousel-container mt-5 position-relative">
                    {{-- Stepper Angka di Atas --}}
                    <div class="steps-progress-bar d-flex justify-content-center mb-4" data-aos="fade-up">
                        @foreach ($steps as $i => $s)
                            <div class="step-indicator {{ $i === 0 ? 'active' : '' }}" data-step="{{ $i + 1 }}">
                                <span>{{ $i + 1 }}</span>
                            </div>
                            @if ($i < count($steps) - 1)
                                <div class="step-connector"></div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Carousel Isi Langkah --}}
                    <div id="stepsCarousel" class="carousel slide" data-bs-ride="false">
                        <div class="carousel-inner">
                            @foreach ($steps as $i => $s)
                                <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                    <div class="text-center">
                                        <div class="step-icon-wrapper mb-4">
                                            <i class="bi {{ $s['icon'] }}"></i>
                                        </div>
                                        <div class="step-content mx-auto">
                                            <h4 class="fw-bold text-primary">{{ $s['title'] }}</h4>
                                            <p class="text-secondary">{{ $s['desc'] }}</p>
                                            <a href="{{ $s['btn']['link'] }}"
                                                class="btn btn-primary rounded-pill px-4 mt-2">{{ $s['btn']['text'] }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="progress-container">
                            <div class="progress-bar-fill" id="stepsProgressBar"></div>
                        </div>


                        {{-- Tombol Navigasi Kustom --}}
                        <button class="custom-carousel-control" type="button" id="stepsCarouselPrev">
                            <i class="bi bi-arrow-left-circle-fill"></i>
                        </button>
                        <button class="custom-carousel-control" type="button" id="stepsCarouselNext">
                            <i class="bi bi-arrow-right-circle-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.querySelector('#stepsCarousel');
            const items = carousel.querySelectorAll('.carousel-item');
            const indicators = document.querySelectorAll('.step-indicator');
            const prevBtn = document.querySelector('#stepsCarouselPrev');
            const nextBtn = document.querySelector('#stepsCarouselNext');
            const progressBar = document.querySelector('#stepsProgressBar');

            let currentIndex = 0;
            const totalSteps = items.length;
            let autoSlideInterval;

            // Fungsi update tampilan step & progress
            function updateCarousel() {
                items.forEach((item, index) => {
                    item.classList.toggle('active', index === currentIndex);
                });

                indicators.forEach((step, index) => {
                    step.classList.toggle('active', index === currentIndex);
                });

                // Progress bar (lebar)
                const progress = ((currentIndex + 1) / totalSteps) * 100;
                progressBar.style.width = `${progress}%`;
            }

            // Tombol navigasi
            prevBtn.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + totalSteps) % totalSteps;
                updateCarousel();
                resetAutoSlide();
            });

            nextBtn.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % totalSteps;
                updateCarousel();
                resetAutoSlide();
            });

            // Auto-slide setiap 5 detik
            function startAutoSlide() {
                autoSlideInterval = setInterval(() => {
                    currentIndex = (currentIndex + 1) % totalSteps;
                    updateCarousel();
                }, 5000);
            }

            function resetAutoSlide() {
                clearInterval(autoSlideInterval);
                startAutoSlide();
            }

            // Jalankan pertama kali
            updateCarousel();
            startAutoSlide();
        });
    </script>
@endpush
