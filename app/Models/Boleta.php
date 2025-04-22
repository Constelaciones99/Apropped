<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boleta extends Model
{
    protected $table = 'boletas';

    protected $fillable = [
        'id',
        'user_id',
        'order_id',
        'numero',
        'tipo',
        'fecha',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
