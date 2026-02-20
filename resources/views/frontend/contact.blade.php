@extends('layouts.app')

@section('title', 'Contact')

@section('content')
    {{-- Bagian Hero --}}
    <div class="py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold text-primary">Kontak</h1>
            <p class="lead text-secondary">Punya pertanyaan atau ingin memulai proyek? Jangan ragu untuk menghubungi kami melalui
                informasi di bawah ini atau kirimkan pesan langsung.</p>
        </div>
    </div>

    {{-- Bagian Utama --}}
    <div class="container my-5">
        {{-- Baris untuk Info Kontak dan Form --}}
        <div class="row g-5 mb-5">
            {{-- Kolom Kiri: Informasi Kontak --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle-fill me-2"></i>Informasi Kontak</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        {{-- Alamat Pusat --}}
                        <div class="list-group-item d-flex align-items-start py-3">
                            <i class="bi bi-geo-alt-fill fs-4 text-primary me-3"></i>
                            <div>
                                <h6 class="mb-1 fw-bold">Alamat [Pusat]</h6>
                                <p class="mb-0 text-muted">Jl. Kaliurang KM.15 Pojok, Harjobinangun, Pakem, Sleman,
                                    Yogyakarta 55582, Indonesia</p>
                            </div>
                        </div>
                        {{-- Alamat Cabang --}}
                        <div class="list-group-item d-flex align-items-start py-3">
                            <i class="bi bi-geo-alt-fill fs-4 text-primary me-3"></i>
                            <div>
                                <h6 class="mb-1 fw-bold">Alamat [Cabang Jakarta]</h6>
                                <p class="mb-0 text-muted">Jl. Kukusan Raya 166-102, Kukusan, Kecamatan Beji, Kota Depok,
                                    Jawa Barat 16425, Indonesia</p>
                            </div>
                        </div>
                        {{-- Email --}}
                        <div class="list-group-item d-flex align-items-start py-3">
                            <i class="bi bi-envelope-fill fs-4 text-primary me-3"></i>
                            <div>
                                <h6 class="mb-1 fw-bold">Email</h6>
                                <a href="mailto:asthattunggalmakmur@gmail.com"
                                    class="text-muted">asthatunggalmakmur@gmail.com</a>
                            </div>
                        </div>
                        {{-- Telepon/WA Pusat --}}
                        <div class="list-group-item d-flex align-items-start py-3">
                            <i class="bi bi-whatsapp fs-4 text-success me-3"></i>
                            <div>
                                <h6 class="mb-1 fw-bold">Telepon/WA [Pusat]</h6>
                                <a href="https://wa.me/628122993883" target="_blank" class="text-decoration-none">+62 812
                                    2993 883</a>
                            </div>
                        </div>
                        {{-- Telepon/WA Cabang --}}
                        <div class="list-group-item d-flex align-items-start py-3">
                            <i class="bi bi-whatsapp fs-4 text-success me-3"></i>
                            <div>
                                <h6 class="mb-1 fw-bold">Telepon/WA [Cabang Jakarta]</h6>
                                <a href="https://wa.me/6281311988070" target="_blank" class="text-decoration-none">+62 813
                                    1198 8070</a>
                            </div>
                        </div>
                        {{-- Jam Kerja --}}
                        <div class="list-group-item d-flex align-items-start py-3">
                            <i class="bi bi-clock-fill fs-4 text-primary me-3"></i>
                            <div>
                                <h6 class="mb-1 fw-bold">Jam Kerja</h6>
                                <p class="mb-0 text-muted">Senin - Jumat: 09:00 - 17:00 WIB<br>Sabtu & Minggu: Tutup</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Form Kontak --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Kirim Pesan Langsung</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('contact.send') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama Anda <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Anda <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subjek <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                    id="subject" name="subject" value="{{ old('subject') }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="message" class="form-label">Pesan Anda <span
                                        class="text-danger">*</span></label>
                                <textarea name="message" class="form-control @error('message') is-invalid @enderror" id="message" rows="6"
                                    required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Kirim Pesan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Baris Baru untuk Peta --}}
        <div class="row g-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold text-primary">Lokasi Workshop Kami</h2>
                <hr class="mx-auto" style="width: 100px;">
            </div>

            {{-- Peta Kiri: Pusat Yogyakarta --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-2">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31631.405533494024!2d110.39101535652256!3d-7.691124108861113!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5e882dee514d%3A0x87b9bf06859836a!2sDusun%20Pojok!5e0!3m2!1sid!2sid!4v1746388532298!5m2!1sid!2sid"
                            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade" class="rounded"></iframe>
                    </div>
                    <div class="card-footer text-center bg-white border-0">
                        <h6 class="mb-0">Workshop Pusat Yogyakarta</h6>
                    </div>
                </div>
            </div>

            {{-- Peta Kanan: Cabang Jakarta --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-2">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.180039139519!2d106.810880074099!3d-6.371488562333403!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69eef684999559%3A0xca99f3e1b78f8950!2sJl.%20Kukusan%20Raya%20No.166%2C%20Kukusan%2C%20Kecamatan%20Beji%2C%20Kota%20Depok%2C%20Jawa%20Barat%2016425!5e0!3m2!1sen!2sid!4v1716820138824!5m2!1sen!2sid"
                            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade" class="rounded"></iframe>
                    </div>
                    <div class="card-footer text-center bg-white border-0">
                        <h6 class="mb-0">Workshop Cabang Jakarta</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
