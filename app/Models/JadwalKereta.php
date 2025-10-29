<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKereta extends Model
{
    protected $table = 'jadwal_kereta';
    protected $primaryKey = 'jadwal_id';
    
    protected $fillable = [
        'jadwal_keberangkatan',
        'jadwal_sampai'
    ];

    protected $casts = [
        'jadwal_keberangkatan' => 'datetime',
        'jadwal_sampai' => 'datetime'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'jadwal_id', 'jadwal_id');
    }
}