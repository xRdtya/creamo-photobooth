<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Merchant extends Authenticatable
{
    protected $fillable = [
        'business_name',
        'email',
        'password',
        'google_id',
        'apple_id',
        'avatar',
        'subscription',
        'expiry_date',
    ];

    protected $hidden = [
        'password',
    ];

    public function subscription()
    {
        return $this->hasOne(\App\Models\Subscription::class, 'merchant_id', 'id');
    }

    public function hasActiveSubscription(): bool
    {
        $this->refresh();
        
        return $this->subscription === 'active'
            && $this->expiry_date !== null
            && \Carbon\Carbon::parse($this->expiry_date)->isFuture();
    }
}
