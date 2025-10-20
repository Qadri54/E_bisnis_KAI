<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Ticket extends Model {
    protected $fillable = [
        'ticket_id',
        'jadwal_kereta_id',
        'kereta_id',
        'destinasi_id',
        'tanggal_berangkat',
        'stok_tiket',
    ];

    public function booking(): BelongsToMany {
        return $this->belongsToMany(User::class,'booking','ticket_id','user_id');
    }

    public function jadwalKereta(): BelongsTo {
        return $this->belongsTo(JadwalKereta::class);
    }

    public function kereta(): BelongsTo {
        return $this->belongsTo(Kereta::class);
    }

    public function destinasi(): BelongsTo {
        return $this->belongsTo(Destinasi::class);
    }
}
