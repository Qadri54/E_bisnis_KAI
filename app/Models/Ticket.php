<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {
    protected $fillable = [
        'ticket_id',
        'jadwal_kereta_id',
        'kereta_id',
        'destinasi_id',
        'tanggal_berangkat',
        'stok_tiket',
    ];

    public function booking() {
        return $this->hasMany(Booking::class);
    }

    public function jadwalKereta() {
        return $this->belongsTo(JadwalKereta::class);
    }

    public function kereta() {
        return $this->belongsTo(Kereta::class);
    }

    public function destinasi() {
        return $this->belongsTo(Destinasi::class);
    }
}
