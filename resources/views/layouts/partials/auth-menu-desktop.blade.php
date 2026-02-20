@guest
    {{-- Tampilan untuk Pengunjung --}}
    <li class="nav-item">
        <a href="{{ route('login') }}" class="nav-link">Login</a>
    </li>
    @if (Route::has('register'))
        <li class="nav-item">
            <a href="{{ route('register') }}" class="nav-link">Sign Up</a>
        </li>
    @endif
@else
    {{-- Tampilan untuk User yang Sudah Login --}}

    <!-- Notifikasi (Versi Desktop) -->
    <li class="nav-item dropdown" id="notification-dropdown-container"> {{-- ID untuk container --}}
        <a class="nav-link" href="#" id="navbarNotificationDropdown" role="button" data-bs-toggle="dropdown"
            aria-expanded="false" style="position: relative;" aria-label="Notifications">
            <i class="bi bi-bell-fill fs-5"></i>
            {{-- ID unik untuk badge angka --}}
            <span id="navbar-notification-count" class="badge rounded-pill bg-danger border border-light"
                style="position: absolute; top: 1px; right: -2px; font-size: 0.6em; display: none;"></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="navbarNotificationDropdown"
            id="notification-list" style="width: 350px; max-height: 400px; overflow-y: auto;">
        </ul>
    </li>

    <!-- Dropdown User -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="navbarUserDropdown">
            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('owner') || Auth::user()->hasRole('arsitek'))
                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i
                            class="bi bi-shield-lock-fill me-2"></i>Admin Dashboard</a></li>
            @endif
            @hasrole('user')
                <li><a class="dropdown-item" href="{{ route('user.dashboard') }}"><i
                            class="bi bi-person-workspace me-2"></i>Dashboard</a></li>
                <li><a class="dropdown-item" href="{{ route('user.orders.index') }}"><i
                            class="bi bi-journal-text me-2"></i>Pemesanan Saya</a></li>
            @endhasrole
            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-gear-fill me-2"></i>Profil</a>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </button>
                </form>
            </li>
        </ul>
    </li>
@endguest
