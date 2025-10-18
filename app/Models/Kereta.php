<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kereta extends Model
{
    public function ticket() {
        return $this->hasMany(Ticket::class);
    }
}
