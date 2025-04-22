<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $casts = [
        'productos' => 'array',
    ];
    protected $fillable = [
        'user_id',
        'nombre_cliente',
        'direccion',
         'fecha',
        'estado',
        'productos',
    ];

    public function usuarios()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(Order::class);
    }

    public function producto()
    {
        return $this->belongsTo(Product::class, 'producto_id');
    }

    public function boleta()
    {
        return $this->hasOne(Boleta::class, 'order_id');
    }
}
