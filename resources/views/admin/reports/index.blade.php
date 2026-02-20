@extends('admin.layouts.app')
@section('title', 'Cetak Laporan Pemesanan')

@push('styles')
<style>
    .report-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border-radius: 16px;
        padding: 1.75rem 2rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .report-header::after {
        content: '';
        position: absolute;
        right: -30px; top: -30px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,.04);
    }
    .report-header h1 {
        color: #fff;
        font-size: 1.5rem;
        font-weight: 800;
        margin: 0;
    }
    .report-header p {
        color: rgba(255,255,255,.55);
        font-size: .85rem;
        margin: .25rem 0 0;
    }

    /* Form card */
    .report-card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }
    .report-card .card-header {
        background: #fff;
        border-bottom: 2px solid #f1f5f9;
        padding: 1.1rem 1.5rem;
    }

    /* Section titles */
    .section-title {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .6px;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: .75rem;
    }

    /* Report type selector */
    .report-type-group {
        display: flex;
        gap: .5rem;
    }
    .report-type-btn {
        flex: 1;
        text-align: center;
        padding: .75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        transition: all .2s ease;
        background: #fff;
    }
    .report-type-btn:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    .report-type-btn.active {
        border-color: #4361ee;
        background: #eef2ff;
    }
    .report-type-btn .type-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto .5rem;
        font-size: 1.1rem;
    }
    .report-type-btn .type-label {
        font-size: .8rem;
        font-weight: 700;
        color: #334155;
    }
    .report-type-btn .type-desc {
        font-size: .68rem;
        color: #94a3b8;
        margin-top: 2px;
    }
    .report-type-btn.active .type-label { color: #4361ee; }
    .report-type-btn input { display: none; }

    /* Format cards */
    .format-option {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        transition: all .2s ease;
        flex: 1;
    }
    .format-option:hover { border-color: #cbd5e1; }
    .format-option.active { border-color: #4361ee; background: #eef2ff; }
    .format-option .format-icon {
        width: 42px; height: 42px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .format-option .format-label {
        font-weight: 700;
        font-size: .85rem;
        color: #334155;
    }
    .format-option .format-desc {
        font-size: .7rem;
        color: #94a3b8;
    }
    .format-option.active .format-label { color: #4361ee; }
    .format-option input { display: none; }

    /* Filter inputs */
    .filter-input {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: .6rem .85rem;
        font-size: .85rem;
        transition: border-color .2s;
    }
    .filter-input:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67,97,238,.1);
    }
    .filter-label {
        font-size: .78rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: .4rem;
    }

    /* Generate button */
    .btn-generate {
        background: linear-gradient(135deg, #4361ee 0%, #3b82f6 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: .85rem 2rem;
        font-weight: 700;
        font-size: .9rem;
        transition: all .2s ease;
        box-shadow: 0 4px 12px rgba(67,97,238,.25);
    }
    .btn-generate:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(67,97,238,.35);
        color: #fff;
    }

    /* Divider */
    .section-divider {
        border: none;
        border-top: 2px solid #f1f5f9;
        margin: 1.5rem 0;
    }
</style>
@endpush

@section('content')
    {{-- Header --}}
    <div class="report-header d-flex align-items-center justify-content-between">
        <div>
            <h1><i class="bi bi-file-earmark-bar-graph-fill me-2" style="opacity:.7;"></i>Cetak Laporan</h1>
            <p>Generate laporan pemesanan dalam format PDF atau Excel</p>
        </div>
        <div>
            <span class="badge px-3 py-2" style="background:rgba(255,255,255,.1); color:rgba(255,255,255,.7); border-radius:10px; font-size:.78rem;">
                <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('d F Y') }}
            </span>
        </div>
    </div>

    <form action="{{ route('admin.reports.export') }}" method="GET" target="_blank">
        <div class="card report-card shadow-sm">
            <div class="card-header">
                <h6 class="fw-bold mb-0" style="color:#1e293b;">
                    <i class="bi bi-sliders me-2" style="color:#4361ee;"></i>Konfigurasi Laporan
                </h6>
            </div>
            <div class="card-body p-4">
                {{-- Step 1: Report Type --}}
                <div class="section-title"><i class="bi bi-1-circle-fill me-1"></i>Pilih Tipe Laporan</div>
                <div class="report-type-group mb-4">
                    <label class="report-type-btn" data-value="daily">
                        <input type="radio" name="report_type" value="daily">
                        <div class="type-icon" style="background:#fef3c7; color:#b45309;">
                            <i class="bi bi-calendar-day"></i>
                        </div>
                        <div class="type-label">Harian</div>
                        <div class="type-desc">Laporan per hari</div>
                    </label>
                    <label class="report-type-btn active" data-value="monthly">
                        <input type="radio" name="report_type" value="monthly" checked>
                        <div class="type-icon" style="background:#e0f2fe; color:#0284c7;">
                            <i class="bi bi-calendar-month"></i>
                        </div>
                        <div class="type-label">Bulanan</div>
                        <div class="type-desc">Laporan per bulan</div>
                    </label>
                    <label class="report-type-btn" data-value="yearly">
                        <input type="radio" name="report_type" value="yearly">
                        <div class="type-icon" style="background:#dcfce7; color:#16a34a;">
                            <i class="bi bi-calendar-range"></i>
                        </div>
                        <div class="type-label">Tahunan</div>
                        <div class="type-desc">Laporan per tahun</div>
                    </label>
                </div>

                {{-- Step 2: Filter --}}
                <div class="section-title"><i class="bi bi-2-circle-fill me-1"></i>Atur Periode</div>
                <div class="row mb-4">
                    <div class="col-md-8">
                        {{-- Daily --}}
                        <div id="daily_filter" class="filter-group">
                            <label class="filter-label">Pilih Tanggal</label>
                            <input type="date" name="date_start" class="form-control filter-input" value="{{ date('Y-m-d') }}">
                        </div>
                        {{-- Monthly --}}
                        <div id="monthly_filter" class="filter-group">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="filter-label">Bulan</label>
                                    <select name="month" class="form-select filter-input">
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="filter-label">Tahun</label>
                                    <input type="number" name="year_month" class="form-control filter-input year-input" value="{{ date('Y') }}">
                                </div>
                            </div>
                        </div>
                        {{-- Yearly --}}
                        <div id="yearly_filter" class="filter-group">
                            <label class="filter-label">Pilih Tahun</label>
                            <input type="number" name="year_year" class="form-control filter-input year-input" value="{{ date('Y') }}">
                        </div>
                    </div>
                </div>

                <hr class="section-divider">

                {{-- Step 3: Format --}}
                <div class="section-title"><i class="bi bi-3-circle-fill me-1"></i>Format Output</div>
                <div class="d-flex gap-3 mb-4" style="max-width:500px;">
                    <label class="format-option active">
                        <input type="radio" name="format" value="pdf" checked>
                        <div class="format-icon" style="background:#fee2e2; color:#dc2626;">
                            <i class="bi bi-filetype-pdf"></i>
                        </div>
                        <div>
                            <div class="format-label">PDF</div>
                            <div class="format-desc">Cetak siap print</div>
                        </div>
                    </label>
                    <label class="format-option">
                        <input type="radio" name="format" value="excel">
                        <div class="format-icon" style="background:#dcfce7; color:#16a34a;">
                            <i class="bi bi-filetype-xlsx"></i>
                        </div>
                        <div>
                            <div class="format-label">Excel</div>
                            <div class="format-desc">Spreadsheet data</div>
                        </div>
                    </label>
                </div>

                <hr class="section-divider">

                {{-- Submit --}}
                <input type="hidden" id="year" name="year" value="{{ date('Y') }}">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i>Laporan akan terbuka di tab baru
                    </div>
                    <button type="submit" class="btn btn-generate">
                        <i class="bi bi-download me-2"></i>Generate Laporan
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportTypeSelect = document.querySelectorAll('.report-type-btn');
    const yearInput = document.getElementById('year');
    const filterGroups = document.querySelectorAll('.filter-group');
    const yearInputs = document.querySelectorAll('.year-input');
    const formatOptions = document.querySelectorAll('.format-option');

    // Report type toggle
    function setReportType(type) {
        reportTypeSelect.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.value === type);
        });
        filterGroups.forEach(group => {
            group.style.display = group.id.startsWith(type) ? 'block' : 'none';
        });
        updateYear(type);
    }

    function updateYear(type) {
        if (!type) type = document.querySelector('.report-type-btn.active')?.dataset.value || 'monthly';
        if (type === 'monthly') {
            yearInput.value = document.querySelector('[name="year_month"]').value;
        } else if (type === 'yearly') {
            yearInput.value = document.querySelector('[name="year_year"]').value;
        }
    }

    reportTypeSelect.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.querySelector('input');
            input.checked = true;
            setReportType(this.dataset.value);
        });
    });

    yearInputs.forEach(input => input.addEventListener('input', () => updateYear()));

    // Format toggle
    formatOptions.forEach(opt => {
        opt.addEventListener('click', function() {
            formatOptions.forEach(o => o.classList.remove('active'));
            this.classList.add('active');
            this.querySelector('input').checked = true;
        });
    });

    // Init
    setReportType('monthly');
});
</script>
@endpush