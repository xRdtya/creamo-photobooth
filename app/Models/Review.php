<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'merchant_id',
        'customer_name',
        'email',
        'rating',
        'comment',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}
