<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {
    protected $table = 'ticket';
    protected $primaryKey = 'ticket_id';
    protected $fillable = [
        'ticket_id',
        'jadwal_id',
        'kereta_id',
        'destinasi_id',
        'stok_tiket',
        'harga_tiket'
    ];

    public function jadwalKereta() {
        return $this->belongsTo(JadwalKereta::class, 'jadwal_id', 'jadwal_id');
    }

    public function kereta() {
        return $this->belongsTo(Kereta::class, 'kereta_id', 'kereta_id');
    }

    public function destinasi() {
        return $this->belongsTo(Destinasi::class, 'destinasi_id', 'destinasi_id');
    }
}
