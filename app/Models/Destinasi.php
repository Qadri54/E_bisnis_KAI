<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model
{
    public function ticket() {
        return $this->hasMany(Ticket::class);
    }
}
