<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $guarded = [];

   protected $casts = [
        'total' => 'decimal:2',
        'created_at' => 'datetime',
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

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    // Accessors
    public function getOwnerAttribute()
    {
        if ($this->user) return $this->user;
        if ($this->driver) return $this->driver;
        if ($this->admin) return $this->admin;
        return null;
    }

    public function getOwnerTypeAttribute()
    {
        if ($this->user) return 'user';
        if ($this->driver) return 'driver';
        if ($this->admin) return 'admin';
        return 'unknown';
    }

    public function getOwnerNameAttribute()
    {
        $owner = $this->owner;
        return $owner ? $owner->name : __('messages.no_owner');
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 2) . ' ' . __('messages.currency');
    }

    // Scopes
    public function scopeByOwnerType($query, $type)
    {
        switch ($type) {
            case 'user':
                return $query->whereNotNull('user_id');
            case 'driver':
                return $query->whereNotNull('driver_id');
            case 'admin':
                return $query->whereNotNull('admin_id');
            default:
                return $query;
        }
    }

    public function scopeWithPositiveBalance($query)
    {
        return $query->where('total', '>', 0);
    }

    public function scopeWithNegativeBalance($query)
    {
        return $query->where('total', '<', 0);
    }

    // Methods
    public function addTransaction($type, $amount, $note = null, $performedBy = null)
    {
        $data = [
            'wallet_id' => $this->id,
            'note' => $note,
        ];

        if ($type === 'deposit') {
            $data['deposit'] = $amount;
            $data['withdrawal'] = 0;
        } else {
            $data['deposit'] = 0;
            $data['withdrawal'] = $amount;
        }

        // Add performer
        if ($performedBy) {
            if ($performedBy instanceof User) {
                $data['user_id'] = $performedBy->id;
            } elseif ($performedBy instanceof Driver) {
                $data['driver_id'] = $performedBy->id;
            } elseif ($performedBy instanceof Admin) {
                $data['admin_id'] = $performedBy->id;
            }
        }

        $transaction = WalletTransaction::create($data);
        
        // Update wallet balance
        $balanceChange = $type === 'deposit' ? $amount : -$amount;
        $this->increment('total', $balanceChange);

        return $transaction;
    }

    public function getTransactionsCount()
    {
        return $this->transactions()->count();
    }

}
