<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $property_type
 * @property string $design_type
 * @property string|null $room_count
 * @property string $contact_name
 * @property string $contact_phone
 * @property string|null $contact_email
 * @property string $city
 * @property string $district
 * @property string $full_address
 * @property string|null $notes
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $order_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $category_id
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCancellationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereProgressDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereServiceDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 
        'user_order_id',
        'contact_name',
        'contact_phone',
        'contact_email',
        'full_address',
        'district',
        'city',
        'province', 
        'client_type', 
        'property_type', 
        'design_type',
        'room_count', 
        'business_needs', 
        'company_name', 
        'project_value', 
        'area_size',
        'estimated_budget',
        'deadline_date',
        'last_reminder_sent_at',
        'notes', 
        'cancellation_reason', 
        'order_date',
    ];
    
    protected $casts = [
        'order_date' => 'datetime',
        'design_type' => 'array',
        'deadline_date' => 'date',
        'last_reminder_sent_at' => 'datetime',
        'estimated_budget' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function feedback()
    {
        return $this->hasOne(CustomerFeedback::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    protected static function booted()
    {
        static::creating(function ($order) {
            if ($order->user_id) {
                $maxId = Order::where('user_id', $order->user_id)->max('user_order_id');
                $order->user_order_id = ($maxId ?? 0) + 1;
            }
        });
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class)->latest();
    }

    public function latestDetail()
    {
        return $this->hasOne(OrderDetail::class)->latestOfMany();
    }

    /**
     * Get total spent from order details (final_price)
     */
    public function getTotalSpentAttribute(): float
    {
        return $this->details->whereNotNull('final_price')->max('final_price') ?? 0;
    }

    /**
     * Get budget usage percentage
     */
    public function getBudgetUsagePercentAttribute(): ?float
    {
        if (!$this->estimated_budget || $this->estimated_budget == 0) {
            return null;
        }
        return round(($this->total_spent / $this->estimated_budget) * 100, 1);
    }

    /**
     * Check if budget is over 80%
     */
    public function getIsBudgetWarningAttribute(): bool
    {
        $usage = $this->budget_usage_percent;
        return $usage !== null && $usage >= 80;
    }

    /**
     * Get days since last update
     */
    public function getDaysSinceLastUpdateAttribute(): int
    {
        $lastDetail = $this->latestDetail;
        if (!$lastDetail) {
            return $this->created_at->diffInDays(now());
        }
        return $lastDetail->created_at->diffInDays(now());
    }

    /**
     * Check if order needs reminder (no update > 3 days)
     */
    public function getNeedsReminderAttribute(): bool
    {
        $latestStatus = $this->latestDetail?->status;
        if (!in_array($latestStatus, ['pending', 'in_progress'])) {
            return false;
        }
        return $this->days_since_last_update >= 3;
    }
}

