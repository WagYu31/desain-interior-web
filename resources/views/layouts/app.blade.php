<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PT. ASTHA TUNGGAL MAKMUR</title>

    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/images/logopt.png') }}">

    {{-- Meta tags untuk JavaScript --}}
    @auth
        <meta name="user-id" content="{{ Auth::user()->id }}">
        <meta name="user-role" content="{{ strtolower(Auth::user()->roles->first()->name ?? '') }}">
        <meta name="mark-as-read-url" content="{{ route('notifications.markAsRead.all') }}">
        <script>
            window.initialUnreadNotifications = {{ Auth::user()->unreadNotifications->count() }};
        </script>
    @endauth

    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100 @if (request()->routeIs('home')) home-page @endif">
    <div class="min-vh-100">
        @include('layouts.navigation')

        <main class="flex-grow-1 main-content-with-bg main-content-padded">
            @if (isset($useContainer) && $useContainer === false)
                @yield('content')
            @else
                <div class="container py-4">
                    @yield('content')
                </div>
            @endif
        </main>

        @include('layouts.partials.footer')

        @php
            $whatsappNumber = '6281703799099';
            $chatHeaderName = 'Astha Tunggal Makmur';
            $chatWelcomeMessage = 'Hi! Ada yang bisa kami bantu terkait layanan desain interior?';
            $chatDefaultMessage = 'Halo, saya ingin bertanya tentang layanan Anda.';
        @endphp

        <!-- Tombol WhatsApp Mengambang (FAB) -->
        <div class="whatsapp-fab" id="whatsapp-fab">
            <i class="bi bi-whatsapp"></i>
        </div>

        <!-- Kotak Chat WhatsApp -->
        <div class="whatsapp-chatbox" id="whatsapp-chatbox" data-whatsapp-number="{{ $whatsappNumber }}"
            data-default-message="{{ $chatDefaultMessage }}">

            <div class="chatbox-header">
                <div class="d-flex align-items-center">
                    <i class="bi bi-whatsapp fs-4 me-2"></i>
                    <div>
                        <div class="fw-bold">{{ $chatHeaderName }}</div>
                        <small class="text-white-50">Fast Response</small>
                    </div>
                </div>
                <div class="chatbox-close" id="chatbox-close">×</div>
            </div>

            {{-- Isi Konten Chatbox --}}
            <div class="chatbox-content">
                <div class="chat-bubble">
                    {!! nl2br(e($chatWelcomeMessage)) !!}
                </div>
            </div>

            {{-- Footer/Input Chatbox --}}
            <div class="chatbox-footer">
                <a href="#" id="send-whatsapp-message" class="chatbox-send-btn" target="_blank">
                    Kirim Pesan
                    <i class="bi bi-send-fill ms-2"></i>
                </a>
            </div>
        </div>

    </div>

    @stack('scripts')
</body>

</html>
