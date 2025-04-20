<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'imagenes';

    protected $fillable = ['producto_id', 'ruta', 'es_principal'];

    public function producto()
    {
        return $this->belongsTo(Product::class, 'producto_id');
    }
}
