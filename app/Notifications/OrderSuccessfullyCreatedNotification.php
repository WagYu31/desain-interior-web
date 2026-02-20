<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderSuccessfullyCreatedNotification extends Notification
{
    use Queueable;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toFcm($notifiable): array
    {
        // Ini adalah payload "data-only" untuk FCM
        return [
            'data' => [
                'title' => 'Pesanan Berhasil Dibuat!',
                'body' => 'Pesanan Anda #' . $this->order->user_order_id . ' telah kami terima dan akan segera diproses.',
                'icon' => asset('images/logopt.png'),
                'click_action' => route('user.orders.show', $this->order->id),
            ],
        ];
    }

    // Ini adalah data yang disimpan di database
    public function toArray($notifiable): array
    {
        return [
            'title' => 'Pesanan Berhasil Dibuat!',
            'message' => 'Pesanan Anda #' . $this->order->user_order_id . ' telah kami terima.',
            'link_url' => route('user.orders.show', $this->order->id),
        ];
    }
}