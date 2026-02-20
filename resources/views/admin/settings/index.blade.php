@extends('admin.layouts.app')

@section('title', 'Pengaturan Situs')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-gear-fill me-2"></i>Pengaturan Situs</h2>
            <p class="text-muted mb-0">Kelola konten halaman utama website</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero"
                    type="button" role="tab"><i class="bi bi-image me-1"></i>Hero</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="why-tab" data-bs-toggle="tab" data-bs-target="#why"
                    type="button" role="tab"><i class="bi bi-star me-1"></i>Keunggulan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#services"
                    type="button" role="tab"><i class="bi bi-briefcase me-1"></i>Layanan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faq"
                    type="button" role="tab"><i class="bi bi-question-circle me-1"></i>FAQ</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="footer-tab" data-bs-toggle="tab" data-bs-target="#footer"
                    type="button" role="tab"><i class="bi bi-layout-text-window-reverse me-1"></i>Footer</button>
            </li>
        </ul>

        <div class="tab-content" id="settingsTabContent">

            {{-- ============== TAB 1: HERO ============== --}}
            <div class="tab-pane fade show active" id="hero" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-image-fill me-2 text-primary"></i>Hero Section</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Hero</label>
                            <input type="text" class="form-control" name="hero_title"
                                value="{{ $settings['hero_title'] ?? '' }}" placeholder="Wujudkan Ruang Impian Anda">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Subtitle Hero</label>
                            <textarea class="form-control" name="hero_subtitle" rows="2"
                                placeholder="Deskripsi singkat...">{{ $settings['hero_subtitle'] ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Teks Tombol CTA</label>
                            <input type="text" class="form-control" name="hero_cta_text"
                                value="{{ $settings['hero_cta_text'] ?? '' }}" placeholder="Konsultasi Sekarang">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============== TAB 2: WHY CHOOSE US ============== --}}
            <div class="tab-pane fade" id="why" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-star-fill me-2 text-warning"></i>Kenapa Memilih Kami</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Section</label>
                            <input type="text" class="form-control" name="why_title"
                                value="{{ $settings['why_title'] ?? '' }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Subtitle Section</label>
                            <textarea class="form-control" name="why_subtitle" rows="2">{{ $settings['why_subtitle'] ?? '' }}</textarea>
                        </div>

                        @for($i = 1; $i <= 3; $i++)
                        <div class="card bg-light border-0 mb-3">
                            <div class="card-body">
                                <h6 class="fw-bold text-primary mb-3">Keunggulan {{ $i }}</h6>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Icon (Bootstrap Icons)</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi {{ $settings['why_'.$i.'_icon'] ?? '' }}"></i></span>
                                            <input type="text" class="form-control" name="why_{{ $i }}_icon"
                                                value="{{ $settings['why_'.$i.'_icon'] ?? '' }}" placeholder="bi-award">
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-label">Judul</label>
                                        <input type="text" class="form-control" name="why_{{ $i }}_title"
                                            value="{{ $settings['why_'.$i.'_title'] ?? '' }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="why_{{ $i }}_desc" rows="2">{{ $settings['why_'.$i.'_desc'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- ============== TAB 3: SERVICES ============== --}}
            <div class="tab-pane fade" id="services" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-briefcase-fill me-2 text-success"></i>Layanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Section</label>
                            <input type="text" class="form-control" name="services_title"
                                value="{{ $settings['services_title'] ?? '' }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Subtitle Section</label>
                            <textarea class="form-control" name="services_subtitle" rows="2">{{ $settings['services_subtitle'] ?? '' }}</textarea>
                        </div>

                        @for($i = 1; $i <= 5; $i++)
                        <div class="card bg-light border-0 mb-3">
                            <div class="card-body">
                                <h6 class="fw-bold text-success mb-3">Layanan {{ $i }}</h6>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Icon</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi {{ $settings['service_'.$i.'_icon'] ?? '' }}"></i></span>
                                            <input type="text" class="form-control" name="service_{{ $i }}_icon"
                                                value="{{ $settings['service_'.$i.'_icon'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-label">Judul</label>
                                        <input type="text" class="form-control" name="service_{{ $i }}_title"
                                            value="{{ $settings['service_'.$i.'_title'] ?? '' }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="service_{{ $i }}_desc" rows="2">{{ $settings['service_'.$i.'_desc'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- ============== TAB 4: FAQ ============== --}}
            <div class="tab-pane fade" id="faq" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-question-circle-fill me-2 text-info"></i>FAQ</h5>
                    </div>
                    <div class="card-body">
                        @for($i = 1; $i <= 4; $i++)
                        <div class="card bg-light border-0 mb-3">
                            <div class="card-body">
                                <h6 class="fw-bold text-info mb-3">FAQ {{ $i }}</h6>
                                <div class="mb-3">
                                    <label class="form-label">Pertanyaan</label>
                                    <input type="text" class="form-control" name="faq_{{ $i }}_question"
                                        value="{{ $settings['faq_'.$i.'_question'] ?? '' }}">
                                </div>
                                <div>
                                    <label class="form-label">Jawaban</label>
                                    <textarea class="form-control" name="faq_{{ $i }}_answer" rows="3">{{ $settings['faq_'.$i.'_answer'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- ============== TAB 5: FOOTER ============== --}}
            <div class="tab-pane fade" id="footer" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-layout-text-window-reverse me-2 text-secondary"></i>Footer</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Perusahaan</label>
                                <input type="text" class="form-control" name="company_name"
                                    value="{{ $settings['company_name'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ $settings['email'] ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi Perusahaan</label>
                                <textarea class="form-control" name="company_description" rows="2">{{ $settings['company_description'] ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Alamat Pusat</label>
                                <textarea class="form-control" name="address_main" rows="3">{{ $settings['address_main'] ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Alamat Cabang</label>
                                <textarea class="form-control" name="address_branch" rows="3">{{ $settings['address_branch'] ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Telepon Pusat</label>
                                <input type="text" class="form-control" name="phone_main"
                                    value="{{ $settings['phone_main'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Telepon Cabang</label>
                                <input type="text" class="form-control" name="phone_branch"
                                    value="{{ $settings['phone_branch'] ?? '' }}">
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-share me-2"></i>Sosial Media (URL)</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-instagram me-1 text-danger"></i>Instagram</label>
                                <input type="url" class="form-control" name="social_instagram"
                                    value="{{ $settings['social_instagram'] ?? '' }}" placeholder="https://instagram.com/...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-facebook me-1 text-primary"></i>Facebook</label>
                                <input type="url" class="form-control" name="social_facebook"
                                    value="{{ $settings['social_facebook'] ?? '' }}" placeholder="https://facebook.com/...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-tiktok me-1"></i>TikTok</label>
                                <input type="url" class="form-control" name="social_tiktok"
                                    value="{{ $settings['social_tiktok'] ?? '' }}" placeholder="https://tiktok.com/@...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-youtube me-1 text-danger"></i>YouTube</label>
                                <input type="url" class="form-control" name="social_youtube"
                                    value="{{ $settings['social_youtube'] ?? '' }}" placeholder="https://youtube.com/...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-whatsapp me-1 text-success"></i>WhatsApp</label>
                                <input type="url" class="form-control" name="social_whatsapp"
                                    value="{{ $settings['social_whatsapp'] ?? '' }}" placeholder="https://wa.me/62...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="mt-4 mb-5">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-check-circle-fill me-2"></i>Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection
