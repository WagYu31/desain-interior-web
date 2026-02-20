<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Show payment page for an order
     */
    public function create(Order $order, Request $request)
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Get amount from request or use a default/calculated amount
        $amount = $request->input('amount', 500000); // Default 500k or calculate based on order

        // Check if there's already a pending payment
        $pendingPayment = $order->payments()->where('status', 'pending')->latest()->first();

        if ($pendingPayment && $pendingPayment->snap_token) {
            return view('user.orders.payment', [
                'order' => $order,
                'payment' => $pendingPayment,
                'snapToken' => $pendingPayment->snap_token,
                'clientKey' => config('midtrans.client_key'),
                'snapUrl' => config('midtrans.snap_url'),
            ]);
        }

        // Create new transaction
        $result = $this->midtransService->createTransaction($order, $amount);

        if (!$result['success']) {
            return back()->with('error', $result['message'] ?? 'Gagal membuat pembayaran');
        }

        return view('user.orders.payment', [
            'order' => $order,
            'payment' => $result['payment'],
            'snapToken' => $result['token'],
            'clientKey' => config('midtrans.client_key'),
            'snapUrl' => config('midtrans.snap_url'),
        ]);
    }

    /**
     * Handle Midtrans webhook notification
     */
    public function notification(Request $request)
    {
        // Verify signature
        $notification = $request->all();
        
        // Log for debugging
        Log::info('Midtrans Notification Received', $notification);

        // Verify signature key
        $serverKey = config('midtrans.server_key');
        $orderId = $notification['order_id'] ?? '';
        $statusCode = $notification['status_code'] ?? '';
        $grossAmount = $notification['gross_amount'] ?? '';
        $signatureKey = $notification['signature_key'] ?? '';

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            Log::warning('Invalid Midtrans Signature', [
                'expected' => $expectedSignature,
                'received' => $signatureKey,
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Process notification
        $this->midtransService->handleNotification($notification);

        return response()->json(['message' => 'OK']);
    }

    /**
     * Handle finish redirect from Midtrans
     */
    public function finish(Request $request)
    {
        $orderId = $request->input('order_id');
        $transactionStatus = $request->input('transaction_status');

        if ($orderId) {
            // Extract actual order ID from Midtrans order_id format
            preg_match('/ORDER-(\d+)-/', $orderId, $matches);
            $actualOrderId = $matches[1] ?? null;

            if ($actualOrderId) {
                $order = Order::find($actualOrderId);
                
                if ($order && $order->user_id === Auth::id()) {
                    // Update payment status based on transaction status
                    // Search by both midtrans_order_id and transaction_id for compatibility
                    $payment = Payment::where('midtrans_order_id', $orderId)
                        ->orWhere('transaction_id', $orderId)
                        ->first();
                    
                    // If no payment found by order_id, get the latest payment for this order
                    if (!$payment) {
                        $payment = Payment::where('order_id', $order->id)
                            ->latest()
                            ->first();
                    }
                    
                    if ($payment) {
                        $newStatus = match($transactionStatus) {
                            'settlement', 'capture' => 'success',
                            'pending' => 'pending',
                            'deny', 'cancel', 'expire' => 'failed',
                            default => $payment->status,
                        };
                        
                        $payment->update([
                            'status' => $newStatus,
                            'transaction_status' => $transactionStatus,
                            'midtrans_order_id' => $orderId,
                            'paid_at' => in_array($transactionStatus, ['settlement', 'capture']) ? now() : null,
                        ]);
                    }
                    
                    $message = match($transactionStatus) {
                        'settlement', 'capture' => 'Pembayaran berhasil! Terima kasih.',
                        'pending' => 'Pembayaran sedang diproses. Silakan selesaikan pembayaran Anda.',
                        'deny', 'cancel', 'expire' => 'Pembayaran dibatalkan atau gagal.',
                        default => 'Status pembayaran: ' . $transactionStatus,
                    };

                    return redirect()
                        ->route('user.orders.show', $order)
                        ->with('payment_status', $transactionStatus)
                        ->with('message', $message);
                }
            }
        }

        return redirect()->route('user.dashboard')->with('message', 'Proses pembayaran selesai.');
    }

    /**
     * Show payment history for an order
     */
    public function history(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $payments = $order->payments()->latest()->get();

        return view('user.orders.payment-history', [
            'order' => $order,
            'payments' => $payments,
        ]);
    }
}
