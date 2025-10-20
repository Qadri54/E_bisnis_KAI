<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'ticket_id',
        'banyak_tiket',
        'status',
    ];
}
