@extends('admin.layouts.app')

@section('title', Auth::user()->hasRole('arsitek') ? 'Dashboard Arsitek' : 'Admin Dashboard')

@section('content')
    <div class="container-fluid">
        {{-- Header dengan Greeting --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold text-gray-800">
                    @php
                        $hour = now()->hour;
                        $greeting = $hour < 12 ? 'Selamat Pagi' : ($hour < 17 ? 'Selamat Siang' : 'Selamat Malam');
                    @endphp
                    {{ $greeting }}, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <div class="d-none d-sm-block">
                <a href="{{ route('admin.analytics.risk') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-graph-up-arrow me-1"></i>Lihat Analytics
                </a>
            </div>
        </div>

        {{-- Baris Kartu Statistik Premium --}}
        <div class="row g-3 mb-4">

            <!-- Total Proyek Card -->
            <div class="col-xl col-md-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden stat-card">
                    <div class="card-body d-flex flex-column p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 140px;">
                        <div class="d-flex justify-content-between align-items-start flex-grow-1">
                            <div>
                                <p class="text-white text-uppercase small fw-bold mb-2 opacity-75">Total Proyek</p>
                                <h2 class="text-white mb-0 fw-bold display-6">{{ $totalProjects }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                <i class="bi bi-briefcase-fill fs-4 text-white"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-2 border-top border-white border-opacity-25">
                            <a href="{{ route('admin.projects.index') }}" class="text-white text-decoration-none small fw-medium">
                                Lihat semua <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('owner') || Auth::user()->hasRole('arsitek'))
            <!-- Total Pemesanan Card -->
            <div class="col-xl col-md-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden stat-card">
                    <div class="card-body d-flex flex-column p-4" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); min-height: 140px;">
                        <div class="d-flex justify-content-between align-items-start flex-grow-1">
                            <div>
                                <p class="text-white text-uppercase small fw-bold mb-2 opacity-75">Total Pesanan</p>
                                <h2 class="text-white mb-0 fw-bold display-6">{{ $totalOrders }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                <i class="bi bi-cart-check-fill fs-4 text-white"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-2 border-top border-white border-opacity-25">
                            <a href="{{ route('admin.orders.index') }}" class="text-white text-decoration-none small fw-medium">
                                Lihat semua <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pemesanan Pending Card -->
            <div class="col-xl col-md-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden stat-card">
                    <div class="card-body d-flex flex-column p-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); min-height: 140px;">
                        <div class="d-flex justify-content-between align-items-start flex-grow-1">
                            <div>
                                <p class="text-white text-uppercase small fw-bold mb-2 opacity-75">Pending</p>
                                <h2 class="text-white mb-0 fw-bold display-6">{{ $pendingOrders }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                <i class="bi bi-hourglass-split fs-4 text-white"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-2 border-top border-white border-opacity-25">
                            <span class="text-white small fw-medium opacity-75">
                                <i class="bi bi-exclamation-circle me-1"></i>Butuh perhatian
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('owner'))
            <!-- Total Pengguna Card -->
            <div class="col-xl col-md-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden stat-card">
                    <div class="card-body d-flex flex-column p-4" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); min-height: 140px;">
                        <div class="d-flex justify-content-between align-items-start flex-grow-1">
                            <div>
                                <p class="text-white text-uppercase small fw-bold mb-2 opacity-75">Pengguna</p>
                                <h2 class="text-white mb-0 fw-bold display-6">{{ $totalUsers }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                <i class="bi bi-people-fill fs-4 text-white"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-2 border-top border-white border-opacity-25">
                            <span class="text-white small fw-medium opacity-75">
                                <i class="bi bi-person-check me-1"></i>Total klien
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Anggota Tim Card -->
            <div class="col-xl col-md-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden stat-card">
                    <div class="card-body d-flex flex-column p-4" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); min-height: 140px;">
                        <div class="d-flex justify-content-between align-items-start flex-grow-1">
                            <div>
                                <p class="text-white text-uppercase small fw-bold mb-2 opacity-75">Tim</p>
                                <h2 class="text-white mb-0 fw-bold display-6">{{ $totalTeamMembers }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                <i class="bi bi-person-badge-fill fs-4 text-white"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-2 border-top border-white border-opacity-25">
                            <a href="{{ route('admin.team-members.index') }}" class="text-white text-decoration-none small fw-medium">
                                Kelola tim <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Risk Analytics Alert Section (Admin/Owner Only) --}}
        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('owner'))
        @if(isset($riskSummary) && ($riskSummary['high_risk'] > 0 || $riskSummary['with_blockers'] > 0))
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-body p-0">
                        <div class="d-flex align-items-stretch">
                            <div class="{{ $riskSummary['high_risk'] > 0 ? 'bg-danger' : 'bg-warning' }} d-flex align-items-center justify-content-center px-4">
                                <i class="bi bi-exclamation-triangle-fill fs-1 text-white"></i>
                            </div>
                            <div class="flex-grow-1 p-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="fw-bold mb-2 {{ $riskSummary['high_risk'] > 0 ? 'text-danger' : 'text-warning' }}">
                                            <i class="bi bi-graph-up-arrow me-2"></i>Peringatan Analisis Risiko
                                        </h5>
                                        <p class="mb-0 text-muted">
                                            @if($riskSummary['high_risk'] > 0)
                                                <span class="badge bg-danger me-2">{{ $riskSummary['high_risk'] }} High Risk</span>
                                            @endif
                                            @if($riskSummary['with_blockers'] > 0)
                                                <span class="badge bg-info">{{ $riskSummary['with_blockers'] }} Ada Kendala</span>
                                            @endif
                                            <span class="ms-2">Proyek membutuhkan perhatian segera.</span>
                                        </p>
                                    </div>
                                    <a href="{{ route('admin.analytics.risk') }}" class="btn btn-primary shadow-sm">
                                        <i class="bi bi-arrow-right me-1"></i>Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif

        {{-- Baris Konten Utama --}}
        <div class="row g-4">
            {{-- Chart Section --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="bi bi-pie-chart-fill me-2"></i>Statistik Pesanan
                            </h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <canvas id="orderStatusChart" height="250"></canvas>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">In Progress</span>
                                        <span class="fw-bold">{{ $inProgressOrders ?? 0 }}</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary" style="width: {{ $totalOrders > 0 ? (($inProgressOrders ?? 0) / $totalOrders * 100) : 0 }}%"></div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Pending</span>
                                        <span class="fw-bold">{{ $pendingOrders }}</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" style="width: {{ $totalOrders > 0 ? ($pendingOrders / $totalOrders * 100) : 0 }}%"></div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Selesai</span>
                                        <span class="fw-bold">{{ $completedOrders ?? 0 }}</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" style="width: {{ $totalOrders > 0 ? (($completedOrders ?? 0) / $totalOrders * 100) : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Aksi Cepat --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-0">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="bi bi-lightning-charge-fill me-2"></i>Aksi Cepat
                        </h6>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center gap-3">
                        <a href="{{ route('admin.projects.create') }}" class="btn btn-lg btn-primary shadow-sm d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-plus-circle me-2"></i>Tambah Proyek</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('owner') || Auth::user()->hasRole('arsitek'))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-lg btn-outline-primary d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-journal-text me-2"></i>Kelola Pesanan</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        @endif
                        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('owner'))
                        <a href="{{ route('admin.team-members.create') }}" class="btn btn-lg btn-outline-secondary d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-person-plus-fill me-2"></i>Tambah Anggota Tim</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="{{ route('admin.analytics.team-performance') }}" class="btn btn-lg btn-outline-info d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-people-fill me-2"></i>Performa Tim</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Orders Section --}}
        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('owner') || Auth::user()->hasRole('arsitek'))
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="bi bi-clock-history me-2"></i>Pesanan Terbaru
                            </h6>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">ID</th>
                                        <th>Pelanggan</th>
                                        <th>Ruangan</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentOrders ?? collect() as $order)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-bold text-primary">#{{ $order->user_order_id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                                    <i class="bi bi-person text-primary"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $order->contact_name }}</strong>
                                                    <br><small class="text-muted">{{ $order->user?->email ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $order->room_count }} ruangan</td>
                                        <td>
                                            @php
                                                $status = $order->latestDetail?->status ?? 'pending';
                                                $statusClass = match($status) {
                                                    'completed' => 'success',
                                                    'in_progress' => 'primary',
                                                    'cancelled' => 'danger',
                                                    default => 'warning'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border border-{{ $statusClass }}">
                                                {{ $order->latestDetail?->translated_status ?? 'Pending' }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $order->order_date->diffForHumans() }}</small>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            Belum ada pesanan terbaru
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('orderStatusChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['In Progress', 'Pending', 'Selesai'],
                datasets: [{
                    data: [
                        {{ $inProgressOrders ?? 0 }},
                        {{ $pendingOrders }},
                        {{ $completedOrders ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.85)',
                        'rgba(255, 193, 7, 0.85)',
                        'rgba(25, 135, 84, 0.85)'
                    ],
                    borderColor: [
                        'rgb(102, 126, 234)',
                        'rgb(255, 193, 7)',
                        'rgb(25, 135, 84)'
                    ],
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
