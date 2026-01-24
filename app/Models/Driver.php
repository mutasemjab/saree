<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Driver extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'activate' => 'integer',
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }



    /**
     * Get the driver's activation status.
     */
    public function getActivationStatusAttribute()
    {
        return $this->activate == 1 ? __('messages.active') : __('messages.inactive');
    }

    /**
     * Check if driver is active.
     */
    public function isActive()
    {
        return $this->activate == 1;
    }

    /**
     * Scope a query to only include active drivers.
     */
    public function scopeActive($query)
    {
        return $query->where('activate', 1);
    }

    /**
     * Scope a query to only include inactive drivers.
     */
    public function scopeInactive($query)
    {
        return $query->where('activate', 2);
    }
}
