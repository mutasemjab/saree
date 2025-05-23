<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;
    protected $guarded = [];

 protected $casts = [
        'deposit' => 'decimal:2',
        'withdrawal' => 'decimal:2',
    ];

    // Relationships
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    // Accessors
    public function getTypeAttribute()
    {
        return $this->deposit > 0 ? 'deposit' : 'withdrawal';
    }

    public function getAmountAttribute()
    {
        return $this->deposit > 0 ? $this->deposit : $this->withdrawal;
    }

    public function getFormattedAmountAttribute()
    {
        $amount = $this->amount;
        $sign = $this->type === 'deposit' ? '+' : '-';
        return $sign . number_format($amount, 2) . ' ' . __('messages.currency');
    }

    public function getPerformerAttribute()
    {
        if ($this->user) return $this->user;
        if ($this->driver) return $this->driver;
        if ($this->admin) return $this->admin;
        return null;
    }

    public function getPerformerNameAttribute()
    {
        $performer = $this->performer;
        return $performer ? $performer->name : __('messages.system');
    }

    public function getPerformerTypeAttribute()
    {
        if ($this->user) return 'user';
        if ($this->driver) return 'driver';
        if ($this->admin) return 'admin';
        return 'system';
    }

    // Scopes
    public function scopeDeposits($query)
    {
        return $query->where('deposit', '>', 0);
    }

    public function scopeWithdrawals($query)
    {
        return $query->where('withdrawal', '>', 0);
    }

    public function scopeByWallet($query, $walletId)
    {
        return $query->where('wallet_id', $walletId);
    }

    public function scopeByPerformer($query, $performerType, $performerId)
    {
        switch ($performerType) {
            case 'user':
                return $query->where('user_id', $performerId);
            case 'driver':
                return $query->where('driver_id', $performerId);
            case 'admin':
                return $query->where('admin_id', $performerId);
            default:
                return $query;
        }
    }

    // Methods
    public function reverseTransaction()
    {
        $wallet = $this->wallet;
        $amount = $this->deposit - $this->withdrawal;
        $wallet->decrement('total', $amount);
        return $this->delete();
    }

    public static function getTotalDeposits()
    {
        return self::sum('deposit');
    }

    public static function getTotalWithdrawals()
    {
        return self::sum('withdrawal');
    }

    public static function getNetAmount()
    {
        return self::sum('deposit') - self::sum('withdrawal');
    }

}
