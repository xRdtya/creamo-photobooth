<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'merchant_id',
        'amount',
        'method',
        'bank_name',
        'account_number',
        'account_holder',
        'status',
        'notes',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}
