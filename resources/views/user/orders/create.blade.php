@extends('layouts.app')
@section('title', 'Mulai Proyek Desain Anda')

@section('content')
    <div class="container py-5">
        <div class="col-lg-8 mx-auto">

            <div class="text-center mb-5" data-aos="fade-up">
                <h1 class="display-5 text-primary fw-bold">Jadwalkan Konsultasi Interior Anda</h1>
                <p class="lead text-secondary">Isi data di bawah dan tim Astha Tunggal Makmur akan merespon dalam 1x24 jam!
                </p>
            </div>

            {{-- ================================================= --}}
            {{--           WRAPPER UNTUK FORM RESIDENSIAL            --}}
            {{-- ================================================= --}}
            <div id="residentialFormWrapper">
                <form id="ajaxOrderFormResidential" method="POST" action="{{ route('user.orders.store') }}"
                    class="material-form needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="client_type" value="Residensial">

                    <div class="form-section mb-5" data-aos="fade-up">
                        <h4 class="form-section-title text-primary mb-4">Detail Interior</h4>

                        {{-- Fieldset Tipe Properti & Interior --}}
                        <div class="row">
                            {{-- Tipe Properti --}}
                            <div class="col-md-6 mb-4">
                                <p class="form-label text-secondary">Tipe Properti <span class="text-danger">*</span></p>
                                <div class="btn-group-radio">
                                    <input type="radio" class="btn-check" name="property_type" id="prop_rumah"
                                        value="Rumah" required>
                                    <label class="btn btn-outline-primary" for="prop_rumah">Rumah</label>

                                    <input type="radio" class="btn-check" name="property_type" id="prop_apartemen"
                                        value="Apartemen" required>
                                    <label class="btn btn-outline-primary" for="prop_apartemen">Apartemen</label>

                                    <div class="invalid-feedback mt-2">Mohon pilih tipe properti.</div>
                                </div>
                            </div>

                            {{-- Tipe Interior (Bisa pilih lebih dari satu) --}}
                            <div class="col-md-6 mb-4">
                                <p class="form-label text-primary">Tipe Interior <span class="text-danger">*</span></p>
                                <div class="btn-group-checkbox">
                                    <input type="checkbox" class="btn-check" id="interior_desain" name="design_type[]"
                                        value="Desain Interior">
                                    <label class="btn btn-outline-primary" for="interior_desain">Desain Interior</label>

                                    <input type="checkbox" class="btn-check" id="interior_kitchen" name="design_type[]"
                                        value="Kitchen Set">
                                    <label class="btn btn-outline-primary" for="interior_kitchen">Kitchen Set</label>
                                </div>
                                <div class="invalid-feedback mt-2">Mohon pilih minimal satu tipe interior.</div>
                            </div>
                        </div>

                        {{-- Jumlah Ruangan --}}
                        <div id="roomCountWrapper">
                            <label for="roomCountSelect" class="form-label text-secondary">Jumlah Ruangan <span
                                    class="text-danger">*</span></label>
                            <select name="room_count" id="roomCountSelect" class="form-select form-select-lg text-secondary"
                                required>
                                <option value="" disabled selected>Pilih jumlah ruangan</option>
                                <option>1 Ruangan</option>
                                <option>2 Ruangan</option>
                                <option>3 Ruangan</option>
                                <option>4 Ruangan</option>
                                <option>5 Ruangan</option>
                                <option>Lebih dari 5 Ruangan</option>
                            </select>
                            <div class="invalid-feedback">Mohon pilih jumlah ruangan.</div>
                        </div>
                    </div>


                    <div class="form-section mb-5" data-aos="fade-up">
                        <h4 class="form-section-title text-primary mb-4">Informasi Kontak</h4>
                        {{-- Fieldset Nama & Telepon --}}
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="name" class="form-label text-secondary">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="name" name="name"
                                    class="form-control form-control-lg text-secondary" placeholder="Masukkan nama Anda"
                                    value="{{ Auth::user()->name ?? '' }}" required>
                                <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="phone" class="form-label text-secondary">No. Telepon (WhatsApp) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg material-input-group">
                                    <span class="input-group-text">+62</span>
                                    <input type="tel" id="phone" name="phone"
                                        class="form-control form-control-lg text-secondary" placeholder="812 XXX XXX"
                                        required pattern="[0-9]{9,13}">
                                </div>
                                <div class="invalid-feedback">Nomor telepon wajib diisi dan harus valid.</div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label text-secondary">Email (Opsional)</label>
                            <input type="email" id="email" name="email"
                                class="form-control form-control-lg text-secondary" placeholder="contoh@email.com"
                                value="{{ Auth::user()->email ?? '' }}">
                        </div>
                    </div>

                    <div class="form-section mb-5" data-aos="fade-up">
                        <h4 class="form-section-title text-primary mb-4">Informasi Alamat</h4>
                        {{-- Fieldset Alamat --}}
                        <div class="row">
                            <div class="col-md-4 mb-3"><label for="provinceSelectResidential"
                                    class="form-label text-secondary">Provinsi <span
                                        class="text-danger">*</span></label><select
                                    class="form-select form-select-lg text-secondary" name="province"
                                    id="provinceSelectResidential" required>
                                    <option value="">Memuat...</option>
                                </select>
                                <div class="invalid-feedback">Pilih provinsi.</div>
                            </div>
                            <div class="col-md-4 mb-3"><label for="citySelectResidential"
                                    class="form-label text-secondary">Kota/Kabupaten <span
                                        class="text-danger">*</span></label><select
                                    class="form-select form-select-lg text-secondary" name="city"
                                    id="citySelectResidential" required disabled>
                                    <option value="">Pilih provinsi dahulu</option>
                                </select>
                                <div class="invalid-feedback">Pilih kota/kab.</div>
                            </div>
                            <div class="col-md-4 mb-3"><label for="dsitrictSelectResidential"
                                    class="form-label text-secondary">Kecamatan <span
                                        class="text-danger">*</span></label><select
                                    class="form-select form-select-lg text-secondary" name="district"
                                    id="districtSelectResidential" required disabled>
                                    <option value="">Pilih kota/kab dahulu</option>
                                </select>
                                <div class="invalid-feedback">Pilih kecamatan.</div>
                            </div>
                        </div>
                        <div class="mb-3"><label for="address" class="form-label text-secondary">Alamat Lengkap <span
                                    class="text-danger">*</span></label><input type="text" id="address"
                                name="address" class="form-control form-control-lg text-secondary"
                                placeholder="Nama jalan, nomor rumah, RT/RW" required>
                            <div class="invalid-feedback">Alamat wajib diisi.</div>
                        </div>
                        <div class="mb-3"><label for="notes" class="form-label text-secondary">Catatan Tambahan
                                (Opsional)</label>
                            <textarea class="form-control form-control-lg text-secondary" id="notes" name="notes" rows="2"
                                placeholder="Contoh: Patokan dekat masjid"></textarea>
                        </div>
                    </div>

                    <div class="text-center mt-4"><button type="submit" id="submitResidential"
                            class="btn btn-primary btn-lg rounded-pill px-5 py-3">Kirim Permintaan</button></div>
                </form>
                <p class="text-center mt-4 text-primary"><small>Mau desain untuk keperluan bisnis? <a href="#"
                            id="switchToBusiness" class="fw-bold text-secondary">Klik di sini</a></small></p>
            </div>

            {{-- =============================================== --}}
            {{--           WRAPPER UNTUK FORM BISNIS             --}}
            {{-- =============================================== --}}
            <div id="businessFormWrapper" style="display: none;">
                <form id="ajaxOrderFormBusiness" method="POST" action="{{ route('user.orders.store') }}"
                    class="material-form needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="client_type" value="Bisnis">

                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-light text-center py-3">
                            <h4 class="mb-0">Formulir Kebutuhan Bisnis</h4>
                        </div>

                        <div class="card-body p-4 p-md-5">
                            {{-- ... (Business Needs & Property Type tidak berubah) ... --}}
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-primary">Business Needs <span
                                            class="text-danger">*</span></label>
                                    <select name="business_needs" required
                                        class="form-select form-select-lg text-primary">
                                        <option value="">Pilih Keperluan</option>
                                        <option>Design & Build</option>
                                        <option>Design</option>
                                        <option>Build</option>
                                    </select>
                                    <div class="invalid-feedback">Pilih keperluan bisnis.</div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-primary">Property Type <span
                                            class="text-danger">*</span></label>
                                    <select name="property_type" required class="form-select form-select-lg text-primary">
                                        <option value="">Pilih Jenis</option>
                                        <option>Office</option>
                                        <option>Ritel</option>
                                        <option>F&B</option>
                                        <option>Hotel</option>
                                    </select>
                                    <div class="invalid-feedback">Pilih jenis properti.</div>
                                </div>
                            </div>

                            {{-- ... (Company Information tidak berubah) ... --}}
                            <div class="mb-4">
                                <label class="form-label text-primary">Company Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="company_name"
                                    class="form-control form-control-lg text-primary" required
                                    placeholder="Masukkan nama perusahaan">
                                <div class="invalid-feedback">Nama perusahaan wajib diisi.</div>
                            </div>

                            {{-- ... (PIC Info) ... --}}
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-primary">PIC Name <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg material-input-group">
                                        <span class="input-group-text bg-light text-primary"><i
                                                class="bi bi-person"></i></span>
                                        <input type="text" name="name"
                                            class="form-control form-control-lg text-primary"
                                            value="{{ Auth::user()->name ?? '' }}" placeholder="Nama penanggung jawab"
                                            required>
                                    </div>
                                    <div class="invalid-feedback">Nama PIC wajib diisi.</div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-primary">PIC Phone <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg material-input-group">
                                        <span class="input-group-text">+62</span>
                                        {{-- PERBAIKAN 1: Mengubah id --}}
                                        <input type="tel" id="phone-business" name="phone"
                                            class="form-control form-control-lg text-primary" placeholder="812 XXX XXX"
                                            required pattern="[0-9]{9,13}">
                                    </div>
                                    <div class="invalid-feedback">Nomor telepon wajib diisi dan harus valid.</div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-primary">PIC Email <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg material-input-group">
                                    <span class="input-group-text bg-light text-primary"><i
                                            class="bi bi-envelope"></i></span>
                                    <input type="email" name="email"
                                        class="form-control form-control-lg text-primary"
                                        value="{{ Auth::user()->email ?? '' }}" placeholder="contoh@email.com" required>
                                </div>
                                <div class="invalid-feedback">Email wajib diisi.</div>
                            </div>

                            {{-- ... (Address) ... --}}
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="provinceSelectBusiness" class="form-label text-primary">Provinsi <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg text-primary" name="province"
                                        id="provinceSelectBusiness" required>
                                        <option value="">Memuat...</option>
                                    </select>
                                    <div class="invalid-feedback">Pilih provinsi.</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="citySelectBusiness" class="form-label text-primary">Kota/Kabupaten <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg text-primary" name="city"
                                        id="citySelectBusiness" required disabled>
                                        <option value="">Pilih provinsi dulu</option>
                                    </select>
                                    <div class="invalid-feedback">Pilih kota/kabupaten.</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="districtSelectBusiness" class="form-label text-primary">Kecamatan <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg text-primary" name="district"
                                        id="districtSelectBusiness" required disabled>
                                        <option value="">Pilih kota/kab dulu</option>
                                    </select>
                                    <div class="invalid-feedback">Pilih kecamatan.</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                {{-- PERBAIKAN 2: Mengubah for dan id --}}
                                <label for="address-business" class="form-label text-primary">Alamat Lengkap <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg material-input-group">
                                    <span class="input-group-text bg-light text-primary"><i
                                            class="bi bi-geo-alt"></i></span>
                                    <input type="text" id="address-business" name="address"
                                        class="form-control form-control-lg text-primary"
                                        placeholder="Nama jalan, nomor, RT/RW" required>
                                </div>
                                <div class="invalid-feedback">Alamat wajib diisi.</div>
                            </div>
                            <div class="mb-3">
                                {{-- PERBAIKAN 3: Mengubah for dan id --}}
                                <label for="notes-business" class="form-label text-primary">Catatan Tambahan
                                    (Opsional)</label>
                                <textarea class="form-control form-control-lg text-primary" id="notes-business" name="notes" rows="2"
                                    placeholder="Contoh: Patokan dekat masjid atau gedung tertentu"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-primary">Project Value<span
                                            class="text-danger">*</span></label>
                                    <select name="project_value" required class="form-select form-select-lg text-primary">
                                        <option value="">Pilih Jumlah Biaya</option>
                                        <option>100-200 Juta</option>
                                        <option>200-500 Juta</option>
                                        <option>Lebih dari 500 Juta</option>
                                    </select>
                                    <div class="invalid-feedback">Pilih.</div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-primary">Area Size<span
                                            class="text-danger">*</span></label>
                                    <select name="area_size" required class="form-select form-select-lg text-primary">
                                        <option value="">Pilih Jenis</option>
                                        <option>100m²</option>
                                        <option>100-200m²</option>
                                        <option>Lebih dari 200m²</option>
                                    </select>
                                    <div class="invalid-feedback">Pilih.</div>
                                </div>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" id="submitBusiness"
                                    class="btn btn-primary btn-lg rounded-pill py-3">
                                    Kirim Permintaan Bisnis
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <p class="text-center mt-4 text-primary">
                    <small>
                        Ini untuk keperluan pribadi?
                        <a href="#" id="switchToResidential" class="fw-bold text-secondary">Kembali ke form
                            sebelumnya</a>
                    </small>
                </p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const apiBaseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

            // Wrapper Forms
            const residentialWrapper = document.getElementById('residentialFormWrapper');
            const businessWrapper = document.getElementById('businessFormWrapper');

            // Link untuk beralih form
            const switchToBusinessLink = document.getElementById('switchToBusiness');
            const switchToResidentialLink = document.getElementById('switchToResidential');

            // Fungsi bantuan untuk mengisi dropdown
            function populateSelect(selectElement, data, placeholder) {
                selectElement.innerHTML = `<option value="">${placeholder}</option>`;
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.name; // Kirim nama, bukan ID
                    option.textContent = item.name;
                    option.dataset.id = item.id;
                    selectElement.appendChild(option);
                });
            }

            // Fungsi bantuan untuk submit AJAX
            const handleAjaxSubmit = (form, button) => {
                const checkboxes = form.querySelectorAll('input[name="design_type[]"]');
                if (checkboxes.length > 0) {
                    const invalidFeedback = form.querySelector('.btn-group-checkbox + .invalid-feedback');
                    const isChecked = Array.from(checkboxes).some(cb => cb.checked);

                    if (!isChecked) {
                        event.preventDefault();
                        event.stopPropagation();
                        form.classList.add('was-validated');
                        invalidFeedback.style.display = 'block';
                        return;
                    } else {
                        if (invalidFeedback) invalidFeedback.style.display = 'none';
                    }
                }
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    form.classList.add('was-validated');
                    const firstInvalid = form.querySelector(':invalid');
                    if (firstInvalid) firstInvalid.focus();
                    return;
                }

                form.classList.remove('was-validated');
                event.preventDefault();

                button.disabled = true;
                button.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Mengirim...`;

                fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.ok ? response.json() : response.json().then(err => Promise
                        .reject(err)))
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message, 'Berhasil!');
                            if (data.whatsapp_url) setTimeout(() => window.open(data.whatsapp_url,
                                '_blank'), 1000);
                            setTimeout(() => window.location.href = "{{ route('user.orders.index') }}",
                                3000);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        let errorMessage = 'Gagal mengirim permintaan. Periksa koneksi Anda.';

                        // Jika server mengembalikan error validasi (status 422)
                        if (error.errors) {
                            // Ambil pesan error validasi pertama yang muncul
                            errorMessage = Object.values(error.errors)[0][0];
                        }

                        toastr.error(errorMessage, 'Terjadi Kesalahan!');
                        button.disabled = false;
                        button.innerHTML = form.id.includes('Residential') ? 'Kirim Permintaan' :
                            'Kirim Permintaan Bisnis';
                    });
            };

            function initializeRegionAPI(provinceId, cityId, districtId) {
                const provinceSelect = document.getElementById(provinceId);
                const citySelect = document.getElementById(cityId);
                const districtSelect = document.getElementById(districtId);

                if (!provinceSelect || !citySelect || !districtSelect) return;

                // 1. Load Provinsi
                fetch(`${apiBaseUrl}/provinces.json`).then(res => res.json()).then(provinces => {
                    populateSelect(provinceSelect, provinces, 'Pilih Provinsi');
                });

                // 2. Event listener Provinsi
                provinceSelect.addEventListener('change', function() {
                    const provinceApiId = this.selectedOptions[0]?.dataset.id;
                    citySelect.innerHTML = '<option value="">Memuat...</option>';
                    citySelect.disabled = true;
                    districtSelect.innerHTML = '<option value="">Pilih kota dulu</option>';
                    districtSelect.disabled = true;
                    if (provinceApiId) {
                        fetch(`${apiBaseUrl}/regencies/${provinceApiId}.json`).then(res => res.json()).then(
                            cities => {
                                populateSelect(citySelect, cities, 'Pilih Kota/Kabupaten');
                                citySelect.disabled = false;
                            });
                    }
                });

                // 3. Event listener Kota/Kab
                citySelect.addEventListener('change', function() {
                    const cityApiId = this.selectedOptions[0]?.dataset.id;
                    districtSelect.innerHTML = '<option value="">Memuat...</option>';
                    districtSelect.disabled = true;
                    if (cityApiId) {
                        fetch(`${apiBaseUrl}/districts/${cityApiId}.json`).then(res => res.json()).then(
                            districts => {
                                populateSelect(districtSelect, districts, 'Pilih Kecamatan');
                                districtSelect.disabled = false;
                            });
                    }
                });
            }


            // ==========================================================
            // 🎭 LOGIKA BERALIH ANTAR FORM
            // ==========================================================
            if (switchToBusinessLink && switchToResidentialLink) {
                switchToBusinessLink.addEventListener('click', e => {
                    e.preventDefault();
                    residentialWrapper.style.display = 'none';
                    businessWrapper.style.display = 'block';
                });
                switchToResidentialLink.addEventListener('click', e => {
                    e.preventDefault();
                    businessWrapper.style.display = 'none';
                    residentialWrapper.style.display = 'block';
                });
            }

            // ==========================================================
            // 🏠 LOGIKA FORM RESIDENSIAL
            // ==========================================================
            const formResidential = document.getElementById('ajaxOrderFormResidential');
            if (formResidential) {
                const provinceSelect = document.getElementById('provinceSelect');
                const citySelect = document.getElementById('citySelect');
                const districtSelect = document.getElementById('districtSelect');
                const designCheckboxes = document.querySelectorAll('input[name="design_type[]"]');
                const roomWrapper = document.getElementById('roomCountWrapper');

                // Submit form
                formResidential.addEventListener('submit', (e) => handleAjaxSubmit(formResidential, document
                    .getElementById('submitResidential')));

                // API Wilayah
                initializeRegionAPI('provinceSelectResidential', 'citySelectResidential',
                    'districtSelectResidential');

                designCheckboxes.forEach(checkbox => {
                    const label = checkbox.nextElementSibling;
                    checkbox.addEventListener('change', function() {
                        if (!label) return;
                        if (this.checked) {
                            label.classList.add('btn-primary', 'text-white');
                            label.classList.remove('btn-outline-primary');
                        } else {
                            label.classList.remove('btn-primary', 'text-white');
                            label.classList.add('btn-outline-primary');
                        }
                    });
                });
            }

            // ==========================================================
            // 🏢 LOGIKA FORM BISNIS
            // ==========================================================
            const formBusiness = document.getElementById('ajaxOrderFormBusiness');
            if (formBusiness) {
                // API Wilayah untuk form bisnis
                initializeRegionAPI('provinceSelectBusiness', 'citySelectBusiness', 'districtSelectBusiness');

                // Submit form
                formBusiness.addEventListener('submit', (e) => handleAjaxSubmit(formBusiness, document
                    .getElementById('submitBusiness')));
            }

        });
    </script>
@endpush
