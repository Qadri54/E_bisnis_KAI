<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kereta extends Model
{
    public function ticket(): HasMany {
        return $this->hasMany(Ticket::class);
    }
}
