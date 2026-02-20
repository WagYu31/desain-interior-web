@extends('layouts.app')

@section('title', 'Dashboard Saya')

@section('content')
    <div class="container py-5">
        {{-- Header Sambutan --}}
        <div class="text-center text-primary mb-5" data-aos="fade-up">
            <h1 class="display-4 fw-bold">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p class="lead text-secondary">Di sini Anda dapat memantau semua aktivitas dan progres proyek Anda.</p>
        </div>

        <div class="row g-4 justify-content-center">

            {{-- Kolom Kiri: Ringkasan Pesanan (Format Tabel Baru) --}}
            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                <div class="card shadow-lg card-glass">
                    <div
                        class="card-header bg-transparent border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary"><i class="bi bi-journal-check me-2"></i>Daftar Pesanan
                        </h5>
                        <a href="{{ route('user.orders.create') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-plus-circle-fill me-1"></i>Buat Pesanan
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if ($orders->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 w-100 text-primary">
                                    <thead class="bg-transparent border-bottom border-light-subtle">
                                        <tr class="text-secondary">
                                            {{-- PERBAIKAN: Menambahkan text-center ke header --}}
                                            <th class="py-3 px-3 text-center">ID</th>
                                            <th class="py-3 text-center">Tanggal Pesan</th>
                                            <th class="py-3 text-center">Tipe Proyek</th>
                                            <th class="py-3 text-center">Status</th>
                                            <th class="py-3 px-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            @php $latestDetail = $order->latestDetail; @endphp
                                            <tr>
                                                <td class="px-3 text-center"><strong>#{{ $order->user_order_id }}</strong>
                                                </td>
                                                <td class="text-center">{{ $order->order_date->format('d M Y') }}</td>
                                                <td class="text-center">{{ $order->client_type }} -
                                                    {{ $order->property_type }}</td>
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
                                                <td class="text-center px-3">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <a href="{{ route('user.orders.show', $order) }}"
                                                            class="btn btn-outline-primary btn-sm" title="Lihat Detail">
                                                            <i class="bi bi-eye-fill"></i> Detail
                                                        </a>
                                                        @if ($latestDetail && $latestDetail->status == 'pending')
                                                            <button type="button" class="btn btn-outline-warning btn-sm"
                                                                data-bs-toggle="modal" data-bs-target="#cancelOrderModal"
                                                                data-order-id="{{ $order->id }}"
                                                                title="Batalkan Pesanan">
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
                    @if ($orders->isNotEmpty())
                        <div class="card-footer bg-transparent border-top border-light-subtle text-center">
                            <a href="{{ route('user.orders.index') }}" class="btn btn-primary">Lihat Semua Pemesanan</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Kolom Kanan: Detail Akun (Tidak Berubah) --}}
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card shadow-lg border-0 card-glass h-100">
                    <div class="card-header bg-primary">
                        <h5 class="mb-0 text-white"><i class="bi bi-person-circle me-2"></i>Akun Saya</h5>
                    </div>
                    <div class="card-body d-flex flex-column text-primary">
                        <div class="text-center mb-3">
                            <i class="bi bi-person-circle fs-1"></i>
                            <h4 class="mt-2 mb-0">{{ Auth::user()->name }}</h4>
                            <small>{{ Auth::user()->email }}</small>
                        </div>
                        <ul class="list-group list-group-flush flex-grow-1">
                            <li class="list-group-item bg-transparent px-0">
                                <i class="bi bi-calendar-plus-fill me-2"></i> Bergabung pada:
                                <span class="fw-semibold float-end">{{ Auth::user()->created_at->format('d M Y') }}</span>
                            </li>
                            <li class="list-group-item bg-transparent px-0">
                                <i class="bi bi-journal-text me-2"></i> Total Pesanan:
                                <span class="fw-semibold float-end">{{ Auth::user()->orders()->count() }}</span>
                            </li>
                        </ul>
                        <div class="mt-auto pt-3">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary w-100 mb-2">Edit Profil &
                                Password</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====================================================== --}}
    {{-- PENAMBAHAN BARU: MODAL KONFIRMASI PEMBATALAN            --}}
    {{-- ====================================================== --}}
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
    {{-- ====================================================== --}}
    {{-- PENAMBAHAN BARU: SCRIPT UNTUK MODAL PEMBATALAN          --}}
    {{-- ====================================================== --}}
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
