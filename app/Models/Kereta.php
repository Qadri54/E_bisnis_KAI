<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kereta extends Model
{
    protected $table = 'kereta';
    protected $primaryKey = 'kereta_id';
    
    protected $fillable = [
        'nama_kereta'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'kereta_id', 'kereta_id');
    }
}