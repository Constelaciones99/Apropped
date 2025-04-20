<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'productos' => 'array',
    ];
    protected $fillable = [
        'producto_id',
        'user_id',
        'nombre_cliente',
        'direccion',
        'cantidad',
        'estado',
        'productos',
        'boleta',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function producto()
    {
        return $this->belongsTo(Product::class, 'producto_id');
    }
}
