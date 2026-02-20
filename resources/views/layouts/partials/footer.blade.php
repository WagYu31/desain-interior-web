<footer class="site-footer">
    @php
        $s = \App\Models\SiteSetting::allAsArray();
    @endphp
    <div class="container">
        <div class="row">

            {{-- Kolom 1: Tentang Perusahaan --}}
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ Vite::asset('resources/images/logopt.png') }}" alt="Logo" style="height: 40px;" class="me-2">
                    <h5 class="mb-0">{{ $s['company_name'] ?? 'PT. ASTHA TUNGGAL MAKMUR' }}</h5>
                </div>
                <p>{{ $s['company_description'] ?? 'Mewujudkan ruang impian Anda dengan sentuhan profesional dan personal.' }}</p>
            </div>

            {{-- Kolom 2: Informasi Kontak --}}
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h5>Kontak & Alamat</h5>
                <ul class="list-unstyled">
                    <li class="mb-2 d-flex">
                        <i class="bi bi-geo-alt-fill me-2 pt-1"></i>
                        <span>
                            <strong>[Pusat]:</strong> {{ $s['address_main'] ?? '' }}
                        </span>
                    </li>
                    <li class="mb-2 d-flex">
                        <i class="bi bi-geo-alt-fill me-2 pt-1"></i>
                        <span>
                            <strong>[Cabang Jakarta]:</strong> {{ $s['address_branch'] ?? '' }}
                        </span>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone-fill me-2"></i>
                        <a href="tel:{{ preg_replace('/\s+/', '', $s['phone_main'] ?? '') }}">[Pusat]: {{ $s['phone_main'] ?? '' }}</a>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone-fill me-2"></i>
                        <a href="tel:{{ preg_replace('/\s+/', '', $s['phone_branch'] ?? '') }}">[Cabang Jakarta]: {{ $s['phone_branch'] ?? '' }}</a>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-envelope-fill me-2"></i>
                        <a href="mailto:{{ $s['email'] ?? '' }}">{{ $s['email'] ?? '' }}</a>
                    </li>
                </ul>
            </div>

            {{-- Kolom 3: Navigasi & Sosial Media --}}
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-md-6 mb-4 mb-lg-0">
                        <h5>Navigasi</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="{{ route('about.us') }}">Tentang Kami</a></li>
                            <li class="mb-2"><a href="{{ route('projects.index') }}">Portofolio</a></li>
                            <li class="mb-2"><a href="{{ route('contact.form') }}">Kontak</a></li>
                            <li class="mb-2"><a href="{{ route('price') }}">Layanan Kami</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Ikuti Kami</h5>
                        <div class="social-icons">
                            @php
                                $socials = [
                                    'instagram' => ['icon' => 'bi-instagram', 'url' => $s['social_instagram'] ?? ''],
                                    'facebook' => ['icon' => 'bi-facebook', 'url' => $s['social_facebook'] ?? ''],
                                    'tiktok' => ['icon' => 'bi-tiktok', 'url' => $s['social_tiktok'] ?? ''],
                                    'youtube' => ['icon' => 'bi-youtube', 'url' => $s['social_youtube'] ?? ''],
                                    'whatsapp' => ['icon' => 'bi-whatsapp', 'url' => $s['social_whatsapp'] ?? ''],
                                ];
                            @endphp
                            @foreach ($socials as $name => $social)
                                @if (!empty($social['url']))
                                    <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" title="{{ ucfirst($name) }}">
                                        <i class="bi {{ $social['icon'] }}"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Bagian Bawah Footer (Copyright) --}}
        <div class="row footer-bottom">
            <div class="col text-center">
                <p class="mb-0">Copyright © {{ date('Y') }}. All Rights Reserved. — {{ $s['company_name'] ?? 'PT. ASTHA TUNGGAL MAKMUR' }}</p>
            </div>
        </div>
    </div>
</footer>