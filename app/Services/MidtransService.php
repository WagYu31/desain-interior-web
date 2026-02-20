<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected string $serverKey;
    protected string $clientKey;
    protected string $apiUrl;
    protected bool $isProduction;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->clientKey = config('midtrans.client_key');
        $this->isProduction = config('midtrans.is_production');
        $this->apiUrl = config('midtrans.api_url');
    }

    /**
     * Create a Snap transaction and get token
     */
    public function createTransaction(Order $order, float $amount): ?array
    {
        $orderId = 'ORDER-' . $order->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $amount,
            ],
            'customer_details' => [
                'first_name' => $order->contact_name,
                'email' => $order->contact_email ?? $order->user?->email ?? 'customer@example.com',
                'phone' => $order->contact_phone,
            ],
            'item_details' => [
                [
                    'id' => 'DESAIN-' . $order->id,
                    'price' => (int) $amount,
                    'quantity' => 1,
                    'name' => 'Jasa Desain Interior - Order #' . $order->user_order_id,
                ]
            ],
            'callbacks' => [
                'finish' => route('user.payment.finish'),
            ],
        ];

        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->post($this->apiUrl . '/snap/v1/transactions', $params);

            if ($response->successful()) {
                $data = $response->json();
                
                // Create payment record
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'transaction_id' => $orderId,
                    'midtrans_order_id' => $orderId,
                    'snap_token' => $data['token'] ?? null,
                    'payment_url' => $data['redirect_url'] ?? null,
                    'gross_amount' => $amount,
                    'status' => 'pending',
                ]);

                return [
                    'success' => true,
                    'token' => $data['token'] ?? null,
                    'redirect_url' => $data['redirect_url'] ?? null,
                    'payment' => $payment,
                ];
            }

            Log::error('Midtrans API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal membuat transaksi pembayaran',
            ];

        } catch (\Exception $e) {
            Log::error('Midtrans Exception', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
            ];
        }
    }

    /**
     * Handle notification from Midtrans webhook
     */
    public function handleNotification(array $notification): bool
    {
        $transactionId = $notification['order_id'] ?? null;
        $transactionStatus = $notification['transaction_status'] ?? null;
        $fraudStatus = $notification['fraud_status'] ?? null;
        $paymentType = $notification['payment_type'] ?? null;

        if (!$transactionId) {
            return false;
        }

        $payment = Payment::where('transaction_id', $transactionId)->first();

        if (!$payment) {
            Log::warning('Payment not found for transaction', ['order_id' => $transactionId]);
            return false;
        }

        // Update payment status based on Midtrans status
        $status = $this->mapTransactionStatus($transactionStatus, $fraudStatus);
        
        $payment->update([
            'status' => $status,
            'payment_type' => $paymentType,
            'midtrans_response' => $notification,
            'paid_at' => $status === 'success' ? now() : null,
        ]);

        // Update order status if payment successful
        if ($status === 'success' && $payment->order) {
            $payment->order->latestDetail?->update([
                'status' => 'in_progress',
            ]);
        }

        return true;
    }

    /**
     * Map Midtrans transaction status to our status
     */
    protected function mapTransactionStatus(string $transactionStatus, ?string $fraudStatus): string
    {
        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                return 'success';
            }
            return 'pending';
        }

        return match ($transactionStatus) {
            'settlement' => 'success',
            'pending' => 'pending',
            'deny', 'cancel' => 'cancelled',
            'expire' => 'expired',
            'failure' => 'failed',
            default => 'pending',
        };
    }

    /**
     * Get transaction status from Midtrans
     */
    public function getTransactionStatus(string $transactionId): ?array
    {
        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->get($this->apiUrl . '/v2/' . $transactionId . '/status');

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Midtrans Status Check Error', [
                'transaction_id' => $transactionId,
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
