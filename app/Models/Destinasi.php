<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model
{
    protected $table = 'destinasi';
    protected $primaryKey = 'destinasi_id';
    
    protected $fillable = [
        'destinasi_asal',
        'destinasi_tujuan'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'destinasi_id', 'destinasi_id');
    }
}