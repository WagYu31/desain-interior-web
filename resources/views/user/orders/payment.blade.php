@extends('layouts.app')

@section('title', 'Pembayaran - Order #' . $order->user_order_id)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Header --}}
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-credit-card-2-front text-primary" style="font-size: 2rem;"></i>
                </div>
                <h2 class="fw-bold">Pembayaran</h2>
                <p class="text-muted">Order #{{ $order->user_order_id }}</p>
            </div>

            {{-- Order Summary Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-receipt me-2 text-primary"></i>Ringkasan Pesanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted">Pelanggan</td>
                                <td class="text-end fw-medium">{{ $order->contact_name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Layanan</td>
                                <td class="text-end fw-medium">Jasa Desain Interior</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Kategori</td>
                                <td class="text-end fw-medium">{{ $order->category?->name ?? '-' }}</td>
                            </tr>
                            @if($order->room_count)
                            <tr>
                                <td class="text-muted">Jumlah Ruangan</td>
                                <td class="text-end fw-medium">{{ $order->room_count }}</td>
                            </tr>
                            @endif
                            <tr class="border-top">
                                <td class="text-muted pt-3"><strong>Total Pembayaran</strong></td>
                                <td class="text-end pt-3">
                                    <span class="h4 fw-bold text-primary mb-0">
                                        Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Payment Methods Info --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-wallet2 me-2 text-success"></i>Metode Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Pilih metode pembayaran yang Anda inginkan:</p>
                    <div class="row g-3">
                        <div class="col-4 col-md-2 text-center">
                            <div class="p-2 border rounded bg-light">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg" alt="GoPay" class="img-fluid" style="max-height: 30px;">
                            </div>
                        </div>
                        <div class="col-4 col-md-2 text-center">
                            <div class="p-2 border rounded bg-light">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/e/eb/Logo_ovo_purple.svg" alt="OVO" class="img-fluid" style="max-height: 30px;">
                            </div>
                        </div>
                        <div class="col-4 col-md-2 text-center">
                            <div class="p-2 border rounded bg-light">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg" alt="DANA" class="img-fluid" style="max-height: 30px;">
                            </div>
                        </div>
                        <div class="col-4 col-md-2 text-center">
                            <div class="p-2 border rounded bg-light">
                                <span class="small fw-bold text-primary">BCA VA</span>
                            </div>
                        </div>
                        <div class="col-4 col-md-2 text-center">
                            <div class="p-2 border rounded bg-light">
                                <span class="small fw-bold text-primary">BNI VA</span>
                            </div>
                        </div>
                        <div class="col-4 col-md-2 text-center">
                            <div class="p-2 border rounded bg-light">
                                <span class="small fw-bold text-primary">QRIS</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pay Button --}}
            <div class="d-grid gap-2">
                <button id="pay-button" class="btn btn-primary btn-lg py-3 fw-bold">
                    <i class="bi bi-lock-fill me-2"></i>Bayar Sekarang
                </button>
                <a href="{{ route('user.orders.show', $order) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Detail Order
                </a>
            </div>

            {{-- Security Info --}}
            <div class="text-center mt-4">
                <p class="text-muted small">
                    <i class="bi bi-shield-check me-1"></i>
                    Pembayaran aman & terenkripsi oleh Midtrans
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
<script>
    const payButton = document.getElementById('pay-button');
    
    payButton.addEventListener('click', function() {
        // Trigger snap popup
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                console.log('success', result);
                window.location.href = '{{ route("user.payment.finish") }}?order_id=' + result.order_id + '&transaction_status=settlement';
            },
            onPending: function(result) {
                console.log('pending', result);
                window.location.href = '{{ route("user.payment.finish") }}?order_id=' + result.order_id + '&transaction_status=pending';
            },
            onError: function(result) {
                console.log('error', result);
                alert('Pembayaran gagal. Silakan coba lagi.');
            },
            onClose: function() {
                console.log('customer closed the popup without finishing the payment');
            }
        });
    });
</script>
@endpush
