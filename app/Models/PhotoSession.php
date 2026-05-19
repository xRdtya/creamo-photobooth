<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoSession extends Model
{
    protected $fillable = [
        'transaction_id',
        'email',
        'device_name',
        'is_active',
        'last_ping_at',
        'kode_download',
        'link_file_foto',
        'link_file_video',
        'waktu_mulai',
        'waktu_selesai',
        'status_cetak',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'last_ping_at' => 'datetime',
        'waktu_mulai'  => 'datetime',
        'waktu_selesai'=> 'datetime',
    ];

    /**
     * Scope: sesi yang sedang aktif (is_active = true &
     * last_ping_at dalam 5 menit terakhir).
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('last_ping_at', '>=', now()->subMinutes(5));
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
