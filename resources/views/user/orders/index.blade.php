@extends('layouts.app')
@section('title', 'Riwayat Pemesanan Saya')

@section('content')
    <div class="container py-5">
        <div class="col-12 mx-auto">

            {{-- Breadcrumb Navigation --}}
            <nav aria-label="breadcrumb" data-aos="fade-up">
                <ol class="breadcrumb text-primary">
                    <li class="breadcrumb-item">
                        <a href="{{ route('user.dashboard') }}" class="text-primary">Dashboard Saya</a>
                    </li>
                    <li class="breadcrumb-item active text-secondary" aria-current="page">Pemesanan Saya</li>
                </ol>
            </nav>

            {{-- Header Page --}}
            <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up" data-aos-delay="50">
                <div>
                    <h1 class="h3 mb-1 fw-bold text-primary">Riwayat Pesanan</h1>
                    <p class="text-secondary mb-0">Pantau semua status proyek Anda di sini.</p>
                </div>
                <a href="{{ route('user.orders.create') }}" class="btn btn-outline-primary">
                    <i class="bi bi-plus-circle-fill me-1"></i>Buat Pesanan
                </a>
            </div>

            @include('layouts.partials.alerts')

            {{-- Card utama dengan style konsisten --}}
            <div class="card shadow-lg border-0 card-glass w-100" data-aos="fade-up" data-aos-delay="100">
                <div class="card-header bg-transparent border-bottom border-light-subtle d-flex align-items-center">
                    <h5 class="mb-0 text-primary"><i class="bi bi-journal-text me-2"></i>Daftar Pemesanan</h5>
                </div>

                <div class="card-body p-0">
                    @if ($orders->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 w-100 text-primary">
                                <thead class="bg-transparent border-bottom border-light-subtle">
                                    <tr class="text-secondary">
                                        <th class="py-3 px-3">ID</th>
                                        <th class="py-3">Tanggal Pesan</th>
                                        <th class="py-3">Tipe Proyek</th>
                                        <th class="py-3">Lokasi</th>
                                        <th class="py-3 text-center">Status</th>
                                        <th class="py-3 text-end px-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                    @php $latestDetail = $order->latestDetail; @endphp
                                        <tr>
                                            <td class="px-3"><strong>#{{ $order->user_order_id }}</strong></td>
                                            <td>{{ $order->order_date->format('d M Y') }}</td>
                                            <td>
                                                {{ $order->client_type }} - {{ $order->property_type }}
                                            </td>
                                            <td>{{ $order->city ?? 'N/A' }}</td>
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
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{ route('user.orders.show', $order) }}"
                                                        class="btn btn-outline-primary btn-sm" title="Lihat Detail">
                                                        <i class="bi bi-eye-fill"></i> Detail
                                                    </a>
                                                    @if ($latestDetail && $latestDetail->status == 'pending')
                                                        <button type="button" class="btn btn-outline-warning btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#cancelOrderModal"
                                                            data-order-id="{{ $order->id }}" title="Batalkan Pesanan">
                                                            <i class="bi bi-x-circle-fill"></i> Batalkan
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Footer --}}
                        @if ($orders->hasPages())
                            <div
                                class="card-footer bg-transparent border-top border-light-subtle d-flex justify-content-center pt-3">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-journal-x fs-1 text-primary"></i>
                            <p class="lead mt-2 text-secondary">Anda belum memiliki riwayat pemesanan.</p>
                            <a href="{{ route('user.orders.create') }}" class="btn btn-primary btn-lg mt-2">
                                Mulai Pemesanan Pertama Anda
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Pembatalan --}}
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true"
        data-base-action="{{ route('user.orders.cancel', ['order' => ':orderId']) }}">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelOrderModalLabel">Konfirmasi Pembatalan Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="cancelOrderForm" method="POST" action="">
                    @csrf
                    @method('POST')
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat diurungkan.</p>
                        <div class="mb-3">
                            <label for="cancellation_reason" class="form-label">
                                <strong>Alasan Pembatalan <span class="text-danger">*</span></strong>
                            </label>
                            <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="4"
                                placeholder="Contoh: Saya salah memasukkan detail kebutuhan..." required minlength="10"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger">Ya, Batalkan Pesanan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cancelOrderModal = document.getElementById('cancelOrderModal');
            if (cancelOrderModal) {
                const baseActionUrl = cancelOrderModal.dataset.baseAction;
                cancelOrderModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const orderId = button.getAttribute('data-order-id');
                    const actionUrl = baseActionUrl.replace(':orderId', orderId);
                    const cancelForm = document.getElementById('cancelOrderForm');
                    cancelForm.action = actionUrl;
                    document.getElementById('cancellation_reason').value = '';
                });
            }
        });
    </script>
@endpush
