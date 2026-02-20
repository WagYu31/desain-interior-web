<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderCancelledByUserNotification extends Notification
{
    use Queueable;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Tentukan channel pengiriman.
     */
    public function via($notifiable): array
    {
        // PERBAIKAN: Kirim ke database DAN FCM
        return ['database', FcmChannel::class];
    }

    /**
     * Payload untuk FCM (push notification).
     */
    public function toFcm($notifiable): array
    {
        return [
            'data' => [
                'title' => 'Pesanan Dibatalkan',
                'body' => "Pesanan #{$this->order->id} telah dibatalkan oleh {$this->order->user->name}.",
                'icon' => asset('images/logopt.png'),
                'click_action' => route('admin.orders.show', $this->order->id),
            ],
        ];
    }

    /**
     * Data yang akan disimpan di database.
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => 'Pesanan Dibatalkan',
            'message' => "Pesanan #{$this->order->id} telah dibatalkan oleh {$this->order->user->name}.",
            'reason' => $this->order->cancellation_reason,
            'link_url' => route('admin.orders.show', $this->order->id),
        ];
    }
}