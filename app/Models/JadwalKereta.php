<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKereta extends Model
{
    public function ticket() {
        return $this->hasMany(Ticket::class);
    }
}
