<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCancelledEvent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public Order $order;
    public int $adminId;

    public function __construct(Order $order, int $adminId)
    {
        $this->order = $order;
        $this->adminId = $adminId;
    }

    public function broadcastOn()
    {
        return [new PrivateChannel('App.Models.User.' . $this->adminId)];
    }

    public function broadcastAs()
    {
        return 'order-cancelled';
    }

    public function broadcastWith()
    {
        return [
            'order_id' => $this->order->id,
            'status' => 'cancelled',
            'cancelled_at' => now()->toDateTimeString(),
        ];
    }
}
