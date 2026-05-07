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
}
