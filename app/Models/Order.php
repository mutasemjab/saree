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
        'search_started_at' => 'datetime',  // For cron-based search
        'last_search_at' => 'datetime',     // For cron-based search
    ];

    // Order status constants
    const STATUS_PENDING = 1;
    const STATUS_ACCEPTED = 2;
    const STATUS_ON_THE_WAY = 3;
    const STATUS_DELIVERED = 4;
    const STATUS_CANCELLED_BY_USER = 5;
    const STATUS_CANCELLED_BY_DRIVER = 6;
    const STATUS_NO_DRIVERS_AVAILABLE = 7;  // NEW STATUS

    // Relationships
    public function address()
    {
        return $this->belongsTo(UserAddress::class);
    }
   
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    protected static function booted()
    {
        static::created(function ($order) {
            $order->number = 'ORD-' . $order->id;
            $order->save();
        });
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
            7 => __('messages.no_drivers_available'),  // NEW
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
            7 => 'muted',      // No drivers available - NEW
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
        return $query->where('order_status', self::STATUS_PENDING);
    }

    public function scopeAccepted($query)
    {
        return $query->where('order_status', self::STATUS_ACCEPTED);
    }

    public function scopeOnTheWay($query)
    {
        return $query->where('order_status', self::STATUS_ON_THE_WAY);
    }

    public function scopeDelivered($query)
    {
        return $query->where('order_status', self::STATUS_DELIVERED);
    }

    public function scopeCancelledByUser($query)
    {
        return $query->where('order_status', self::STATUS_CANCELLED_BY_USER);
    }

    public function scopeCancelledByDriver($query)
    {
        return $query->where('order_status', self::STATUS_CANCELLED_BY_DRIVER);
    }

    public function scopeNoDriversAvailable($query)
    {
        return $query->where('order_status', self::STATUS_NO_DRIVERS_AVAILABLE);
    }

    public function scopeCancelled($query)
    {
        return $query->whereIn('order_status', [
            self::STATUS_CANCELLED_BY_USER, 
            self::STATUS_CANCELLED_BY_DRIVER
        ]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('order_status', self::STATUS_DELIVERED);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('order_status', [
            self::STATUS_PENDING, 
            self::STATUS_ACCEPTED, 
            self::STATUS_ON_THE_WAY
        ]);
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
        return $this->order_status == self::STATUS_PENDING;
    }

    public function isAccepted()
    {
        return $this->order_status == self::STATUS_ACCEPTED;
    }

    public function isOnTheWay()
    {
        return $this->order_status == self::STATUS_ON_THE_WAY;
    }

    public function isDelivered()
    {
        return $this->order_status == self::STATUS_DELIVERED;
    }

    public function isCancelled()
    {
        return in_array($this->order_status, [
            self::STATUS_CANCELLED_BY_USER, 
            self::STATUS_CANCELLED_BY_DRIVER
        ]);
    }

    public function isCancelledByUser()
    {
        return $this->order_status == self::STATUS_CANCELLED_BY_USER;
    }

    public function isCancelledByDriver()
    {
        return $this->order_status == self::STATUS_CANCELLED_BY_DRIVER;
    }

    public function isNoDriversAvailable()
    {
        return $this->order_status == self::STATUS_NO_DRIVERS_AVAILABLE;
    }

    public function isActive()
    {
        return in_array($this->order_status, [
            self::STATUS_PENDING, 
            self::STATUS_ACCEPTED, 
            self::STATUS_ON_THE_WAY
        ]);
    }

    public function isCompleted()
    {
        return $this->order_status == self::STATUS_DELIVERED;
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
        return in_array($this->order_status, [
            self::STATUS_PENDING,
            self::STATUS_ACCEPTED,
            self::STATUS_NO_DRIVERS_AVAILABLE
        ]);
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

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => __('messages.pending'),
            self::STATUS_ACCEPTED => __('messages.accepted'),
            self::STATUS_ON_THE_WAY => __('messages.on_the_way'),
            self::STATUS_DELIVERED => __('messages.delivered'),
            self::STATUS_CANCELLED_BY_USER => __('messages.cancelled_by_user'),
            self::STATUS_CANCELLED_BY_DRIVER => __('messages.cancelled_by_driver'),
            self::STATUS_NO_DRIVERS_AVAILABLE => __('messages.no_drivers_available'),
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
            'no_drivers_available' => self::noDriversAvailable()->count(),
        ];
    }
}