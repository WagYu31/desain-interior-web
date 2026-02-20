@extends('admin.layouts.app')

@section('title', 'Detail User - ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar User
        </a>
    </div>

    {{-- User Info Card --}}
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-4">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                             alt="{{ $user->name }}" 
                             class="rounded-circle mb-3" 
                             width="100" height="100" 
                             style="object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 100px; height: 100px;">
                            <i class="bi bi-person-fill text-primary fs-1"></i>
                        </div>
                    @endif
                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    <span class="badge bg-success px-3 py-2">
                        <i class="bi bi-person-check me-1"></i>User
                    </span>
                    <hr>
                    <div class="text-start">
                        <p class="mb-2">
                            <i class="bi bi-calendar3 me-2 text-muted"></i>
                            <strong>Terdaftar:</strong> {{ $user->created_at->format('d M Y, H:i') }}
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-clock me-2 text-muted"></i>
                            <strong>Terakhir Update:</strong> {{ $user->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            {{-- Stats --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                        <div class="card-body text-center">
                            <h3 class="fw-bold text-primary mb-1">{{ $totalOrders }}</h3>
                            <p class="text-muted mb-0 small">Total Pesanan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                        <div class="card-body text-center">
                            <h3 class="fw-bold text-success mb-1">{{ $completedOrders }}</h3>
                            <p class="text-muted mb-0 small">Pesanan Selesai</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                        <div class="card-body text-center">
                            <h3 class="fw-bold text-warning mb-1">Rp {{ number_format($totalSpent, 0, ',', '.') }}</h3>
                            <p class="text-muted mb-0 small">Total Pembayaran</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Orders Table --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-journal-text me-2"></i>Riwayat Pesanan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">ID</th>
                                    <th>Tipe Desain</th>
                                    <th>Ruangan</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->orders as $order)
                                <tr>
                                    <td class="ps-3 fw-semibold">#{{ $order->user_order_id ?? $order->id }}</td>
                                    <td>{{ $order->design_type ?? '-' }}</td>
                                    <td>{{ $order->room_count ?? '-' }} Ruangan</td>
                                    <td>
                                        @php
                                            $status = $order->latestDetail?->status ?? 'pending';
                                            $badge = match($status) {
                                                'completed' => 'success',
                                                'in_progress' => 'primary',
                                                'cancelled' => 'danger',
                                                'pending' => 'warning',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badge }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                                    </td>
                                    <td><small class="text-muted">{{ $order->created_at->format('d M Y') }}</small></td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-journal-x fs-3 d-block mb-2"></i>
                                        Belum ada pesanan
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
</div>
@endsection
