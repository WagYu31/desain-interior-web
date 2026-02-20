<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderReminderNotification extends Notification
{
    use Queueable;

    protected Order $order;
    protected array $reasons;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, array $reasons)
    {
        $this->order = $order;
        $this->reasons = $reasons;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'order_reminder',
            'order_id' => $this->order->id,
            'user_order_id' => $this->order->user_order_id,
            'contact_name' => $this->order->contact_name,
            'reasons' => $this->reasons,
            'message' => 'Pesanan #' . $this->order->user_order_id . ' (' . $this->order->contact_name . ') perlu perhatian: ' . implode(', ', $this->reasons),
            'url' => route('admin.orders.show', $this->order),
        ];
    }
}
