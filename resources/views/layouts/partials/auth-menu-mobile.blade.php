@guest
    <li class="nav-item">
        <a href="{{ route('login') }}" class="nav-link">Login</a>
    </li>
    @if (Route::has('register'))
        <li class="nav-item">
            <a href="{{ route('register') }}" class="nav-link">Register</a>
        </li>
    @endif
@else
    {{-- Notifikasi Dropdown --}}
    <li class="nav-item dropdown" id="notification-dropdown-container">
        <a class="nav-link dropdown-toggle" href="#" id="navbarNotificationDropdownMobile" role="button"
            data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
            <i class="bi bi-bell-fill fs-5"></i>
            Notifikasi
            <span id="navbar-notification-count" class="badge rounded-pill bg-danger border border-light"
                style="position: absolute; top: 1px; right: -2px; font-size: 0.6em; display: none;"></span>
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarNotificationDropdownMobile" id="notification-list-mobile"
            style="width: 300px; max-height: 300px; overflow-y: auto;">
        </ul>
    </li>

    {{-- Nama Akun --}}
    <li class="nav-item">
        <span class="nav-link disabled text-white-50">
            Akun: {{ Auth::user()->name }}
        </span>
    </li>

    {{-- Link Role Admin --}}
    @hasrole('admin')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
        </li>
    @endhasrole

    {{-- Link Role User --}}
    @hasrole('user')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.dashboard') }}">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.orders.index') }}">Pemesanan Saya</a>
        </li>
    @endhasrole

    {{-- Profil --}}
    <li class="nav-item">
        <a class="nav-link" href="{{ route('profile.edit') }}">Profil</a>
    </li>

    {{-- Logout Button --}}
    <li class="nav-item mt-2">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100">
                Logout
            </button>
        </form>
    </li>
@endguest
