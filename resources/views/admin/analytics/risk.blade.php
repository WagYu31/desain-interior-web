@extends('admin.layouts.app')

@section('title', 'Analisis Risiko Proyek')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Analisis Risiko</li>
        </ol>
    </nav>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-graph-up-arrow me-2"></i>Analisis Risiko Proyek
        </h1>
        <button class="btn btn-sm btn-outline-primary" onclick="location.reload()">
            <i class="bi bi-arrow-clockwise me-1"></i>Refresh Data
        </button>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="bi bi-clipboard-data fs-4 text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase">Total Aktif</div>
                            <div class="h4 mb-0 fw-bold">{{ $summary['total_active'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 border-start border-danger border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                            <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase">Risiko Tinggi</div>
                            <div class="h4 mb-0 fw-bold text-danger">{{ $summary['high_risk'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="bi bi-exclamation-circle-fill fs-4 text-warning"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase">Risiko Sedang</div>
                            <div class="h4 mb-0 fw-bold text-warning">{{ $summary['medium_risk'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="bi bi-chat-left-text-fill fs-4 text-info"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase">Ada Kendala</div>
                            <div class="h4 mb-0 fw-bold text-info">{{ $summary['with_blockers'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="row mb-4">
        {{-- Risk Distribution Chart (Doughnut) --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-pie-chart-fill me-2 text-primary"></i>Distribusi Risiko
                    </h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div style="position: relative; width: 100%; max-width: 250px;">
                        <canvas id="riskDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Risk Factors Chart (Bar) --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-bar-chart-fill me-2 text-info"></i>Skor Risiko per Proyek
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="riskScoresChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Trend/Radar Chart --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-diagram-3-fill me-2 text-success"></i>Analisis Faktor
                    </h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div style="position: relative; width: 100%; max-width: 280px;">
                        <canvas id="factorsRadarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Daftar Proyek Aktif</h5>
        </div>
        <div class="card-body p-0">
            @if($ordersWithRisk->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-check-circle fs-1 text-success"></i>
                    <p class="mt-3 text-muted">Tidak ada proyek aktif saat ini.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Status</th>
                                <th class="text-center">Skor Risiko</th>
                                <th>Faktor Risiko</th>
                                <th>Kendala Terdeteksi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ordersWithRisk as $item)
                                @php
                                    $order = $item['order'];
                                    $riskLevel = $item['risk_level'];
                                    $riskBadgeClass = match($riskLevel) {
                                        'high' => 'bg-danger',
                                        'medium' => 'bg-warning text-dark',
                                        default => 'bg-success'
                                    };
                                    $riskLabel = match($riskLevel) {
                                        'high' => 'TINGGI',
                                        'medium' => 'SEDANG',
                                        default => 'RENDAH'
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <strong>#{{ $order->id }}</strong>
                                    </td>
                                    <td>
                                        <div>{{ $order->contact_name }}</div>
                                        <small class="text-muted">{{ $order->user?->email ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $order->latestDetail?->translated_status ?? 'N/A' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $riskBadgeClass }} fs-6">
                                            {{ $item['risk_score'] }} - {{ $riskLabel }}
                                        </span>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled mb-0 small">
                                            @foreach($item['risk_factors'] as $factor)
                                                <li>
                                                    <i class="bi bi-dot"></i>
                                                    <strong>{{ $factor['name'] }}:</strong> {{ $factor['description'] }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        @if($item['has_blockers'])
                                            @foreach($item['detected_issues'] as $issue)
                                                <span class="badge bg-danger-subtle text-danger border border-danger mb-1">
                                                    <i class="bi bi-flag-fill me-1"></i>{{ $issue }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-success"><i class="bi bi-check-circle"></i> Tidak ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Legend --}}
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Keterangan Algoritma</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Cara Perhitungan Skor Risiko:</h6>
                    <ul class="small">
                        <li><strong>Frekuensi Update (40%):</strong> Membandingkan jeda waktu update terakhir dengan rata-rata jeda sebelumnya.</li>
                        <li><strong>Durasi Status (40%):</strong> Membandingkan lama pengerjaan dengan estimasi waktu berdasarkan kompleksitas.</li>
                        <li><strong>Kompleksitas Proyek (20%):</strong> Berdasarkan jumlah ruangan dan jenis desain.</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>Deteksi Kendala (NLP):</h6>
                    <p class="small mb-2">Sistem mendeteksi kata kunci negatif dalam catatan progress:</p>
                    <div class="small">
                        <span class="badge bg-secondary me-1">hujan</span>
                        <span class="badge bg-secondary me-1">rusak</span>
                        <span class="badge bg-secondary me-1">habis</span>
                        <span class="badge bg-secondary me-1">tunggu</span>
                        <span class="badge bg-secondary me-1">delay</span>
                        <span class="badge bg-secondary me-1">kendala</span>
                        <span class="badge bg-secondary me-1">masalah</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data from backend
    const riskData = {
        high: {{ $summary['high_risk'] ?? 0 }},
        medium: {{ $summary['medium_risk'] ?? 0 }},
        low: {{ ($summary['total_active'] ?? 0) - ($summary['high_risk'] ?? 0) - ($summary['medium_risk'] ?? 0) }}
    };

    // Project data for bar chart
    const projectLabels = [
        @foreach($ordersWithRisk->take(8) as $item)
            '{{ Str::limit($item['order']->contact_name, 10) }}',
        @endforeach
    ];
    const projectScores = [
        @foreach($ordersWithRisk->take(8) as $item)
            {{ $item['risk_score'] }},
        @endforeach
    ];
    const projectColors = [
        @foreach($ordersWithRisk->take(8) as $item)
            '{{ $item['risk_level'] === 'high' ? 'rgba(220, 53, 69, 0.8)' : ($item['risk_level'] === 'medium' ? 'rgba(255, 193, 7, 0.8)' : 'rgba(25, 135, 84, 0.8)') }}',
        @endforeach
    ];

    // Average factors for radar chart
    @php
        $avgUpdate = 0;
        $avgDuration = 0;
        $avgComplexity = 0;
        $count = $ordersWithRisk->count();
        
        if ($count > 0) {
            foreach ($ordersWithRisk as $item) {
                foreach ($item['risk_factors'] as $factor) {
                    if (str_contains($factor['name'], 'Update')) {
                        $avgUpdate += $factor['value'] ?? 0;
                    } elseif (str_contains($factor['name'], 'Durasi')) {
                        $avgDuration += $factor['value'] ?? 0;
                    } elseif (str_contains($factor['name'], 'Kompleksitas')) {
                        $avgComplexity += $factor['value'] ?? 0;
                    }
                }
            }
            $avgUpdate = round($avgUpdate / $count, 2);
            $avgDuration = round($avgDuration / $count, 2);
            $avgComplexity = round($avgComplexity / $count, 2);
        }
    @endphp
    const radarData = {
        updateFrequency: {{ $avgUpdate }},
        duration: {{ $avgDuration }},
        complexity: {{ $avgComplexity }},
        blockers: {{ $summary['with_blockers'] ?? 0 }}
    };

    // 1. Risk Distribution Doughnut Chart
    const distributionCtx = document.getElementById('riskDistributionChart');
    if (distributionCtx) {
        new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Risiko Tinggi', 'Risiko Sedang', 'Risiko Rendah'],
                datasets: [{
                    data: [riskData.high, riskData.medium, riskData.low],
                    backgroundColor: [
                        'rgba(220, 53, 69, 0.85)',
                        'rgba(255, 193, 7, 0.85)',
                        'rgba(25, 135, 84, 0.85)'
                    ],
                    borderColor: [
                        'rgb(220, 53, 69)',
                        'rgb(255, 193, 7)',
                        'rgb(25, 135, 84)'
                    ],
                    borderWidth: 2,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((context.raw / total) * 100) : 0;
                                return `${context.label}: ${context.raw} proyek (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // 2. Risk Scores Bar Chart
    const scoresCtx = document.getElementById('riskScoresChart');
    if (scoresCtx && projectLabels.length > 0) {
        new Chart(scoresCtx, {
            type: 'bar',
            data: {
                labels: projectLabels,
                datasets: [{
                    label: 'Skor Risiko',
                    data: projectScores,
                    backgroundColor: projectColors,
                    borderColor: projectColors.map(c => c.replace('0.8', '1')),
                    borderWidth: 1,
                    borderRadius: 6,
                    barPercentage: 0.7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 3,
                        ticks: {
                            stepSize: 0.5
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const score = context.raw;
                                const level = score >= 2 ? 'TINGGI' : (score >= 1.2 ? 'SEDANG' : 'RENDAH');
                                return `Skor: ${score} (${level})`;
                            }
                        }
                    }
                }
            }
        });
    }

    // 3. Factors Radar Chart
    const radarCtx = document.getElementById('factorsRadarChart');
    if (radarCtx) {
        new Chart(radarCtx, {
            type: 'radar',
            data: {
                labels: ['Frekuensi Update', 'Durasi Status', 'Kompleksitas', 'Kendala NLP'],
                datasets: [{
                    label: 'Rata-rata Faktor',
                    data: [
                        radarData.updateFrequency,
                        radarData.duration,
                        radarData.complexity,
                        radarData.blockers
                    ],
                    fill: true,
                    backgroundColor: 'rgba(13, 110, 253, 0.2)',
                    borderColor: 'rgb(13, 110, 253)',
                    pointBackgroundColor: 'rgb(13, 110, 253)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgb(13, 110, 253)',
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    r: {
                        angleLines: {
                            color: 'rgba(0,0,0,0.1)'
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        },
                        pointLabels: {
                            font: {
                                size: 11
                            }
                        },
                        suggestedMin: 0,
                        suggestedMax: 3
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
@endpush
