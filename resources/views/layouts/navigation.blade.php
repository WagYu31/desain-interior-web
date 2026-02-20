<nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-transparent">
    <div class="container-fluid">
        <!-- Logo & Nama Perusahaan -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ Vite::asset('resources/images/logopt.png') }}" alt="Logo" style="height: 35px;" class="me-2">
            <span class="fw-bold d-none d-sm-inline" style="font-size: 0.9rem;">PT. ASTHA TUNGGAL MAKMUR</span>
        </a>

        <!-- Tombol Toggler (Offcanvas) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mainNavbarOffcanvas"
            aria-controls="mainNavbarOffcanvas" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- ============================================= -->
        <!--        MENU OFFCANVAS UNTUK MOBILE            -->
        <!-- ============================================= -->
        <div class="offcanvas offcanvas-end text-bg-primary d-lg-none" tabindex="-1" id="mainNavbarOffcanvas"
            aria-labelledby="mainNavbarOffcanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="mainNavbarOffcanvasLabel">Menu</h5>
                <button type="button" class="btn-close btn-close-primary" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body d-flex flex-column">
                <!-- Menu Navigasi Utama -->
                <ul class="navbar-nav flex-grow-1">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('about.us') ? 'active' : '' }}"
                            href="{{ route('about.us') }}">About Us</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('projects.index') ? 'active' : '' }}"
                            href="{{ route('projects.index') }}">Portofolio</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact.form') ? 'active' : '' }}"
                            href="{{ route('contact.form') }}">Contact</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('price') ? 'active' : '' }}"
                            href="{{ route('price') }}">Our Service</a></li>
                </ul>
                <hr class="text-white-50">
                <!-- Menu Otentikasi (Bawah Offcanvas) -->
                <ul class="navbar-nav">
                    @include('layouts.partials.auth-menu-mobile')
                </ul>
            </div>
        </div>

        <!-- ============================================= -->
        <!--        MENU NAVBAR UNTUK DESKTOP              -->
        <!-- ============================================= -->
        <div class="collapse navbar-collapse d-none d-lg-flex" id="navbarNavDropdown">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('about.us') ? 'active' : '' }}"
                        href="{{ route('about.us') }}">Tentang Kami</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('projects.index') ? 'active' : '' }}"
                        href="{{ route('projects.index') }}">Portofolio</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact.form') ? 'active' : '' }}"
                        href="{{ route('contact.form') }}">Kontak</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('price') ? 'active' : '' }}"
                        href="{{ route('price') }}">Layanan Kami</a></li>
                @include('layouts.partials.auth-menu-desktop')
            </ul>
        </div>
    </div>
</nav>
