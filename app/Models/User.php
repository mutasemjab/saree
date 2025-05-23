<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;



class User extends Authenticatable
{
   use HasApiTokens, HasFactory, Notifiable;

   /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
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

   protected $casts = [
        'lat' => 'double',
        'lng' => 'double',
        'activate' => 'integer',
    ];


    /**
     * Get the user's activation status.
     */
    public function getActivationStatusAttribute()
    {
        return $this->activate == 1 ? __('messages.active') : __('messages.inactive');
    }

    /**
     * Check if user is active.
     */
    public function isActive()
    {
        return $this->activate == 1;
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('activate', 1);
    }

    /**
     * Scope a query to only include inactive users.
     */
    public function scopeInactive($query)
    {
        return $query->where('activate', 2);
    }

   public function addresses()
   {
      return $this->hasMany(UserAddress::class);
   }
   public function orders()
   {
      return $this->hasMany(Order::class);
   }

   public function transfers()
   {
      return $this->hasMany(Transfer::class);
   }


   public function wallets()
   {
      return $this->hasMany(Wallet::class);
   }

 
}
