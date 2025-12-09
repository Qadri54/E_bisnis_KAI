<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model {
    protected $table = 'booking';
    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'booking_id',
        'user_id',
        'ticket_id',
        'banyak_tiket',
        'tanggal_booking',
        'kode_booking',
        'total_harga',
        'status',
    ];

    public function ticket() {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
