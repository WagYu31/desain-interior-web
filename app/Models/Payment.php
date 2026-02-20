<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'transaction_id',
        'midtrans_order_id',
        'snap_token',
        'payment_type',
        'gross_amount',
        'status',
        'transaction_status',
        'payment_url',
        'midtrans_response',
        'paid_at',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'midtrans_response' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the order that owns the payment.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if payment is successful
     */
    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'success' => 'success',
            'pending' => 'warning',
            'failed' => 'danger',
            'expired' => 'secondary',
            'cancelled' => 'dark',
            default => 'secondary'
        };
    }

    /**
     * Get translated status
     */
    public function getTranslatedStatusAttribute(): string
    {
        return match($this->status) {
            'success' => 'Berhasil',
            'pending' => 'Menunggu Pembayaran',
            'failed' => 'Gagal',
            'expired' => 'Kadaluarsa',
            'cancelled' => 'Dibatalkan',
            default => $this->status
        };
    }
}
