@extends('admin.layouts.app')

@section('title', 'Performa Tim')

@push('styles')
<style>
    /* ===== Summary Cards ===== */
    .perf-card {
        border: none;
        border-radius: 16px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
        position: relative;
    }
    .perf-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,.10) !important;
    }
    .perf-card .card-body { position: relative; z-index: 2; }
    .perf-card .perf-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
    }
    .perf-card .perf-value {
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1.1;
    }
    .perf-card .perf-label {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .6px;
        text-transform: uppercase;
    }
    .perf-card .perf-bg-shape {
        position: absolute;
        right: -20px; bottom: -20px;
        width: 110px; height: 110px;
        border-radius: 50%;
        opacity: .08;
        z-index: 1;
    }

    /* Card color themes */
    .perf-card.card-team     { background: linear-gradient(135deg, #f0f4ff 0%, #e0eaff 100%); }
    .perf-card.card-team     .perf-icon { background: #4361ee; color: #fff; }
    .perf-card.card-team     .perf-value { color: #4361ee; }
    .perf-card.card-team     .perf-bg-shape { background: #4361ee; }

    .perf-card.card-active   { background: linear-gradient(135deg, #e8fdf5 0%, #d0f5e8 100%); }
    .perf-card.card-active   .perf-icon { background: #0ea770; color: #fff; }
    .perf-card.card-active   .perf-value { color: #0ea770; }
    .perf-card.card-active   .perf-bg-shape { background: #0ea770; }

    .perf-card.card-done     { background: linear-gradient(135deg, #fff4e6 0%, #ffe8cc 100%); }
    .perf-card.card-done     .perf-icon { background: #e8890c; color: #fff; }
    .perf-card.card-done     .perf-value { color: #e8890c; }
    .perf-card.card-done     .perf-bg-shape { background: #e8890c; }

    .perf-card.card-top      { background: linear-gradient(135deg, #fef3f2 0%, #fde1de 100%); }
    .perf-card.card-top      .perf-icon { background: #e04f5f; color: #fff; }
    .perf-card.card-top      .perf-value { color: #e04f5f; }
    .perf-card.card-top      .perf-bg-shape { background: #e04f5f; }

    /* ===== Table ===== */
    .perf-table-card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }
    .perf-table-card .card-header {
        background: #fff;
        border-bottom: 2px solid #f0f2f5;
        padding: 1.1rem 1.5rem;
    }
    .perf-table thead th {
        background: #f8f9fc;
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
        color: #6c7293;
        padding: .85rem 1rem;
        border: none;
    }
    .perf-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #f2f3f7;
        vertical-align: middle;
    }
    .perf-table tbody tr {
        transition: background .15s ease;
    }
    .perf-table tbody tr:hover {
        background: #f8faff;
    }
    .perf-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Member avatar */
    .member-avatar {
        width: 42px; height: 42px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700;
        font-size: .85rem;
        color: #fff;
        flex-shrink: 0;
    }

    /* Position badge */
    .badge-position {
        padding: .35em .75em;
        font-size: .7rem;
        font-weight: 600;
        border-radius: 8px;
        background: #eef0f6;
        color: #555b7a;
    }

    /* Metric badges */
    .metric-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: .3em .7em;
        border-radius: 8px;
        font-size: .78rem;
        font-weight: 700;
    }
    .metric-pill.pill-active { background: #e0f5ed; color: #0ea770; }
    .metric-pill.pill-done   { background: #e4edff; color: #4361ee; }

    /* Stars */
    .star-rating .bi-star-fill { color: #f5a623; font-size: .85rem; }
    .star-rating .bi-star      { color: #ddd;     font-size: .85rem; }

    /* On-Time Rate bar */
    .ontime-bar {
        width: 64px; height: 6px;
        border-radius: 3px;
        background: #eee;
        overflow: hidden;
        display: inline-block;
        vertical-align: middle;
    }
    .ontime-bar .fill {
        height: 100%;
        border-radius: 3px;
        transition: width .5s ease;
    }

    /* Duration chip */
    .duration-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: .25em .65em;
        border-radius: 8px;
        font-size: .78rem;
        font-weight: 600;
        background: #f0f2ff;
        color: #4361ee;
    }

    /* Legend card */
    .legend-card {
        border: none;
        border-radius: 16px;
        background: #f8f9fc;
    }
    .legend-item {
        padding: .6rem 0;
    }
    .legend-item .legend-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold" style="color:#1e293b;">
                    <i class="bi bi-people-fill me-2" style="color:#4361ee;"></i>Performa Tim
                </h1>
                <p class="text-muted mb-0 small">Pantau kinerja dan statistik seluruh anggota tim Anda</p>
            </div>
            <div>
                <span class="badge bg-light text-dark border px-3 py-2" style="border-radius:10px;">
                    <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('d F Y') }}
                </span>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">
            {{-- Total Anggota --}}
            <div class="col-xl-3 col-md-6">
                <div class="card perf-card card-team shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="perf-icon me-3">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <div class="perf-label text-muted mb-1">Total Anggota</div>
                                <div class="perf-value">{{ $summary['total_team_members'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="perf-bg-shape"></div>
                </div>
            </div>

            {{-- Proyek Aktif --}}
            <div class="col-xl-3 col-md-6">
                <div class="card perf-card card-active shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="perf-icon me-3">
                                <i class="bi bi-hammer"></i>
                            </div>
                            <div>
                                <div class="perf-label text-muted mb-1">Proyek Aktif</div>
                                <div class="perf-value">{{ $summary['total_active_projects'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="perf-bg-shape"></div>
                </div>
            </div>

            {{-- Proyek Selesai --}}
            <div class="col-xl-3 col-md-6">
                <div class="card perf-card card-done shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="perf-icon me-3">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div>
                                <div class="perf-label text-muted mb-1">Proyek Selesai</div>
                                <div class="perf-value">{{ $summary['total_completed_projects'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="perf-bg-shape"></div>
                </div>
            </div>

            {{-- Top Performer --}}
            <div class="col-xl-3 col-md-6">
                <div class="card perf-card card-top shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="perf-icon me-3">
                                <i class="bi bi-trophy-fill"></i>
                            </div>
                            <div>
                                <div class="perf-label text-muted mb-1">Top Performer</div>
                                <div class="fw-bold" style="font-size:1.05rem; color:#e04f5f;">
                                    {{ $summary['top_performer']['member']->name ?? 'N/A' }}
                                </div>
                                @if(isset($summary['top_performer']['completed']))
                                    <small class="text-muted">{{ $summary['top_performer']['completed'] }} proyek selesai</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="perf-bg-shape"></div>
                </div>
            </div>
        </div>

        {{-- Performance Table --}}
        <div class="card perf-table-card shadow-sm mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0" style="color:#1e293b;">
                    <i class="bi bi-bar-chart-line-fill me-2" style="color:#4361ee;"></i>Statistik Performa Per Anggota
                </h6>
                <span class="text-muted small">{{ count($teamPerformance) }} anggota</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table perf-table mb-0">
                        <thead>
                            <tr>
                                <th style="min-width:220px;">Anggota Tim</th>
                                <th class="text-center">Posisi</th>
                                <th class="text-center">Proyek Aktif</th>
                                <th class="text-center">Selesai</th>
                                <th class="text-center">Rata-rata Durasi</th>
                                <th class="text-center">Rating</th>
                                <th class="text-center">On-Time Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $avatarColors = ['#4361ee','#0ea770','#e8890c','#e04f5f','#7c3aed','#0891b2','#c026d3','#16a34a','#ea580c','#6366f1'];
                            @endphp
                            @forelse($teamPerformance as $index => $perf)
                            <tr>
                                {{-- Member --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="member-avatar me-3" style="background:{{ $avatarColors[$index % count($avatarColors)] }};">
                                            {{ strtoupper(substr($perf['member']->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold" style="color:#1e293b;">{{ $perf['member']->name }}</div>
                                            <small class="text-muted">{{ $perf['member']->phone ?? $perf['member']->email ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>

                                {{-- Position --}}
                                <td class="text-center">
                                    <span class="badge-position">{{ $perf['member']->position ?? 'N/A' }}</span>
                                </td>

                                {{-- Active Projects --}}
                                <td class="text-center">
                                    <span class="metric-pill pill-active">
                                        <i class="bi bi-lightning-fill" style="font-size:.7rem;"></i>
                                        {{ $perf['in_progress_projects'] }}
                                    </span>
                                </td>

                                {{-- Completed --}}
                                <td class="text-center">
                                    <span class="metric-pill pill-done">
                                        <i class="bi bi-check2" style="font-size:.8rem;"></i>
                                        {{ $perf['completed_projects'] }}
                                    </span>
                                </td>

                                {{-- Avg Duration --}}
                                <td class="text-center">
                                    @if($perf['avg_completion_days'])
                                        <span class="duration-chip">
                                            <i class="bi bi-clock" style="font-size:.7rem;"></i>
                                            {{ $perf['avg_completion_days'] }} hari
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Rating --}}
                                <td class="text-center">
                                    @if($perf['avg_rating'])
                                        <div class="star-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= round($perf['avg_rating']))
                                                    <i class="bi bi-star-fill"></i>
                                                @else
                                                    <i class="bi bi-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-muted" style="font-size:.7rem;">{{ $perf['avg_rating'] }} · {{ $perf['total_feedbacks'] }} ulasan</small>
                                    @else
                                        <span class="text-muted small">Belum ada</span>
                                    @endif
                                </td>

                                {{-- On-Time Rate --}}
                                <td class="text-center">
                                    @if($perf['on_time_rate'] !== null)
                                        @php
                                            $rate = $perf['on_time_rate'];
                                            $barColor = $rate >= 80 ? '#0ea770' : ($rate >= 50 ? '#e8890c' : '#e04f5f');
                                        @endphp
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="fw-bold" style="font-size:.85rem; color:{{ $barColor }};">{{ $rate }}%</span>
                                            <div class="ontime-bar mt-1">
                                                <div class="fill" style="width:{{ $rate }}%; background:{{ $barColor }};"></div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div style="color:#aab0c6;">
                                        <i class="bi bi-inbox" style="font-size:2.5rem;"></i>
                                        <p class="mt-2 mb-0 fw-medium">Belum ada data performa tim</p>
                                        <small>Data akan muncul setelah anggota tim ditugaskan ke proyek</small>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Legend --}}
        <div class="card legend-card shadow-sm">
            <div class="card-body py-3 px-4">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-info-circle-fill me-2" style="color:#4361ee;"></i>
                    <h6 class="fw-bold mb-0" style="font-size:.85rem; color:#1e293b;">Keterangan Metrik</h6>
                </div>
                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="legend-item d-flex align-items-start">
                            <span class="legend-dot mt-1" style="background:#4361ee;"></span>
                            <div>
                                <div class="small fw-semibold" style="color:#1e293b;">Rata-rata Durasi</div>
                                <div class="small text-muted">Rata-rata hari pengerjaan dari "In Progress" sampai "Selesai"</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="legend-item d-flex align-items-start">
                            <span class="legend-dot mt-1" style="background:#f5a623;"></span>
                            <div>
                                <div class="small fw-semibold" style="color:#1e293b;">Rating</div>
                                <div class="small text-muted">Rata-rata rating bintang dari feedback klien (1-5)</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="legend-item d-flex align-items-start">
                            <span class="legend-dot mt-1" style="background:#0ea770;"></span>
                            <div>
                                <div class="small fw-semibold" style="color:#1e293b;">On-Time Rate</div>
                                <div class="small text-muted">Persentase proyek selesai sebelum atau tepat deadline</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
