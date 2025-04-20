<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Image;


class VendedorControler extends Controller
{
    public function vender(){

        $productos = Product::paginate(6);
        $categorias = Category::all(); // Asegúrate de importar el modelo Categoria
        $imagenes=Image::all();

        return view('vendedor.index',compact('productos', 'categorias','imagenes'));
    }

    public function mostrarProductos(Request $request)
    {

        $query = Product::query();

        // Filtro por nombre
        if ($request->has('nombre') && $request->nombre) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        // Filtro por categoría
        if ($request->has('categoria_id') && $request->categoria_id) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Filtro por precio
        if ($request->has('precio') && $request->precio) {
            if ($request->precio == 'menor') {
                $query->orderBy('precio', 'asc');
            } else {
                $query->orderBy('precio', 'desc');
            }
        }

        // Paginación con 6 productos por página
        $productos = $query->paginate(6);

        // Obtener las categorías para el filtro
        $categorias = Category::all();

        if ($request->ajax()) {
            $imagenes = Image::all();
            return view('vendedor.lista', compact('productos', 'imagenes'))->render();
        }

        // ⚠️ Esto solo debe ejecutarse si NO es AJAX (como al cargar la página por primera vez)
        $imagenes = Image::all();
        return view('vendedor.index', compact('productos', 'categorias', 'imagenes'));
    }
}
