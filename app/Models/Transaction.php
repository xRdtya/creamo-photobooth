<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [
        'id'
    ];

    public function photoSessions()
    {
        return $this->hasMany(PhotoSession::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}
