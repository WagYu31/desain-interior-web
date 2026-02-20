<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerFeedback extends Model
{
    use HasFactory;

    protected $table = 'customer_feedbacks';

    protected $fillable = [
        'order_id',
        'user_id',
        'rating',
        'review',
        'would_recommend',
    ];

    protected $casts = [
        'would_recommend' => 'boolean',
    ];

    /**
     * Get the order this feedback belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the user who gave this feedback.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
