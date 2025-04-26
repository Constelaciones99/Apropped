<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    protected $table = 'detalles_venta';

    protected $fillable = [
        'id',
        'id_usuario',
        'productos',
        'fecha',
    ];
}
