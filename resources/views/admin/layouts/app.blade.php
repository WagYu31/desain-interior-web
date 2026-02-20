<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Laravel') }}</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/images/logopt.png') }}">

    {{-- Meta tags dan data inisialisasi untuk JavaScript (hanya dirender jika user login) --}}
    @auth
        <meta name="user-id" content="{{ Auth::id() }}">
        <meta name="user-role" content="{{ strtolower(Auth::user()->roles->first()->name ?? '') }}">
        <meta name="mark-as-read-url" content="{{ route('notifications.markAsRead.all') }}">
        <script>
            window.initialUnreadNotifications = {{ Auth::user()->unreadNotifications->count() }};
        </script>
    @endauth

    {{-- Aset yang di-compile oleh Vite (termasuk semua CSS dan JS) --}}
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    {{-- Slot untuk style tambahan per halaman --}}
    @stack('styles')
</head>

<body class="admin-layout">

    {{-- ======================= NAVBAR ======================= --}}
    <nav class="navbar navbar-expand navbar-dark bg-primary shadow-sm sticky-top">
        <div class="container-fluid px-3">

            {{-- Tombol Toggler untuk Sidebar di Mobile --}}
            <button class="btn btn-outline-secondary d-lg-none me-2" type="button" id="sidebarToggle"
                aria-label="Toggle sidebar">
                <i class="bi bi-list fs-4"></i>
            </button>

            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-shield-lock-fill me-2 d-none d-sm-inline"></i>Admin Panel
            </a>

            {{-- Tombol Toggler Navbar Kanan tidak lagi diperlukan, jadi dihapus --}}

            {{-- Item di sisi kanan navbar --}}
            <div class="ms-auto">
                <ul class="navbar-nav flex-row align-items-center">
                    <li class="nav-item me-2">
                        <a class="nav-link" href="{{ route('home') }}" target="_blank"
                            title="Lihat Tampilan Depan Situs">
                            <i class="bi bi-box-arrow-up-right me-1"></i>
                            <span class="d-none d-md-inline">Lihat Situs</span>
                        </a>
                    </li>

                    @auth
                        {{-- Dropdown Notifikasi --}}
                        <li class="nav-item dropdown me-2" id="notification-dropdown-container">
                            <a class="nav-link px-2" href="#" id="navbarNotificationDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false" style="position: relative;"
                                aria-label="Notifications">
                                <i class="bi bi-bell-fill fs-5"></i>
                                <span id="navbar-notification-count"
                                    class="badge rounded-pill bg-danger border border-light"
                                    style="position: absolute; top: 1px; right: -2px; font-size: 0.6em; display: none;"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2"
                                aria-labelledby="navbarNotificationDropdown" id="notification-list"
                                style="width: 350px; max-height: 400px; overflow-y: auto;">
                                {{-- Konten diisi oleh Blade & JavaScript --}}
                            </ul>
                        </li>

                        {{-- Dropdown --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminUserDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i>
                                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2"
                                aria-labelledby="adminUserDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i
                                            class="bi bi-gear-fill me-2"></i>Profil</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i
                                                class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- ======================= KONTEN UTAMA ======================= --}}
    <div class="admin-wrapper">
        <aside class="admin-sidebar" id="adminSidebar">
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 d-lg-none"
                id="sidebarClose" aria-label="Close"></button>
            @auth
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-grid-1x2-fill"></i>Dashboard
                        </a>
                    </li>
                    {{-- Menu operasional hanya untuk Admin --}}
                    @if(Auth::user()->hasRole('admin'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                            href="{{ route('admin.categories.index') }}">
                            <i class="bi bi-tags-fill"></i>Kategori Proyek
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('arsitek'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}"
                            href="{{ route('admin.projects.index') }}">
                            <i class="bi bi-archive-fill"></i>Proyek
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                            href="{{ route('admin.orders.index') }}">
                            <i class="bi bi-journal-text"></i>Pemesanan
                            <span id="admin-sidebar-order-badge" class="badge bg-info ms-auto"
                                style="display: none;"></span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->hasRole('admin'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.team-members.*') ? 'active' : '' }}"
                            href="{{ route('admin.team-members.index') }}">
                            <i class="bi bi-people-fill"></i>Manajemen Tim
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                            href="{{ route('admin.users.index') }}">
                            <i class="bi bi-person-lines-fill"></i>Data User
                        </a>
                    </li>
                    @endif
                    {{-- Menu Manajemen Admin hanya untuk Owner --}}
                    @if(Auth::user()->hasRole('owner'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}"
                            href="{{ route('admin.admins.index') }}">
                            <i class="bi bi-person-lock"></i> Manajemen Admin
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->hasRole('admin'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.analytics.risk') ? 'active' : '' }}"
                            href="{{ route('admin.analytics.risk') }}">
                            <i class="bi bi-graph-up-arrow"></i>Analisis Risiko
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.analytics.team-performance') ? 'active' : '' }}"
                            href="{{ route('admin.analytics.team-performance') }}">
                            <i class="bi bi-people-fill"></i>Performa Tim
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                            href="{{ route('admin.settings.index') }}">
                            <i class="bi bi-gear-fill"></i>Pengaturan Situs
                        </a>
                    </li>
                    @endif
                    @can('view reports')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                                href="{{ route('admin.reports.index') }}">
                                <i class="bi bi-file-earmark-bar-graph-fill"></i>Laporan
                            </a>
                        </li>
                    @endcan
                </ul>
            @endauth
        </aside>
        <main class="admin-content">
            @include('layouts.partials.alerts')
            @yield('content')
        </main>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    @stack('scripts')
</body>

</html>
