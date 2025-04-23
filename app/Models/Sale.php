<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'ventas';

    protected $fillable = [
        'id',
        'producto_id',
        'cantidad',
        'fecha_venta',
    ];


}
