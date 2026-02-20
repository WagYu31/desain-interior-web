@extends('admin.layouts.app')

@section('title', 'Manajemen Pemesanan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Pemesanan</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0">Daftar Pemesanan</h5>
        </div>
        <div class="card-body">
            @if ($orders->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-4">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 px-3">ID</th>
                                <th class="py-3">Info Kontak</th>
                                <th class="py-3">Detail Proyek</th>
                                <th class="py-3">Lokasi</th>
                                <th class="py-3">Tanggal</th>
                                <th class="py-3 text-center">Status</th>
                                <th class="py-3 text-end px-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-animated">
                            @foreach ($orders as $order)
                            @php $latestDetail = $order->latestDetail; @endphp
                                <tr>
                                    <td class="px-3"><strong>#{{ $order->id }}</strong></td>
                                    <td>
                                        <div>{{ $order->contact_name }}</div>
                                        <small class="text-muted">{{ $order->contact_phone }}</small>
                                    </td>
                                    <td>
                                        {{ $order->client_type }} - {{ $order->property_type }}
                                    </td>
                                    <td>{{ $order->city }}</td>
                                    <td>{{ $order->order_date ? $order->order_date->format('d M Y') : 'N/A' }}</td>
                                    <td class="text-center">
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
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td class="text-end px-3">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="btn btn-sm btn-outline-primary me-1" title="Lihat Detail">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        @can('update', $order)
                                            <a href="{{ route('admin.orders.edit', $order) }}"
                                                class="btn btn-sm btn-outline-warning" title="Update Progress">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($orders->hasPages())
                    <div class="card-footer bg-white border-0">{{ $orders->links() }}</div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                    <p class="lead">Tidak ada data pemesanan yang ditemukan.</p>
                    <a href="{{ route('admin.orders.create') }}" class="btn btn-primary mt-2">Buat Pesanan Pertama</a>
                </div>
            @endif
        </div>
    </div>
@endsection
