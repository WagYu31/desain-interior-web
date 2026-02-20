@extends('layouts.app')

@php
    $latestDetail = $order->latestDetail;
@endphp

@section('title', 'Detail Pesanan #' . $order->user_order_id)

@section('content')
    <div class="container py-5">
        <div class="col-lg-10 mx-auto">

            {{-- Breadcrumb Navigation --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-primary">
                    <li class="breadcrumb-item">
                        <a href="{{ route('user.dashboard') }}" class="text-primary">Dashboard Saya</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('user.orders.index') }}" class="text-primary">Pemesanan Saya</a>
                    </li>
                    <li class="breadcrumb-item active text-secondary" aria-current="page">
                        Detail Pesanan #{{ $order->user_order_id }}
                    </li>
                </ol>
            </nav>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-receipt-cutoff me-2"></i>
                        Detail Pesanan #{{ $order->user_order_id }}
                    </h5>
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
                </div>
                <div class="card-body p-4">
                    {{-- Detail Waktu --}}
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-md-4">
                            <p class="mb-1 text-muted small">Tanggal Pesan</p>
                            <p class="fw-semibold">{{ $order->order_date->format('d F Y, H:i') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted small">Tipe Klien</p>
                            <p class="fw-semibold">{{ $order->client_type }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted small">Terakhir Diperbarui</p>
                            <p class="fw-semibold">
                                {{ $latestDetail->created_at->diffForHumans() ?? $order->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    {{-- Detail dari Form --}}
                    <h6 class="fw-bold mb-3">Detail Permintaan Anda</h6>
                    <div class="row">
                        {{-- Bagian ini ditampilkan untuk SEMUA tipe pesanan --}}
                        <div class="col-md-6 mb-3">
                            <p class="small text-muted mb-0">Nama Kontak</p>
                            <p class="fw-semibold">{{ $order->contact_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="small text-muted mb-0">No. Telepon (WA)</p>
                            <p class="fw-semibold">{{ $order->contact_phone }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="small text-muted mb-0">Tipe Klien</p>
                            <p class="fw-semibold">{{ $order->client_type }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="small text-muted mb-0">Tipe Properti</p>
                            <p class="fw-semibold">{{ $order->property_type }}</p>
                        </div>
                        @if ($order->client_type === 'Residensial')
                            {{-- Tampilkan field ini HANYA JIKA tipe klien adalah Residensial --}}
                            <div class="col-md-6 mb-3">
                                <p class="small text-muted mb-0">Tipe Desain</p>
                                <p class="fw-semibold">
                                    @if (is_array($order->design_type) && !empty($order->design_type))
                                        {{ implode(', ', $order->design_type) }}
                                    @else
                                        {{ $order->design_type ?? 'N/A' }}
                                    @endif
                                </p>
                            </div>
                            @if ($order->room_count)
                                <div class="col-md-6 mb-3">
                                    <p class="small text-muted mb-0">Jumlah Ruangan</p>
                                    <p class="fw-semibold">{{ $order->room_count }}</p>
                                </div>
                            @endif
                        @elseif ($order->client_type === 'Bisnis')
                            {{-- Tampilkan field ini HANYA JIKA tipe klien adalah Bisnis --}}
                            @if ($order->company_name)
                                <div class="col-md-6 mb-3">
                                    <p class="small text-muted mb-0">Nama Perusahaan</p>
                                    <p class="fw-semibold">{{ $order->company_name }}</p>
                                </div>
                            @endif
                            @if ($order->business_needs)
                                <div class="col-md-6 mb-3">
                                    <p class="small text-muted mb-0">Kebutuhan Bisnis</p>
                                    <p class="fw-semibold">{{ $order->business_needs }}</p>
                                </div>
                            @endif
                            @if ($order->project_value)
                                <div class="col-md-6 mb-3">
                                    <p class="small text-muted mb-0">Estimasi Nilai Proyek</p>
                                    <p class="fw-semibold">{{ $order->project_value }}</p>
                                </div>
                            @endif
                            @if ($order->area_size)
                                <div class="col-md-6 mb-3">
                                    <p class="small text-muted mb-0">Luas Area</p>
                                    <p class="fw-semibold">{{ $order->area_size }}</p>
                                </div>
                            @endif
                        @endif

                        <div class="col-12">
                            <p class="small text-muted mb-0">Alamat Proyek</p>
                            {{-- PERBAIKAN: Ambil data alamat dari $latestDetail --}}
                            <p class="fw-semibold">
                                {{ $order->full_address ?? 'Alamat tidak tersedia' }}, {{ $order->district ?? '' }},
                                {{ $order->city ?? '' }}, {{ $order->province ?? '' }}
                            </p>
                        </div>

                        @if ($order->notes)
                            <div class="col-12 mt-2">
                                <p class="small text-muted mb-0">Catatan Tambahan</p>
                                <p class="fw-semibold"><em>{{ $order->notes }}</em></p>
                            </div>
                        @endif
                    </div>

                    {{-- Update Progress dari Admin --}}
                    <div class="mt-4">
                        <h5 class="fw-bold mb-3 text-primary">Riwayat Progres Proyek</h5>
                        @if ($order->details->isNotEmpty())
                            <div class="timeline">
                                {{-- Looping semua detail dari yang TERBARU ke yang terlama --}}
                                @foreach ($order->details as $detail)
                                    <div class="timeline-item">
                                        <div class="timeline-marker"></div>
                                        <div class="timeline-content">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-primary mb-1">
                                                    {{ Str::title(str_replace('_', ' ', $latestDetail->translated_status)) }}
                                                </span>
                                                <small
                                                    class="text-muted">{{ $detail->created_at->format('d M Y, H:i') }}</small>
                                            </div>

                                            {{-- Catatan Progress --}}
                                            @if ($detail->progress_description)
                                                <p class="mb-2 mt-1"><em>{!! nl2br(e($detail->progress_description)) !!}</em></p>
                                            @endif

                                            {{-- Foto Progress --}}
                                            @if (!empty($detail->photos))
                                                <div class="mb-2">
                                                    <h6 class="fw-bold small">Foto Progress:</h6>
                                                    <div class="row g-2">
                                                        @foreach ($detail->photos as $photoPath)
                                                            <div class="col-auto">
                                                                <a href="{{ asset('storage/' . $photoPath) }}"
                                                                    target="_blank">
                                                                    <img src="{{ asset('storage/' . $photoPath) }}"
                                                                        alt="Foto Progress" class="img-fluid rounded"
                                                                        style="height: 60px; width: 60px; object-fit: cover;">
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Tim yang Mengerjakan --}}
                                            @php $assignedTeam = $detail->assignedTeam(); @endphp
                                            @if ($assignedTeam->isNotEmpty())
                                                <div class="mb-2">
                                                    <h6 class="fw-bold small">Tim yang Ditugaskan:</h6>
                                                    <ul class="list-inline mb-0">
                                                        @foreach ($assignedTeam as $member)
                                                            <li class="list-inline-item">
                                                                <span
                                                                    class="badge bg-light text-dark">{{ $member->name }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted">
                                <p><em>Belum ada update progres dari admin.</em></p>
                            </div>
                        @endif
                    </div>


                    {{-- Harga Final (diambil dari detail terbaru) --}}
                    @if ($latestDetail && $latestDetail->final_price)
                        <div class="my-4">
                            <h6 class="fw-bold">Biaya Akhir Proyek:</h6>
                            <p class="fs-4 fw-bold text-success">
                                Rp {{ number_format($latestDetail->final_price, 0, ',', '.') }}
                            </p>
                            
                            {{-- Payment Section --}}
                            @php
                                $latestPayment = $order->payments()->latest()->first();
                                $hasPaidSuccessfully = $latestPayment && $latestPayment->status === 'success';
                            @endphp
                            
                            @if($hasPaidSuccessfully)
                                <div class="alert alert-success d-flex align-items-center" role="alert">
                                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                                    <div>
                                        <strong>Pembayaran Berhasil!</strong><br>
                                        <small>Dibayar pada {{ $latestPayment->paid_at->format('d M Y, H:i') }} via {{ ucfirst($latestPayment->payment_type ?? 'Midtrans') }}</small>
                                    </div>
                                </div>
                            @else
                                <div class="card border-warning bg-warning bg-opacity-10 mb-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                            <div>
                                                <h6 class="mb-1 text-warning">
                                                    <i class="bi bi-exclamation-circle me-1"></i>
                                                    @if($latestPayment && $latestPayment->status === 'pending')
                                                        Menunggu Pembayaran
                                                    @else
                                                        Pembayaran Belum Dilakukan
                                                    @endif
                                                </h6>
                                                <p class="mb-0 text-muted small">Silakan lakukan pembayaran untuk melanjutkan proses desain</p>
                                            </div>
                                            <a href="{{ route('user.orders.payment', ['order' => $order, 'amount' => $latestDetail->final_price]) }}" 
                                               class="btn btn-success btn-lg">
                                                <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Pesan Kontekstual Berdasarkan Status --}}
                    @if ($latestDetail)
                    <div class="mt-4">
                        @switch($latestDetail->status)
                            @case('pending')
                            @case('in_progress')
                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    Tim kami sedang memproses pesanan Anda. Anda akan menerima notifikasi jika ada pembaruan.
                                </div>
                            @break
                            @case('completed')
                                <div class="alert alert-success d-flex align-items-center" role="alert">
                                    Pemesanan Anda telah selesai! Terima kasih telah menggunakan layanan kami.
                                </div>
                            @break
                            @case('cancelled')
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <div>
                                        Pemesanan ini telah dibatalkan.
                                        @if ($order->cancellation_reason)
                                            <br><strong>Alasan:</strong> {{ $order->cancellation_reason }}
                                        @endif
                                    </div>
                                </div>
                            @break
                        @endswitch
                    </div>
                @endif

            </div>
            <div class="card-footer bg-white border-0 text-start py-3">
                <a href="{{ route('user.orders.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle-fill me-1"></i> Kembali ke Daftar Pesanan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
