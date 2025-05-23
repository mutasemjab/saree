<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'total_distance' => 'decimal:2',
        'order_status' => 'integer',
        'payment_type' => 'integer',
        'payment_method' => 'integer',
    ];
     // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        $statuses = [
            1 => __('messages.pending'),
            2 => __('messages.accepted'),
            3 => __('messages.on_the_way'),
            4 => __('messages.delivered'),
            5 => __('messages.cancelled_by_user'),
            6 => __('messages.cancelled_by_driver'),
        ];

        return $statuses[$this->order_status] ?? __('messages.unknown');
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            1 => 'warning',    // Pending
            2 => 'info',       // Accepted  
            3 => 'primary',    // On the way
            4 => 'success',    // Delivered
            5 => 'danger',     // Cancelled by user
            6 => 'secondary',  // Cancelled by driver
        ];

        return $colors[$this->order_status] ?? 'dark';
    }

    public function getPaymentStatusTextAttribute()
    {
        return $this->payment_type == 1 ? __('messages.paid') : __('messages.unpaid');
    }

    public function getPaymentMethodTextAttribute()
    {
        return $this->payment_method == 1 ? __('messages.cash') : __('messages.visa');
    }

    public function getFormattedPriceAttribute()
    {
        return $this->price ? number_format($this->price, 2) . ' ' . __('messages.currency') : __('messages.not_set');
    }

    public function getFormattedDiscountAttribute()
    {
        return $this->discount ? number_format($this->discount, 2) . ' ' . __('messages.currency') : __('messages.no_discount');
    }

    public function getFormattedFinalPriceAttribute()
    {
        return $this->final_price ? number_format($this->final_price, 2) . ' ' . __('messages.currency') : __('messages.not_set');
    }

    public function getFormattedDistanceAttribute()
    {
        return $this->total_distance ? number_format($this->total_distance, 2) . ' ' . __('messages.km') : __('messages.not_set');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('order_status', 1);
    }

    public function scopeAccepted($query)
    {
        return $query->where('order_status', 2);
    }

    public function scopeOnTheWay($query)
    {
        return $query->where('order_status', 3);
    }

    public function scopeDelivered($query)
    {
        return $query->where('order_status', 4);
    }

    public function scopeCancelledByUser($query)
    {
        return $query->where('order_status', 5);
    }

    public function scopeCancelledByDriver($query)
    {
        return $query->where('order_status', 6);
    }

    public function scopeCancelled($query)
    {
        return $query->whereIn('order_status', [5, 6]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('order_status', 4);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('order_status', [1, 2, 3]);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_type', 1);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_type', 2);
    }

    public function scopeCash($query)
    {
        return $query->where('payment_method', 1);
    }

    public function scopeVisa($query)
    {
        return $query->where('payment_method', 2);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByDriver($query, $driverId)
    {
        return $query->where('driver_id', $driverId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('order_status', $status);
    }

    public function scopeSearchByNumber($query, $number)
    {
        return $query->where('number', 'like', '%' . $number . '%');
    }

    // Methods
    public function isPending()
    {
        return $this->order_status == 1;
    }

    public function isAccepted()
    {
        return $this->order_status == 2;
    }

    public function isOnTheWay()
    {
        return $this->order_status == 3;
    }

    public function isDelivered()
    {
        return $this->order_status == 4;
    }

    public function isCancelled()
    {
        return in_array($this->order_status, [5, 6]);
    }

    public function isCancelledByUser()
    {
        return $this->order_status == 5;
    }

    public function isCancelledByDriver()
    {
        return $this->order_status == 6;
    }

    public function isActive()
    {
        return in_array($this->order_status, [1, 2, 3]);
    }

    public function isCompleted()
    {
        return $this->order_status == 4;
    }

    public function isPaid()
    {
        return $this->payment_type == 1;
    }

    public function isUnpaid()
    {
        return $this->payment_type == 2;
    }

    public function isCash()
    {
        return $this->payment_method == 1;
    }

    public function isVisa()
    {
        return $this->payment_method == 2;
    }

    public function hasDriver()
    {
        return !is_null($this->driver_id);
    }

    public function canBeAssignedDriver()
    {
        return $this->isPending() || $this->isAccepted();
    }

    public function canBeCancelled()
    {
        return !$this->isDelivered() && !$this->isCancelled();
    }

    public function calculateFinalPrice()
    {
        if ($this->price) {
            $discount = $this->discount ?? 0;
            return $this->price - $discount;
        }
        return 0;
    }

    public function updateStatus($status)
    {
        $this->update(['order_status' => $status]);
        return $this;
    }

    public function assignDriver($driverId)
    {
        $this->update(['driver_id' => $driverId]);
        return $this;
    }

    public function markAsPaid()
    {
        $this->update(['payment_type' => 1]);
        return $this;
    }

    public function markAsUnpaid()
    {
        $this->update(['payment_type' => 2]);
        return $this;
    }

    // Static methods
    public static function generateOrderNumber()
    {
        do {
            $number = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
        } while (self::where('number', $number)->exists());

        return $number;
    }

    public static function getStatusOptions()
    {
        return [
            1 => __('messages.pending'),
            2 => __('messages.accepted'),
            3 => __('messages.on_the_way'),
            4 => __('messages.delivered'),
            5 => __('messages.cancelled_by_user'),
            6 => __('messages.cancelled_by_driver'),
        ];
    }

    public static function getPaymentTypeOptions()
    {
        return [
            1 => __('messages.paid'),
            2 => __('messages.unpaid'),
        ];
    }

    public static function getPaymentMethodOptions()
    {
        return [
            1 => __('messages.cash'),
            2 => __('messages.visa'),
        ];
    }

    public static function getTotalRevenue()
    {
        return self::delivered()->sum('final_price');
    }

    public static function getAverageOrderValue()
    {
        return self::delivered()->avg('final_price');
    }

    public static function getOrdersCountByStatus()
    {
        return [
            'pending' => self::pending()->count(),
            'accepted' => self::accepted()->count(),
            'on_the_way' => self::onTheWay()->count(),
            'delivered' => self::delivered()->count(),
            'cancelled_by_user' => self::cancelledByUser()->count(),
            'cancelled_by_driver' => self::cancelledByDriver()->count(),
        ];
    }

}


