<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverNotified extends Model
{
    protected $table = 'driver_notified';

    protected $fillable = [
        'order_id',
        'driver_id',
        'distance_km',
        'radius_km',
        'status',
        'notified_at',
        'responded_at',
    ];

    protected $casts = [
        'notified_at'  => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}