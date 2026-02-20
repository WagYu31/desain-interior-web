<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderProgressUpdatedNotification extends Notification
{
    use Queueable;

    public $order;
    public $latestDetail;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->latestDetail = $order->latestDetail;
    }

    public function via($notifiable)
    {
        return ['database', FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        return [
            'data'  => [
                'title' => 'Update Proyek #' . $this->order->user_order_id,
                'body'  => 'Status proyek Anda sekarang: ' . $this->latestDetail->translated_status,
                'icon' => asset('images/logopt.png'),
                'click_action' => route('user.orders.show', $this->order->id),
            ],
        ];
    }
    
    public function toArray($notifiable): array
    {
        return [
            'title' => 'Update Proyek #' . $this->order->user_order_id,
            // PERBAIKAN: Gunakan variabel status terbaru
            'message' => 'Status proyek Anda sekarang: ' . $this->latestDetail->translated_status,
            'link_url' => route('user.orders.show', $this->order->id),
        ];
    }
}