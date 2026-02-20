@extends('admin.layouts.app')

@php
    $latestDetail = $order->latestDetail;
@endphp

@section('title', 'Detail Pesanan #' . $order->user_order_id)

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-receipt-cutoff me-2 text-primary"></i>
                Detail Pesanan #{{ $order->user_order_id }}
            </h1>
            @can('update', $order)
                <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-warning btn-sm shadow-sm">
                    <i class="bi bi-pencil-fill me-1"></i> Update Progress
                </a>
            @endcan
        </div>

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Manajemen Pemesanan</a></li>
                <li class="breadcrumb-item active">Detail Pesanan #{{ $order->user_order_id }}</li>
            </ol>
        </nav>

        {{-- ================= GRID UTAMA ================= --}}
        <div class="row g-4">

            {{-- ================================================= --}}
            {{-- KOLOM KIRI (lg-8)                                --}}
            {{-- ================================================= --}}
            <div class="col-lg-8">

                {{-- Detail Proyek --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="text-uppercase text-muted small mb-2">Detail Proyek</h6>
                    </div>
                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <p class="small text-muted mb-1">Tipe Klien</p>
                                <p class="fw-semibold">{{ $order->client_type }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <p class="small text-muted mb-1">Tipe Properti</p>
                                <p class="fw-semibold">{{ $order->property_type ?? 'N/A' }}</p>
                            </div>

                            @if ($order->client_type === 'Residensial')
                                <div class="col-md-6 mb-3">
                                    <p class="small text-muted mb-1">Tipe Desain</p>
                                    <p class="fw-semibold">
                                        {{ is_array($order->design_type) ? implode(', ', $order->design_type) : 'N/A' }}
                                    </p>
                                </div>

                                @if ($order->room_count)
                                    <div class="col-md-6 mb-3">
                                        <p class="small text-muted mb-1">Jumlah Ruangan</p>
                                        <p class="fw-semibold">{{ $order->room_count }}</p>
                                    </div>
                                @endif
                            @elseif ($order->client_type === 'Bisnis')
                                <div class="col-md-6 mb-3">
                                    <p class="small text-muted mb-1">Nama Perusahaan</p>
                                    <p class="fw-semibold">{{ $order->company_name ?? 'N/A' }}</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <p class="small text-muted mb-1">Kebutuhan Bisnis</p>
                                    <p class="fw-semibold">{{ $order->business_needs ?? 'N/A' }}</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <p class="small text-muted mb-1">Estimasi Nilai Proyek</p>
                                    <p class="fw-semibold">{{ $order->project_value ?? 'N/A' }}</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <p class="small text-muted mb-1">Luas Area</p>
                                    <p class="fw-semibold">{{ $order->area_size ?? 'N/A' }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- Alamat --}}
                        <h6 class="text-uppercase text-muted small mb-2">Alamat Proyek</h6>
                        <hr class="mt-1">
                        <p class="fw-semibold mb-1">{{ $order->full_address ?? 'N/A' }}</p>
                        <p class="text-muted mb-0">
                            {{ $order->district }}, {{ $order->city }}, {{ $order->province }}
                        </p>

                        {{-- Catatan --}}
                        @if ($order->notes)
                            <h6 class="text-uppercase text-muted small mt-4 mb-2">Catatan Klien</h6>
                            <hr class="mt-1">
                            <p class="text-muted fw-semibold ">{{ $order->notes }}</p>
                        @endif
                    </div>
                </div>

                {{-- Riwayat Progres --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Riwayat Progres Proyek</h6>
                    </div>
                    <div class="card-body">
                        @if ($order->details->isNotEmpty())
                            <div class="timeline">
                                @foreach ($order->details as $detail)
                                    <div class="timeline-item">
                                        <div class="timeline-marker"></div>
                                        <div class="timeline-content">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="badge bg-primary">
                                                    {{ Str::title(str_replace('_', ' ', $detail->status)) }}
                                                </span>
                                                <small class="text-muted">
                                                    {{ $detail->created_at->format('d M Y, H:i') }}
                                                </small>
                                            </div>

                                            @if ($detail->progress_description)
                                                <p class="bg-light p-3 rounded mb-2">
                                                    <em>{!! nl2br(e($detail->progress_description)) !!}</em>
                                                </p>
                                            @endif

                                            @if (!empty($detail->photos))
                                                <div class="row g-2">
                                                    @foreach ($detail->photos as $photo)
                                                        <div class="col-auto">
                                                            <img src="{{ asset('storage/' . $photo) }}"
                                                                class="img-fluid rounded border"
                                                                style="width:70px;height:70px;object-fit:cover">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-muted"><em>Belum ada progres.</em></p>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ================================================= --}}
            {{-- KOLOM KANAN (lg-4)                               --}}
            {{-- ================================================= --}}
            <div class="col-lg-4">

                {{-- Informasi Pesanan --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Informasi Pesanan</h6>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-1">Status</p>
                        @if ($latestDetail)
                            @php
                                $statusClass = match ($latestDetail->status) {
                                    'pending' => 'bg-warning text-dark',
                                    'in_progress' => 'bg-info text-dark',
                                    'completed' => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge rounded-pill {{ $statusClass }}">
                                {{ $latestDetail->translated_status }}
                            </span>
                        @else
                            <span class="badge bg-secondary mb-3">N/A</span>
                        @endif

                        <p class="small text-muted mb-1">Tanggal Pesan</p>
                        <p class="fw-semibold">{{ $order->order_date->format('d M Y, H:i') }}</p>

                        @if($order->deadline_date)
                        <p class="small text-muted mb-1">Deadline</p>
                        <p class="fw-semibold {{ $order->deadline_date->isPast() ? 'text-danger' : '' }}">
                            {{ $order->deadline_date->format('d M Y') }}
                            @if($order->deadline_date->isFuture())
                                <small class="text-muted">({{ $order->deadline_date->diffForHumans() }})</small>
                            @elseif($order->deadline_date->isPast())
                                <span class="badge bg-danger">Lewat Deadline</span>
                            @endif
                        </p>
                        @endif

                        <p class="small text-muted mb-1">Harga Final</p>
                        <p class="fw-bold text-success fs-5 mb-0">
                            {{ $latestDetail && $latestDetail->final_price ? 'Rp ' . number_format($latestDetail->final_price, 0, ',', '.') : 'Belum ditetapkan' }}
                        </p>

                        {{-- Budget Tracking Section --}}
                        @if($order->estimated_budget)
                        <hr>
                        <h6 class="text-uppercase text-muted small mb-2">
                            <i class="bi bi-wallet2 me-1"></i>Budget Tracking
                        </h6>
                        <p class="small text-muted mb-1">Estimasi Budget</p>
                        <p class="fw-semibold mb-2">Rp {{ number_format($order->estimated_budget, 0, ',', '.') }}</p>
                        
                        <p class="small text-muted mb-1">Terpakai</p>
                        <p class="fw-semibold mb-2">Rp {{ number_format($order->total_spent, 0, ',', '.') }}</p>
                        
                        <div class="progress mb-2" style="height: 10px;">
                            @php
                                $usagePercent = $order->budget_usage_percent ?? 0;
                                $progressClass = $usagePercent >= 100 ? 'bg-danger' : ($usagePercent >= 80 ? 'bg-warning' : 'bg-success');
                            @endphp
                            <div class="progress-bar {{ $progressClass }}" 
                                 role="progressbar" 
                                 style="width: {{ min($usagePercent, 100) }}%"
                                 aria-valuenow="{{ $usagePercent }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">{{ $usagePercent }}% terpakai</small>
                            @if($order->is_budget_warning)
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Perhatian
                                </span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Detail Klien --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0 text-gray-800">Detail Klien & Kontak</h6>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-1">Akun Pemesan</p>
                        <p class="fw-semibold">{{ $order->user->name ?? 'Pesanan Manual' }}</p>

                        <h6 class="text-uppercase text-muted small mt-3 mb-2">Kontak Proyek</h6>
                        <hr class="mt-1">
                        {{-- PERBAIKAN: Ambil semua detail kontak & alamat dari $latestDetail --}}
                        @if ($latestDetail)
                            <p class="small text-muted mb-1">Nama Kontak</p>
                            <p class="fw-semibold">{{ $order->contact_name }}</p>

                            <p class="small text-muted mb-1">No. Telepon</p>
                            <p class="fw-semibold">{{ $order->contact_phone }}</p>

                            <p class="small text-muted mb-1">Alamat Proyek</p>
                            <p class="fw-semibold mb-1">{{ $order->full_address }}</p>
                            <p class="text-muted mb-0">
                                {{ $order->district }}, {{ $order->city }}, {{ $order->province }}
                            </p>
                        @else
                            <p class="text-muted"><em>Informasi kontak belum tersedia.</em></p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
