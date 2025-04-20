<?php

namespace App\Models;

use App\Models\Category;  // Corregido el uso
use App\Models\Detail;    // Corregido el uso
use App\Models\Image;     // Corregido el uso
use App\Models\Sale;      // Corregido el uso

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;  // Si necesitas factorys, esta es la manera correcta de incluirlo

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'categoria_id',
    ];

    // Relación: Un producto pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(Category::class, 'categoria_id');
    }

    // Relación: Un producto tiene muchas imágenes
    public function imagenes()
    {
        return $this->hasMany(Image::class, 'producto_id');
    }

    // Relación: Un producto tiene muchas ventas
    public function ventas()
    {
        return $this->hasMany(Sale::class, 'producto_id');
    }

    // Relación: Un producto tiene muchas reseñas
    public function detail()
    {
        return $this->hasMany(Detail::class, 'producto_id');
    }

    public function imagenPrincipal()
    {
        return $this->hasOne(Image::class, 'producto_id')->where('es_principal', true);
    }
}
