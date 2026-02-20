<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/images/logopt.png') }}">

    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    @stack('styles')

    <style>
        body.auth-page {
            /* Gambar latar belakang utama */
            background: url("{{ Vite::asset('resources/images/illustration.png') }}") center/cover no-repeat;
            min-height: 100vh;
            /* Gunakan min-height agar bisa lebih panjang jika perlu */
            display: flex;
            flex-direction: column;
            /* Atur flex direction */
        }

        .auth-wrapper {
            width: 100%;
            display: flex;
            flex-grow: 1;
            /* Biarkan wrapper tumbuh mengisi sisa ruang */
            align-items: center;
            /* Tetap di tengah secara vertikal */
            justify-content: flex-end;
            /* Posisikan ke kanan di desktop */
            padding: 3rem 2rem;
            /* Beri padding atas-bawah dan kanan-kiri */
            position: relative;
        }

        .auth-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
        }

        .auth-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 450px;
            /* Sedikit lebih lebar untuk form register */
            background: rgba(255, 255, 255, 0.95);
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Responsif untuk layar kecil */
        @media (max-width: 991.98px) {
            body.auth-page {
                overflow-y: auto;
                /* Izinkan scroll di mobile jika form panjang */
            }

            .auth-wrapper {
                justify-content: center;
                /* Posisikan ke tengah di mobile */
                padding: 2rem 1rem;
                /* Padding lebih kecil di mobile */
                align-items: flex-start;
                /* Mulai dari atas di mobile */
            }
        }
    </style>
</head>

<body class="auth-page">

    <div class="auth-wrapper">
        <div class="auth-card" data-aos="fade-up">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>

</html>
